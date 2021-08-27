<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\API\ResponseTrait;
use Google\Client as Google_Client;

class Profile extends BaseController
{
    use ResponseTrait;
    public $google_client;
    public $userModel;
    public $session;
    public function __construct()
    {
        $userModel = new \App\Models\UserModel();
        $this->google_client = new \Google_Client();
        $this->userModel = new \App\Models\UserModel();
        $this->session = session();
    }

    public function index()
    {
        $this->google_client->setClientId('635155083806-c7v7749em1v04u5oc194fq8f6gjd2hkd.apps.googleusercontent.com'); //Define your ClientID

        $this->google_client->setClientSecret('840SMc_PaafoIhvn_aLZlDUj'); //Define your Client Secret Key

        $this->google_client->setRedirectUri(base_url() . '/profile'); //Define your Redirect Uri

        $this->google_client->addScope('email');

        $this->google_client->addScope('profile');
        if ($this->request->getVar('code')) {
            $token = $this->google_client->fetchAccessTokenWithAuthCode($this->request->getVar('code'));
            if (!isset($token["error"])) {
                $this->session->set('access_token', $token['access_token']);
                $google_service = new \Google_Service_Oauth2($this->google_client);
                $data = $google_service->userinfo->get();
                $current_datetime = date('Y-m-d H:i:s');
                $user_data = array(
                    'login_oauth_uid' => $data['id'],
                    'first_name' => $data['given_name'],
                    'last_name' => $data['family_name'],
                    'email' => $data['email'],
                    'profile_picture' => $data['picture'],
                    'updated_at' => $current_datetime,
                );
                $this->userModel->updateUserGoogle($user_data, $this->session->get('id'));
                $user_data['logged_in'] = true;
                $user_data['role'] = "Pemesan";
                $this->session->set($user_data);
                return redirect()->to(base_url("profile"));
            }
        }
        if (!$this->session->set('access_token')) {
            $google['loginButton'] = $this->google_client->createAuthUrl();
            $data['datamenu'] = ['menu' => "Dashboard"];
            $data['sidebar'] = view('layout/sidebar');
            $data['header'] = view('layout/header');
            $data['content'] = view('profile', $google);
            return view('layout/layout', $data);
        }
    }

    public function read()
    {
        $result = $this->userModel->where('id', $this->session->get('id'))->get()->getRowArray();
        return $this->respond($result);
    }

    public function update()
    {
        $data = (array) $this->request->getJSON();
        $result = $this->userModel->update($data['id'], [
            'fullname' => $data['fullname'],
            'kontak' => $data['kontak'],
            'alamat' => $data['alamat'],
        ]);
        return $this->respond($result);
    }
}
