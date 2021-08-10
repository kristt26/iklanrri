<?php

namespace App\Controllers\Admin;

use CodeIgniter\API\ResponseTrait;
use App\Controllers\BaseController;
use App\Models\UserModel;

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
        $data['datamenu'] = ['menu'=>"User"];
        $data['sidebar'] = view('layout/sidebar');
        $data['header'] = view('layout/header');
        $data['content'] = view('admin/user');
        return view('layout/layout', $data);
    }


    public function read()
    {
        return $this->respond($this->User->get()->getResultArray());
    }

   
}
