<?php namespace App\Models;
use CodeIgniter\Model;

class PeminjamanModel extends Model {
    protected $table = 'peminjaman';
    protected $primaryKey = 'id_pinjam';
    protected $allowedFields = ['id_user', 'id_buku', 'tgl_pinjam', 'tgl_kembali', 'tgl_dikembalikan', 'denda', 'status'];

    public function getPeminjaman($keyword = null) {
        $builder = $this->table('peminjaman')
            ->select('peminjaman.*, users.nama, buku.judul')
            ->join('users', 'users.id = peminjaman.id_user')
            ->join('buku', 'buku.id_buku = peminjaman.id_buku');
        
        if ($keyword) {
            $builder->like('users.nama', $keyword);
        }
        return $builder->findAll();
    }
}