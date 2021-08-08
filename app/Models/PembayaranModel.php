<?php namespace App\Models;
  
use CodeIgniter\Model;
  
class PembayaranModel extends Model{
    protected $table = 'pembayaran';
    protected $allowedFields = ['id', 'orderid', 'iklanid', 'nominal', 'status'];
    protected $db;
    public function updatePembayaran($data, $id)
    {
        $this->db = \Config\Database::connect();
        return $this->db->table('pembayaran')->where('orderid', $id)->update($data);
    }
}