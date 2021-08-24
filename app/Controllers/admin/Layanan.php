<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\LayananModel;
use CodeIgniter\API\ResponseTrait;

class Layanan extends BaseController
{
    use ResponseTrait;
    public $layanan;

    public function __construct()
    {
        $this->layanan = new LayananModel();
    }

    public function index()
    {
        $data['datamenu'] = ['menu' => "Layanan"];
        $data['sidebar'] = view('layout/sidebar');
        $data['header'] = view('layout/header');
        $data['content'] = view('admin/layanan');
        return view('layout/layout', $data);
    }

    public function create()
    {
        $data = (array) $this->request->getJSON();
        $this->layanan->save($data);
        $data['id'] = $this->layanan->insertID();
        return $this->respondCreated($data);
    }

    public function read($id = null)
    {
        if ($id) {
            return $this->respond($this->layanan->where('id', $id)->first());
        } else {
            return $this->respond($this->layanan->get()->getResultArray());
        }
    }

    public function update()
    {
        $data = (array) $this->request->getJSON();
        $result = $this->layanan->update($data['id'], [
            'layanan' => $data['layanan'],
            'status' => (int) $data['status'],
        ]);
        return $this->respond($result);
    }

    public function delete($id)
    {
        return $this->respond($this->layanan->delete($id));
    }
}
