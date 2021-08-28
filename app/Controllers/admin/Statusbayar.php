<?php

namespace App\Controllers\Admin;

use CodeIgniter\API\ResponseTrait;
use App\Controllers\BaseController;
use App\Models\IklanModel;

class Statusbayar extends BaseController
{
    use ResponseTrait;
    public $iklan;

    public function __construct()
    {
        $this->iklan = new IklanModel();
    }

    public function index()
    {
        $data['datamenu'] = ['menu' => "Status Bayar"];
        $data['sidebar'] = view('layout/sidebar');
        $data['header'] = view('layout/header');
        $data['content'] = view('admin/statusbayar');
        return view('layout/layout', $data);
    }

    public function read()
    {
        $result = $this->iklan->query("SELECT
            `iklan`.`topik`,
            `iklan`.`waktu`,
            `iklan`.`tanggalmulai`,
            `iklan`.`tanggalselesai`,
            `iklan`.`jeniskontent`,
            `iklan`.`kontent`,
            `iklan`.`tarifid`,
            `iklan`.`status`,
            `iklan`.`tanggal`,
            `iklan`.`userid`,
            `pembayaran`.`orderid`,
            `pembayaran`.`iklanid`,
            `pembayaran`.`nominal`,
            `pembayaran`.`status` AS `statusbayar`,
            `tarif`.`kategori`,
            `tarif`.`jenis`,
            `tarif`.`tarif`,
            `user`.`fullname`,
            `layanan`.`layanan`
        FROM
            `iklan`
            LEFT JOIN `pembayaran` ON `pembayaran`.`iklanid` = `iklan`.`id`
            LEFT JOIN `tarif` ON `iklan`.`tarifid` = `tarif`.`id`
            LEFT JOIN `user` ON `user`.`id` = `iklan`.`userid`
            LEFT JOIN `layanan` ON `tarif`.`layananid` = `layanan`.`id`
        WHERE year(iklan.tanggal)=year(CURRENT_DATE()) ORDER BY iklan.tanggal ASC")->getResultArray();
        return $this->respond($result);
    }
}
