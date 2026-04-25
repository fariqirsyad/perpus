<?php namespace App\Models;

// Mengambil core class Model dari CodeIgniter 4
use CodeIgniter\Model;

class PeminjamanModel extends Model {
    // Menentukan nama tabel utama yang dikelola model ini
    protected $table = 'peminjaman';
    
    // Menentukan kolom kunci utama
    protected $primaryKey = 'id_pinjam';
    
    // Daftar kolom yang boleh dimanipulasi (Insert/Update)
    protected $allowedFields = ['id_user', 'id_buku', 'tgl_pinjam', 'tgl_kembali', 'tgl_dikembalikan', 'denda', 'status'];

    /**
     * Method Custom: searchPeminjaman
     * Gunanya untuk mengambil data peminjaman sekaligus menarik nama user dan judul buku
     */
    public function searchPeminjaman($keyword = null) {
        // Menggabungkan tabel peminjaman dengan tabel users dan buku
        $builder = $this->select('peminjaman.*, users.nama, buku.judul')
            ->join('users', 'users.id = peminjaman.id_user')
            ->join('buku', 'buku.id_buku = peminjaman.id_buku');
        
        // Jika ada kata kunci pencarian dari user
        if ($keyword) {
            // groupStart & groupEnd digunakan agar logika LIKE tidak mengganggu join
            $builder->groupStart() 
                    ->like('users.nama', $keyword) // Cari berdasarkan nama peminjam
                    ->orLike('buku.judul', $keyword) // Atau berdasarkan judul buku
                    ->groupEnd();
        }
        
        // Mengembalikan semua hasil pencarian dalam bentuk array
        return $builder->findAll();
    }

    /**
     * Method Custom: konfirmasi_kembali
     * Menangani proses update status saat buku dikembalikan
     */
    public function konfirmasi_kembali($id) {
        // Inisialisasi model di dalam method (bisa juga pakai $this)
        $peminjamanModel = new \App\Models\PeminjamanModel(); 
        
        // Mengambil data peminjaman yang lama berdasarkan ID
        $dataLama = $peminjamanModel->find($id);

        // ... (Logika hitung denda biasanya ada di sini) ...

        // Melakukan update data status, tanggal kembali, dan denda
        $peminjamanModel->update($id, [
            'status'           => 'kembali',
            'tgl_dikembalikan' => date('Y-m-d'),
            'denda'            => $denda, // Variabel $denda didapat dari hasil perhitungan
            'status_bayar'     => 'lunas'
        ]);
    }

}