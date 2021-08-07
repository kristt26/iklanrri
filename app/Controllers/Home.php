<?php namespace App\Controllers;
use App\Controllers\BaseController;
class Home extends BaseController
{
	public function __construct()
	{
		$userModel = new \App\Models\UserModel();
	}

	public function index()
	{
		$data['datamenu'] = ['menu'=>"Dashboard"];
		$data['sidebar'] = view('layout/sidebar');
		$data['header'] = view('layout/header');
		$data['content'] = "";
		return view('layout/layout', $data);
	}
}
