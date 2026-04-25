<?php

namespace App\Models;

// Mengimport base class Model dari CodeIgniter 4
use CodeIgniter\Model;

class UsersModel extends Model
{
    // Menentukan nama tabel yang dikelola oleh model ini di database
    protected $table = 'users';

    // Menentukan kolom yang bertindak sebagai Primary Key (ID Unik)
    protected $primaryKey = 'id';

    // White-list: Daftar kolom yang diizinkan untuk diisi atau dimanipulasi.
    // Ini menjaga agar tidak ada injeksi data ke kolom yang tidak seharusnya.
    protected $allowedFields = [
        'nama',     // Nama lengkap user
        'email',    // Alamat email user
        'username', // Username untuk login
        'password', // Password (yang sudah di-hash)
        'role',     // Jabatan (admin, petugas, atau anggota)
        'foto',     // Nama file foto profil
        'status'    // Status akun (misal: aktif/tidak aktif)
    ];

    /**
     * Method Custom: getUsersByUsername
     * Fungsi ini sangat krusial, biasanya digunakan pada Controller Auth 
     * untuk mengecek apakah username yang diinput saat login ada di database atau tidak.
     */
    public function getUsersByUsername($username)
    {
        // Mencari data user berdasarkan kolom 'username' dan mengambil satu hasil pertama
        return $this->where('username', $username)->first();
    }
}