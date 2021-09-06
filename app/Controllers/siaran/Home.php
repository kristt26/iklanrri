<?php namespace App\Controllers\Siaran;

use App\Controllers\BaseController;
use CodeIgniter\API\ResponseTrait;

class Home extends BaseController
{
    use ResponseTrait;
    public $data;
    public function __construct()
    {
        $this->data = new \App\Models\HomeModel();
        $this->iklan = new \App\Models\IklanModel();
    }

    public function index()
    {
        $result = $this->data->getData();

        $data['datamenu'] = ['menu' => "Dashboard"];
        $data['sidebar'] = view('layout/sidebar');
        $data['header'] = view('layout/header');
        $data['content'] = view('admin/home', $result);
        return view('layout/layout', $data);
    }
}