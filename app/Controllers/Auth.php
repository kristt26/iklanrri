<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;
use App\Models\EmployeeModel;
use App\Models\PemesanModel;
use App\Models\PetugasModel;
use Google\Client as Google_Client;
use Google_Service_Oauth2;

class Auth extends ResourceController
{
    protected $userModel;
    public $session;
    public function __construct()
    {
        $this->userModel = new \App\Models\UserModel();
        $this->session = session();
    }

    public function index()
    {
        // require_once APPPATH."libraries/vendor/autoload.php";
        $result = $this->userModel->check();
        $google_client = new \Google_Client();

        $google_client->setClientId('635155083806-c7v7749em1v04u5oc194fq8f6gjd2hkd.apps.googleusercontent.com'); //Define your ClientID

        $google_client->setClientSecret('840SMc_PaafoIhvn_aLZlDUj'); //Define your Client Secret Key

        $google_client->setRedirectUri(base_url() . '/auth'); //Define your Redirect Uri

        $google_client->addScope('email');

        $google_client->addScope('profile');

        if ($this->request->getVar('code')) {
            $token = $google_client->fetchAccessTokenWithAuthCode($this->request->getVar('code'));
            if (!isset($token["error"])) {
                // $google_client->setAccessToken($token['access_token']);
                // $google_client->setRefreshAccessToken($token['refresh_token']);
                // $google_client->setAccessTokenCreated($token['created']);
                // $google_client->setAccessTokenExpiresIn($token['expires_in']);
                $this->session->set('access_token', $token['access_token']);
                $google_service = new \Google_Service_Oauth2($google_client);
                $data = $google_service->userinfo->get();
                $current_datetime = date('Y-m-d H:i:s');
                if ($this->userModel->isAlreadyRegister($data['id'])) {
                    $user_data = array(
                        'first_name' => $data['given_name'],
                        'last_name'  => $data['family_name'],
                        'email' => $data['email'],
                        'profile_picture' => $data['picture'],
                        'updated_at' => $current_datetime
                    );
                    $this->userModel->updateUserGoogle($user_data, $data['id']);
                } else {
                    $user_data = array(
                        'login_oauth_uid' => $data['id'],
                        'first_name'  => $data['given_name'],
                        'last_name'   => $data['family_name'],
                        'email'  => $data['email'],
                        'profile_picture' => $data['picture']
                    );
                    $userid = $this->userModel->insertUserGoogle($user_data);
                }
                $user_data = $this->userModel->readData($data['id']);
                $user_data['logged_in']= true;
                $user_data['role']= "Pemesan";
                $this->session->set($user_data);
                return redirect()->to(base_url("home"));
            }
        }
        if (!$this->session->set('access_token')) {
            $data['loginButton'] = $google_client->createAuthUrl();
            $a = $data;
            return view('auth', $data);
        }
    }

    public function login()
    {
        $session = session();
        $data = (array)$this->request->getJSON();
        $result = $this->userModel->login($data);
        if ($result) {
            $bio = [];
            if ($result['role'] == 'Pemesan') {
                $pemesan = new PemesanModel();
                $bio = $pemesan->where("userid", $result['id'])->first();
            } else {
                $petugas = new PetugasModel();
                $bio = $petugas->where("userid", $result['id'])->first();
            }
            $ses = [
                "id" => $result['id'],
                "email" => $result['email'],
                "role" => $result['role'],
                "nama" => $bio['nama'],
                'logged_in' => TRUE
            ];
            $session->set($ses);
            return $this->respond($ses);
        } else {
            return $this->fail("Data Tidak Ditemukan");
        }
    }

    public function logout()
    {
        $session = session();
        $session->destroy();
        return redirect()->to('/auth');
    }
}
