<?php namespace App\Controllers\Admin;

use App\Controllers\BaseController;

class Home extends BaseController
{
	public $data;
    public function __construct()
    {
        $this->data = new \App\Models\HomeModel();
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