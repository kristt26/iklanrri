<?php namespace App\Models;
  
use CodeIgniter\Model;
  
class IklanModel extends Model{
    protected $table = 'iklan';
    protected $allowedFields = ['id', 'topik', 'waktu', 'tanggalmulai', 'tanggalselesai', 'jeniskontent', 'kontent', 'tarifid', 'userid','status', 'tanggal', 'kategori', 'jenis', 'uraian', 'satuan', 'tarif'];
    protected $db;

    public function readData()
    {
        $this->db = \Config\Database::connect();
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
}