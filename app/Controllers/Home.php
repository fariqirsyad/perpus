<?php

namespace App\Controllers;

class Home extends BaseController
{
 public function index()
{
    $db = \Config\Database::connect();
    
    // 1. Total Koleksi (Jumlah baris buku)
    $total_koleksi = $db->table('buku')->countAllResults();

    // 2. Sedang Dipinjam (SESUAIKAN DENGAN ENUM DATABASE)
    // Di database kamu tulisannya 'dipinjam', maka di sini harus sama
    $total_pinjam = $db->table('peminjaman')
                       ->where('status', 'dipinjam') 
                       ->countAllResults();

    // 3. Data Deadline (Jatuh Tempo)
    $deadline_hari_ini = $db->table('peminjaman')
        ->select('peminjaman.*, buku.judul, users.username')
        ->join('buku', 'buku.id_buku = peminjaman.id_buku')
        ->join('users', 'users.id = peminjaman.id_user')
        ->where('peminjaman.status', 'dipinjam') 
        ->where('peminjaman.tgl_kembali <=', date('Y-m-d')) 
        ->get()->getResultArray();

    $data = [
        'total_buku'        => $total_koleksi,
        'total_pinjam'      => $total_pinjam,
        'deadline_hari_ini' => $deadline_hari_ini
    ];

    return view('layouts/dashboard', $data); 
}
        
       
    


    
}