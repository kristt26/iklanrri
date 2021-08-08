<?php namespace App\Models;
  
use CodeIgniter\Model;
  
class JadwalModel extends Model{
    protected $table = 'jadwalsiaran';
    protected $allowedFields = ['id', 'iklanid', 'tanggal', 'waktu'];
}