<?php

namespace App\Controllers;

class Home extends BaseController
{
    public function index()
    {
        $db = \Config\Database::connect();

        // 1. Ambil semua data yang dibutuhkan
        $data['total_buku']     = $db->table('buku')->countAll();
        
        // Hitung yang statusnya BENAR-BENAR 'dipinjam'
        $data['total_pinjam']   = $db->table('peminjaman')
                                     ->where('status', 'dipinjam')
                                     ->countAllResults();
        
        // Hitung yang statusnya 'diajukan' (menunggu konfirmasi)
        $data['total_diajukan'] = $db->table('peminjaman')
                                     ->where('status', 'diajukan')
                                     ->countAllResults();

        // Hitung total anggota
        $data['total_member']   = $db->table('users')
                                     ->where('role', 'anggota')
                                     ->countAllResults();

        // 2. Kirim SEMUA data tersebut ke view dalam satu array $data
        return view('layouts/dashboard', $data); 
    }
}