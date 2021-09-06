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
        $data['datamenu'] = ['menu' => "Dashboard"];
        $data['sidebar'] = view('layout/sidebar');
        $data['header'] = view('layout/header');
        $data['content'] = view('home');
        return view('layout/layout', $data);
    }

    public function getHome()
    {
        $tarif = new \App\Models\TarifModel();
        $layanans = ['Spot Iklan', 'Pengumuman'];
        $types = ['Prime Time', 'Reguler Time'];
        $kategoris = ['Non Komersial', 'Komersial'];
        $data = [];
        foreach ($layanans as $keyLayanan => $valueLayanan) {
            $item = [
                'layanan' => $valueLayanan,
                'kategori' => array(),
            ];
            foreach ($kategoris as $keyKategori => $valueKategori) {
                $kategori = [
                    'kategori' => $valueKategori,
                    'dataKategori' => array(),
                ];
                if ($valueLayanan == "Spot Iklan") {
                    foreach ($types as $keyType => $valueType) {
                        $type = [
                            'jenis' => $valueType,
                            'data' => $tarif->query("SELECT
								*
							FROM
								`tarif`
								LEFT JOIN `layanan` ON `tarif`.`layananid` = `layanan`.`id` WHERE kategori='$valueKategori' AND layanan.layanan='$valueLayanan' AND jenis='$valueType'")->getResultArray(),
                        ];
                        array_push($kategori['dataKategori'], $type);
                    }
                } else {
                    $itemData = $tarif->query("SELECT
						*
					FROM
						`tarif`
						LEFT JOIN `layanan` ON `tarif`.`layananid` = `layanan`.`id` WHERE kategori='$valueKategori' AND layanan.layanan='$valueLayanan'")->getResultArray();
                    $kategori['dataKategori'] = $itemData;

                }
                array_push($item['kategori'], $kategori);
            }
            array_push($data, $item);
        }
        echo json_encode($data);
    }
}