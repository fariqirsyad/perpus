<?php

namespace App\Controllers;

class Home extends BaseController
{
    public function index()
{
    $db = \Config\Database::connect();
    
    $data = [
        'total_buku'    => $db->table('buku')->countAllResults(),
        'total_pinjam'  => $db->table('peminjaman')->where('status', 'Sedang Dipinjam')->countAllResults(),
        // INI YANG PENTING: Ambil data peminjaman yang deadline-nya hari ini atau lewat
        'deadline_hari_ini' => $db->table('peminjaman')
            ->join('buku', 'buku.id_buku = peminjaman.id_buku')
            ->join('users', 'users.id = peminjaman.id_user')
            ->where('peminjaman.status', 'Sedang Dipinjam')
            ->where('peminjaman.tgl_kembali <=', date('Y-m-d')) 
            ->get()->getResultArray()
    ];

    return view('layouts/dashboard', $data); // Pastikan $data dikirim ke view

        
        // Contoh logic di Controller
$db = \Config\Database::connect();
$data['deadline_hari_ini'] = $db->table('peminjaman')
    ->join('buku', 'buku.id_buku = peminjaman.id_buku')
    ->join('users', 'users.id = peminjaman.id_user')
    ->where('peminjaman.status', 'Sedang Dipinjam')
    ->where('peminjaman.batas_kembali <=', date('Y-m-d')) // Hari ini atau sudah lewat
    ->get()->getResultArray();

    }


    
}