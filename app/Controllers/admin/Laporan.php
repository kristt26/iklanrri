<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\LayananModel;
use CodeIgniter\API\ResponseTrait;

class Laporan extends BaseController
{
    use ResponseTrait;
    public $iklan;

    public function __construct()
    {
        $this->iklan = new LayananModel();
    }

    public function iklan()
    {
        $data['datamenu'] = ['menu' => "Layanan"];
        $data['sidebar'] = view('layout/sidebar');
        $data['header'] = view('layout/header');
        $data['content'] = view('admin/laporaniklan');
        return view('layout/layout', $data);
    }

    public function pendapatan()
    {
        $data['datamenu'] = ['menu' => "Layanan"];
        $data['sidebar'] = view('layout/sidebar');
        $data['header'] = view('layout/header');
        $data['content'] = view('admin/laporanpendapatan');
        return view('layout/layout', $data);
    }

    public function read()
    {
        $data = (array) $this->request->getJSON();
        $awal = $data['awal'];
        $akhir = $data['akhir'];
        $result = $this->iklan->query("SELECT
            `iklan`.`topik`,
            `iklan`.`tanggalmulai`,
            `iklan`.`tanggalselesai`,
            `iklan`.`jeniskontent`,
            `iklan`.`kontent`,
            `iklan`.`tarifid`,
            `iklan`.`status`,
            `iklan`.`tanggal`,
            `iklan`.`waktu`,
            `user`.`fullname`,
            `pembayaran`.`nominal`,
            `layanan`.`layanan`,
            `tarif`.`tarif`,
            `tarif`.`kategori`,
            `tarif`.`jenis`
        FROM
            `iklan`
            LEFT JOIN `pembayaran` ON `iklan`.`id` = `pembayaran`.`iklanid`
            LEFT JOIN `tarif` ON `iklan`.`tarifid` = `tarif`.`id`
            LEFT JOIN `layanan` ON `tarif`.`layananid` = `layanan`.`id`
            LEFT JOIN `user` ON `user`.`id` = `iklan`.`userid` WHERE iklan.tanggal>='$awal' AND iklan.tanggal <='$akhir'")->getResultArray();
            foreach ($result as $key => $value) {
                $result[$key]['waktu'] = unserialize($value['waktu']);
            }
        return $this->respond($result);
    }
}
