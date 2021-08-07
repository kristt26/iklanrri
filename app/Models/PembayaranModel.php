<?php namespace App\Models;
  
use CodeIgniter\Model;
  
class PembayaranModel extends Model{
    protected $table = 'pembayaran';
    protected $allowedFields = ['id', 'orderid', 'iklanid', 'nominal', 'status'];
}