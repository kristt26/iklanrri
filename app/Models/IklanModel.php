<?php namespace App\Models;
  
use CodeIgniter\Model;
  
class IklanModel extends Model{
    protected $table = 'iklan';
    protected $allowedFields = ['id', 'layananid', 'topik', 'waktu', 'tanggalmulai', 'tanggalselesai', 'jeniskontent', 'kontent', 'tarifid', 'userid','status', 'tanggal', 'kategori', 'jenis', 'uraian', 'satuan', 'tarif'];

    public function readData()
    {
        # code...
    }
}