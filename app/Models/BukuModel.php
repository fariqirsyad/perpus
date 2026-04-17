<?php

namespace App\Models;

use CodeIgniter\Model;

class BukuModel extends Model
{
    protected $table = 'buku';
    protected $primaryKey = 'id_buku';
    // Hanya kolom ini yang ada di database kamu sekarang
    protected $allowedFields = ['judul', 'penulis', 'stok', 'denda_per_hari'];
}