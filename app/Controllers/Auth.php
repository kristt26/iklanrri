<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use Firebase\JWT\JWT;
use Google\Client as Google_Client;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;

class Auth extends ResourceController
{
    protected $userModel;
    public $session;
    public $google_client;
    public $mail;
    protected $encrypter;

    public function __construct()
    {
        $this->userModel = new \App\Models\UserModel();
        $this->session = session();
        $this->google_client = new \Google_Client();
        $this->mail = new PHPMailer();
        $this->encrypter = \Config\Services::encrypter();
    }

    public function index()
    {
        // require_once APPPATH."libraries/vendor/autoload.php";
        $result = $this->userModel->check();

        $this->google_client->setClientId('635155083806-c7v7749em1v04u5oc194fq8f6gjd2hkd.apps.googleusercontent.com'); //Define your ClientID

        $this->google_client->setClientSecret('840SMc_PaafoIhvn_aLZlDUj'); //Define your Client Secret Key

        $this->google_client->setRedirectUri(base_url() . '/auth'); //Define your Redirect Uri

        $this->google_client->addScope('email');

        $this->google_client->addScope('profile');

        if ($this->request->getVar('code')) {
            $token = $this->google_client->fetchAccessTokenWithAuthCode($this->request->getVar('code'));
            if (!isset($token["error"])) {
                // $this->google_client->setAccessToken($token['access_token']);
                // $this->google_client->setRefreshAccessToken($token['refresh_token']);
                // $this->google_client->setAccessTokenCreated($token['created']);
                // $this->google_client->setAccessTokenExpiresIn($token['expires_in']);
                $this->session->set('access_token', $token['access_token']);
                $google_service = new \Google_Service_Oauth2($this->google_client);
                $data = $google_service->userinfo->get();
                $current_datetime = date('Y-m-d H:i:s');
                if ($this->userModel->isAlreadyRegister($data['id'])) {
                    $user_data = array(
                        'first_name' => $data['given_name'],
                        'last_name' => $data['family_name'],
                        'email' => $data['email'],
                        'profile_picture' => $data['picture'],
                        'updated_at' => $current_datetime,
                    );
                    $this->userModel->updateUser($user_data, $data['id']);
                    $user_data = $this->userModel->readData($data['id']);
                    $user_data['logged_in'] = true;
                    $user_data['role'] = "Pemesan";
                    $this->session->set($user_data);
                    return redirect()->to(base_url("home"));
                } else {
                    if ($this->session->get('access_token') !== null) {
                        $this->google_client->revokeToken(['refresh_token' => $this->session->get('access_token')]);
                    }
                    $this->session->setFlashdata('pesan', 'Email belum terdaftar');
                    return redirect()->to('/auth');
                    // $user_data = array(
                    //     'login_oauth_uid' => $data['id'],
                    //     'first_name'  => $data['given_name'],
                    //     'last_name'   => $data['family_name'],
                    //     'email'  => $data['email'],
                    //     'profile_picture' => $data['picture']
                    // );
                    // $userid = $this->userModel->insertUser($user_data);
                }
            }
        }
        if (!$this->session->set('access_token')) {
            $data['loginButton'] = $this->google_client->createAuthUrl();
            $a = $data;
            return view('auth', $data);
        }
    }

    public function login()
    {
        $session = session();
        $data = (array) $this->request->getJSON();
        $result = $this->userModel->login($data);
        if ($result) {
            $result['logged_in'] = true;
            $result['nama'] = $result['fullname'];
            $session->set($result);
            return $this->respond($result);
        } else {
            return $this->fail("Data Tidak Ditemukan");
        }
    }

    public function register()
    {
        try {
            $data = (array) $this->request->getJSON();
            $data['password'] = base64_encode($this->encrypter->encrypt($data['password']));
            $item = [
                'fullname' => $data['fullname'],
                'username' => $data['username'],
                'password' => $data['password'],
                'email' => $data['email'],
            ];
            $result = $this->userModel->insertUser($item);
            if ($result) {
                $data['id'] = $result;
                $data['exp'] = time() + (60 * 60);
                $jwt = JWT::encode($data, TOKENJWT);
                $data['tokenconfirm'] = $jwt;
                $pesan = view('mailconfirm', $data);
                $email = $this->sendMail($data, $pesan);
                $this->respond([true]);
            } else {
                return $this->fail(['message' => 'Username atau email Sudah Digunakakan, gunakan yang lain']);
            }
        } catch (\Throwable$e) {
            $pesan = $e->getMessage();
            if ($e->getCode() == "1062") {
                return $this->fail(['message' => 'Username atau email Sudah Digunakakan, gunakan yang lain']);
            } else {
                return $this->fail(['message' => 'Proses gagal Silahkan Coba Lagi']);
            }
        }
    }

    public function confirm()
    {
        try {
            JWT::$leeway = 3600;
            $token = $this->request->getGet('token');
            $decoded = JWT::decode($token, TOKENJWT, array('HS256'));
            $this->userModel->update($decoded->id, ['status' => '1']);
            $this->session->setFlashdata('pesan', 'Silahkan Login untuk memulai');
            return redirect()->to('/auth');
        } catch (\Exception$e) {
            $this->respond(['message' => $e->getMessage()]);
            //  echo 'Caught exception: ',  $e->getMessage(), "\n";
        }
    }

    public function logout()
    {
        if ($this->session->get('access_token') !== null) {
            $this->google_client->revokeToken(['refresh_token' => $this->session->get('access_token')]);
        }
        $session = session();
        $session->destroy();
        return redirect()->to('/auth');
    }

    public function sendMail($data, $pesan)
    {
        try {
            // $this->mail->SMTPDebug = SMTP::DEBUG_SERVER;
            $this->mail->isSMTP();
            $this->mail->Host = 'smtp.gmail.com';
            $this->mail->SMTPAuth = true;
            $this->mail->Username = 'emailfortesting1011@gmail.com';
            $this->mail->Password = '26031988@Aj';
            $this->mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
            $this->mail->Port = 465;
            $this->mail->setFrom('noreply@rrijayapura.com', 'RRI Nusantara 1');
            $this->mail->addAddress($data['email'], $data['fullname']);
            $this->mail->isHTML(true);
            $this->mail->Subject = 'Email Confirmation';
            $this->mail->msgHTML($pesan);
            $this->mail->send();
            return true;
        } catch (Exception $e) {
            $this->mail->ErrorInfo;
            return false;
        }
    }
}
