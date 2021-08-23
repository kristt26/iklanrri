<?php

namespace App\Models;

use CodeIgniter\Model;

class IklanModel extends Model
{
    protected $table = 'iklan';
    protected $allowedFields = ['id', 'topik', 'waktu', 'tanggalmulai', 'tanggalselesai', 'jeniskontent', 'kontent', 'tarifid', 'userid', 'status', 'tanggal', 'kategori', 'jenis', 'uraian', 'satuan', 'tarif'];
    protected $db;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
    }

    public function readData()
    {
        $iduser = session()->get('id');
        $result = $this->db->query("SELECT
            `iklan`.*,
            `tarif`.`kategori`,
            `tarif`.`jenis`,
            `tarif`.`uraian`,
            `tarif`.`satuan`,
            `tarif`.`tarif`,
            `tarif`.`layananid`,
            `pembayaran`.`orderid`,
            `pembayaran`.`nominal`,
            `pembayaran`.`status` AS `status1`,
            `layanan`.`layanan`
        FROM
            `iklan`
            LEFT JOIN `pembayaran` ON `iklan`.`id` = `pembayaran`.`iklanid`
            LEFT JOIN `tarif` ON `tarif`.`id` = `iklan`.`tarifid`
            LEFT JOIN `layanan` ON `layanan`.`id` = `tarif`.`layananid` WHERE iklan.userid='$iduser'")->getResultArray();
        foreach ($result as $key => $value) {
            $result[$key]['waktu'] = unserialize($result[$key]['waktu']);
        }
        return $result;
    }

    public function order()
    {
        $result = $this->db->query("SELECT
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
            `tarif`.`uraian`,
            `tarif`.`satuan`,
            `tarif`.`tarif`,
            `tarif`.`layananid`,
            `user`.`username`,
            `user`.`email`,
            `user`.`alamat`,
            `user`.`kontak`,
            `user`.`fullname`,
            `layanan`.`layanan`
        FROM
            `iklan`
            LEFT JOIN `user` ON `iklan`.`userid` = `user`.`id`
            LEFT JOIN `tarif` ON `tarif`.`id` = `iklan`.`tarifid`
            LEFT JOIN `pembayaran` ON `iklan`.`id` = `pembayaran`.`iklanid`
            LEFT JOIN `layanan` ON `tarif`.`layananid` = `layanan`.`id`
        WHERE
            pembayaran.status = 'Success'")->getResultArray();

        foreach ($result as $key => $value) {
            $result[$key]['waktu'] = unserialize($result[$key]['waktu']);
            $iklanid = $value['iklanid'];
            $result[$key]['jadwal'] = $this->db->query("SELECT * FROM jadwalsiaran WHERE iklanid = '$iklanid'")->getResultArray();
        }
        return $result;
    }

    public function newIklan($id)
    {
        return $this->db->query("SELECT
            *
        FROM
            `iklan`
            LEFT JOIN `pembayaran` ON `pembayaran`.`iklanid` = `iklan`.`id`  WHERE iklan.id = '$id'")->getRowArray();
    }
}
