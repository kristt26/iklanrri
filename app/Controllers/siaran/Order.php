<?php

namespace App\Controllers\Siaran;

use App\Controllers\BaseController;
use App\Models\IklanModel;
use CodeIgniter\API\ResponseTrait;

class Order extends BaseController
{
    use ResponseTrait;
    public $iklan;

    public function __construct()
    {
        $this->iklan = new IklanModel();
    }

    public function index()
    {
        $data['datamenu'] = ['menu' => "Daftar Order"];
        $data['sidebar'] = view('layout/sidebar');
        $data['header'] = view('layout/header');
        $data['content'] = view('siaran/order');
        return view('layout/layout', $data);
    }

    public function create()
    {
        $data = (array) $this->request->getJSON();
        $this->layanan->save($data);
        $data['id'] = $this->layanan->insertID();
        return $this->respondCreated($data);
    }

    public function read()
    {
        $result = $this->iklan->order();
        return $this->respond($result);
    }

    public function update()
    {
        $data = (array) $this->request->getJSON();
        $result = $this->iklan->update($data['id'], [
            'status' => (int) $data['status'],
        ]);
        return $this->respond($result);
    }

    public function delete($id)
    {
        return $this->respond($this->layanan->delete($id));
    }

    public function readTayang()
    {
        $result = $this->iklan->iklanTayang();
        return $this->respond($result);
    }
}
