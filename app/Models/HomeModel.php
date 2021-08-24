<?php namespace App\Models;

use CodeIgniter\Model;

class HomeModel extends Model
{
    protected $db;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
    }

    public function getData()
    {
        $month = date('n');
        return $this->db->query("SELECT
        (SELECT COUNT(*) FROM iklan) as totaliklan,
        (SELECT COUNT(*) FROM iklan WHERE month(tanggal)='$month') as monthiklan,
        (SELECT sum(nominal) FROM pembayaran) as totalpendapatan,
        (SELECT sum(nominal) FROM pembayaran LEFT JOIN iklan ON pembayaran.iklanid = iklan.id WHERE month(iklan.tanggal)='$month') as monthpendapatan")->getRowArray();
    }
}