<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\UserModel;
use CodeIgniter\API\ResponseTrait;

class Users extends BaseController
{
    use ResponseTrait;
    public $User;

    public function __construct()
    {
        $this->User = new UserModel();
    }

    public function index()
    {
        $data['datamenu'] = ['menu' => "User"];
        $data['sidebar'] = view('layout/sidebar');
        $data['header'] = view('layout/header');
        $data['content'] = view('admin/users');
        return view('layout/layout', $data);
    }

    public function read()
    {
        return $this->respond($this->User->get()->getResultArray());
    }

}
