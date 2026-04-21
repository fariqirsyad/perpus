<?php

namespace App\Controllers;

class Home extends BaseController
{

    public function index()
{
    $db = \Config\Database::connect();
    
    // 1. Total Koleksi (Jumlah baris buku)
    $total_koleksi = $db->table('buku')->countAllResults();

    // 2. Sedang Dipinjam
    $total_pinjam = $db->table('peminjaman')
                        ->where('status', 'dipinjam') 
                        ->countAllResults();

    // 3. Data Deadline (Jatuh Tempo) - TETAP DIJAGA
    $deadline_hari_ini = $db->table('peminjaman')
        ->select('peminjaman.*, buku.judul, users.username')
        ->join('buku', 'buku.id_buku = peminjaman.id_buku')
        ->join('users', 'users.id = peminjaman.id_user')
        ->where('peminjaman.status', 'dipinjam') 
        ->where('peminjaman.tgl_kembali <=', date('Y-m-d')) 
        ->get()->getResultArray();

    // 4. Aktivitas Terbaru (TAMBAHAN BARU)
    // Mengambil 5 transaksi terakhir baik yang dipinjam maupun kembali
    $recent_transactions = $db->table('peminjaman')
        ->select('peminjaman.*, buku.judul, users.nama') // Sesuaikan 'nama_lengkap' jika kolom di tabel users beda
        ->join('buku', 'buku.id_buku = peminjaman.id_buku')
        ->join('users', 'users.id = peminjaman.id_user')
        ->orderBy('peminjaman.id_pinjam', 'DESC') // Urutkan dari yang paling baru
        ->limit(5)
        ->get()->getResultArray();

    $data = [
        'total_buku'          => $total_koleksi,
        'total_pinjam'        => $total_pinjam,
        'deadline_hari_ini'   => $deadline_hari_ini,
        'recent_transactions' => $recent_transactions // Kirim ke view
    ];

    return view('layouts/dashboard', $data); 
}
        
       
    


    
}