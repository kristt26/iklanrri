<?php

namespace App\Controllers\Admin;

use CodeIgniter\API\ResponseTrait;
use App\Controllers\BaseController;
use App\Models\TarifModel;
use App\Models\LayananModel;

class Tarif extends BaseController
{
    use ResponseTrait;
    public $tarif;
    public $layanan;

    public function __construct()
    {
        $this->tarif = new TarifModel();
        $this->layanan = new LayananModel();
    }


    public function index()
    {
        $data['datamenu'] = ['menu' => "Tarif"];
        $data['sidebar'] = view('layout/sidebar');
        $data['header'] = view('layout/header');
        $data['content'] = view('admin/tarif');
        return view('layout/layout', $data);
    }

    public function create()
    {
        $data = (array)$this->request->getJSON();
        $this->tarif->save($data);
        $data['id'] = $this->tarif->insertID();
        return $this->respondCreated($data);
    }

    public function read($id = null)
    {
        if ($id) {
            return $this->respond($this->tarif->where('id', $id)->first());
        } else {
            $layanans = $this->layanan->get()->getResultArray();
            $data = [['kategori'=>"Non Komersial"], ['kategori'=>"Komersial"]];
            $newArray = [];
            foreach ($layanans as $key => $layanan) {
                foreach ($data as $key => $kategoti) {
                    $layanan['kategori'] = [];
                    foreach ($data as $key => $value) {
                        $item = [
                            'id'=>$key+1,
                            'kategori'=>$value['kategori'],
                            'tarif'=> $this->tarif->where(['layananid'=>$layanan['id'], 'kategori'=>$value['kategori']])->get()->getResultArray()
                        ];
                        array_push($layanan['kategori'], $item);
                    }
                }
                array_push($newArray, $layanan);
            }
            return $this->respond($newArray);
        }
    }

    public function update()
    {
        $data = (array)$this->request->getJSON();
        $result = $this->tarif->update($data['id'], [
            'kategori' => $data['kategori'],
            'jenis' => $data['jenis'],
            'uraian' => $data['uraian'],
            'satuan' => $data['satuan'],
            'tarif' => $data['tarif']
        ]);
        return $this->respond($result);
    }

    public function delete($id)
    {
        return $this->respond($this->tarif->delete($id));
    }
}
