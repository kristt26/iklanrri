<?php namespace App\Controllers\Pimpinan;

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

    public function read()
    {
        $result = $this->iklan->query("SELECT month(tanggal) as stringbln, count(*) as jumlah
            from iklan WHERE year(tanggal) = year(curdate())
            group by month(tanggal)")->getResultArray();
        return $this->respond($result);
    }
}