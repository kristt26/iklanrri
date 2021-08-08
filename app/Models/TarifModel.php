<?php namespace App\Models;
  
use CodeIgniter\Model;
  
class TarifModel extends Model{
    protected $table = 'tarif';
    protected $allowedFields = [
        'id', 'kategori', 'jenis', 'uraian', 'satuan', 'tarif', 'layananid'
    ];
}