<?php

namespace App\Controllers;

// Home Controller ini extend ke BaseController untuk dapet akses library standar
class Home extends BaseController
{
    // Method index: Halaman utama (Dashboard) saat pertama kali login
    public function index()
    {
        // Membuka koneksi ke database secara manual menggunakan Query Builder
        $db    = \Config\Database::connect();
        // Mengambil tanggal hari ini untuk perbandingan jatuh tempo
        $today = date('Y-m-d');
        
        // 1. Menghitung Total Koleksi
        // Menghitung seluruh jumlah baris (row) yang ada di tabel 'buku'
        $total_koleksi = $db->table('buku')->countAllResults();

        // 2. Menghitung Buku yang Sedang Dipinjam
        // Mencari data di tabel 'peminjaman' yang kolom statusnya masih 'dipinjam'
        $total_pinjam = $db->table('peminjaman')
                            ->where('status', 'dipinjam') 
                            ->countAllResults();

        // 3. Menghitung Buku yang Terlambat Dikembalikan
        // Logic: Statusnya masih 'dipinjam' TAPI tanggal kembalinya sudah lewat dari hari ini
        $jumlah_terlambat = $db->table('peminjaman')
                            ->where('tgl_kembali <', $today)
                            ->where('status', 'dipinjam')
                            ->countAllResults();

        // 4. Mengambil Data Detail Jatuh Tempo (Deadline)
        // Mengambil data lengkap peminjaman hari ini atau yang sudah lewat untuk ditampilkan di tabel/list
        $deadline_hari_ini = $db->table('peminjaman')
            ->select('peminjaman.*, buku.judul, users.username') // Pilih kolom yang mau ditampilkan
            ->join('buku', 'buku.id_buku = peminjaman.id_buku') // Join ke tabel buku untuk ambil Judul
            ->join('users', 'users.id = peminjaman.id_user')    // Join ke tabel users untuk ambil Username
            ->where('peminjaman.status', 'dipinjam') 
            ->where('peminjaman.tgl_kembali <=', $today)        // Filter: deadline hari ini atau sebelumnya
            ->get()->getResultArray();

        // 5. Mengambil 5 Aktivitas Transaksi Terbaru
        // Digunakan untuk bagian "Recent Activities" di Dashboard
        $recent_transactions = $db->table('peminjaman')
            ->select('peminjaman.*, buku.judul, users.nama') 
            ->join('buku', 'buku.id_buku = peminjaman.id_buku')
            ->join('users', 'users.id = peminjaman.id_user')
            ->orderBy('peminjaman.id_pinjam', 'DESC') // Urutkan dari yang paling baru masuk (ID terbesar)
            ->limit(5)                               // Batasi cuma 5 data saja
            ->get()->getResultArray();

        // Membungkus semua hasil query ke dalam satu array $data
        $data = [
            'total_buku'          => $total_koleksi,
            'total_pinjam'        => $total_pinjam,
            'jumlah_terlambat'    => $jumlah_terlambat, // Data untuk Card "Terlambat"
            'deadline_hari_ini'   => $deadline_hari_ini,
            'recent_transactions' => $recent_transactions 
        ];

        // Mengirim data ke view 'layouts/dashboard'
        return view('layouts/dashboard', $data); 
    }
}