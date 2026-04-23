<?php

namespace App\Controllers;

class Home extends BaseController
{

    public function index()
{
    $db = \Config\Database::connect();
    $today = date('Y-m-d');
    
    // 1. Total Koleksi (Jumlah baris buku)
    $total_koleksi = $db->table('buku')->countAllResults();

    // 2. Sedang Dipinjam
    $total_pinjam = $db->table('peminjaman')
                        ->where('status', 'dipinjam') 
                        ->countAllResults();

    // 3. HITUNG BUKU TERLAMBAT (Untuk Card Status Baru)
    $jumlah_terlambat = $db->table('peminjaman')
                        ->where('tgl_kembali <', $today)
                        ->where('status', 'dipinjam')
                        ->countAllResults();

    // 4. Data Deadline (Jatuh Tempo) - TETAP DIJAGA
    $deadline_hari_ini = $db->table('peminjaman')
        ->select('peminjaman.*, buku.judul, users.username')
        ->join('buku', 'buku.id_buku = peminjaman.id_buku')
        ->join('users', 'users.id = peminjaman.id_user')
        ->where('peminjaman.status', 'dipinjam') 
        ->where('peminjaman.tgl_kembali <=', $today) 
        ->get()->getResultArray();

    // 5. Aktivitas Terbaru
    $recent_transactions = $db->table('peminjaman')
        ->select('peminjaman.*, buku.judul, users.nama') 
        ->join('buku', 'buku.id_buku = peminjaman.id_buku')
        ->join('users', 'users.id = peminjaman.id_user')
        ->orderBy('peminjaman.id_pinjam', 'DESC') 
        ->limit(5)
        ->get()->getResultArray();

    $data = [
        'total_buku'          => $total_koleksi,
        'total_pinjam'        => $total_pinjam,
        'jumlah_terlambat'    => $jumlah_terlambat, // Data baru buat card status
        'deadline_hari_ini'   => $deadline_hari_ini,
        'recent_transactions' => $recent_transactions 
    ];

    return view('layouts/dashboard', $data); 
}
        
       
    


    
}