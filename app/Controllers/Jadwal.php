<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\JadwalModel;
use CodeIgniter\API\ResponseTrait;

class Jadwal extends BaseController
{
    use ResponseTrait;
    public $jadwal;

    public function __construct()
    {
        $this->jadwal = new JadwalModel();
    }

    public function index()
    {
        $data['datamenu'] = ['menu' => "Jadwal Siaran"];
        $data['sidebar'] = view('layout/sidebar');
        $data['header'] = view('layout/header');
        $data['content'] = view('jadwal');
        return view('layout/layout', $data);
    }

    public function read()
    {
        $tanggal = $this->jadwal->query("SELECT
        (SELECT tanggalmulai FROM iklan ORDER BY tanggalmulai ASC LIMIT 1) AS awal,
        (SELECT tanggalselesai FROM iklan ORDER BY tanggalselesai DESC LIMIT 1) AS akhir")->getRowArray();
        // $result = $this->CheckTanggal($tanggal);
        // $result = $this->tanggalsiaran($tanggal);
        $result = $this->jadwal->query("SELECT
        `jadwalsiaran`.*,
        `layanan`.`layanan`,
        `layanan`.`id` AS `layananid`
    FROM
        `jadwalsiaran`
        LEFT JOIN `iklan` ON `iklan`.`id` = `jadwalsiaran`.`iklanid`
        LEFT JOIN `tarif` ON `tarif`.`id` = `iklan`.`tarifid`
        LEFT JOIN `layanan` ON `layanan`.`id` = `tarif`.`layananid` ORDER BY jadwalsiaran.tanggal asc")->getResultArray();
        return $this->respond($result);
    }

    public function CheckTanggal($tanggal)
    {
        $dari = $tanggal['awal']; // tanggal mulai
        $sampai = $tanggal['akhir']; // tanggal akhir
        $data = [];
        while (strtotime($dari) <= strtotime($sampai)) {
            array_push($data, $dari);
            $dari = date("Y-m-d", strtotime("+1 day", strtotime($dari))); //looping tambah 1 date
        }
        $b = $data;
        return $data;
    }

    public function tanggalsiaran($data)
    {
        try {
            $jadwal = new \App\Models\JadwalModel();
            $layanan = new \App\Models\LayananModel();
            $dataLayanan = $layanan->get()->getResultArray();
            $dari = $data['awal'];
            $sampai = $data['akhir'];
            $datawaktu = ['Pagi', 'Siang', 'Sore'];
            $siaran = $jadwal->query("SELECT
                `jadwalsiaran`.*,
                `layanan`.`layanan`,
                `layanan`.`id` AS `layananid`
            FROM
                `jadwalsiaran`
                LEFT JOIN `iklan` ON `iklan`.`id` = `jadwalsiaran`.`iklanid`
                LEFT JOIN `tarif` ON `tarif`.`id` = `iklan`.`tarifid`
                LEFT JOIN `layanan` ON `layanan`.`id` = `tarif`.`layananid` WHERE jadwalsiaran.tanggal >= '$dari' AND jadwalsiaran.tanggal <= '$sampai'")->getResultArray();
            $newArray = [];
            while (strtotime($dari) <= strtotime($sampai)) {
                foreach ($datawaktu as $key => $waktu) {
                    $new_array = [];
                    foreach ($dataLayanan as $key => $itemLayanan) {
                        foreach ($siaran as $key => $value) {
                            $time = strtotime($dari);
                            $tanggal = date('Y-m-d', $time);
                            if ($value['tanggal'] == $tanggal && $value['waktu'] == $waktu && $value['layananid'] == $itemLayanan['id']) {
                                array_push($new_array, $value);
                            }
                        }
                    }
                    if (count($new_array) < 5) {
                        $item = [
                            'tanggal' => $dari,
                            'waktu' => $waktu,
                        ];
                        array_push($newArray, $item);
                    }
                }

                // echo "$dari<br/>";
                $dari = date("Y-m-d", strtotime("+1 day", strtotime($dari)));
            }
            return $newArray;
        } catch (\Throwable $th) {
            return $this->respond(['message' => $th->getMessage()]);
        }
    }
}
