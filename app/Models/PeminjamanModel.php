<?php namespace App\Models;
use CodeIgniter\Model;

class PeminjamanModel extends Model {
    protected $table = 'peminjaman';
    protected $primaryKey = 'id_pinjam';
    protected $allowedFields = ['id_user', 'id_buku', 'tgl_pinjam', 'tgl_kembali', 'tgl_dikembalikan', 'denda', 'status'];

    // Kita gunakan nama searchPeminjaman agar sesuai dengan Controller
    public function searchPeminjaman($keyword = null) {
        $builder = $this->select('peminjaman.*, users.nama, buku.judul')
            ->join('users', 'users.id = peminjaman.id_user')
            ->join('buku', 'buku.id_buku = peminjaman.id_buku');
        
        if ($keyword) {
            $builder->groupStart() // Mulai grup pencarian agar join tidak berantakan
                    ->like('users.nama', $keyword)
                    ->orLike('buku.judul', $keyword)
                    ->groupEnd();
        }
        
        return $builder->findAll();
    }

    public function konfirmasi_kembali($id) {
    $peminjamanModel = new \App\Models\PeminjamanModel(); // Sesuaikan nama model lu
    $dataLama = $peminjamanModel->find($id);

    // ... hitung denda sama kayak di atas ...

    $peminjamanModel->update($id, [
        'status'           => 'kembali',
        'tgl_dikembalikan' => date('Y-m-d'),
        'denda'            => $denda,
        'status_bayar'     => 'lunas'
    ]);
}

}