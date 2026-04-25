<?php

namespace App\Models;

// Mengimport core model dari CodeIgniter 4
use CodeIgniter\Model;

class BukuModel extends Model
{
    // Menentukan nama tabel yang akan dikelola oleh model ini
    protected $table = 'buku';

    // Menentukan kolom mana yang menjadi kunci utama (Primary Key)
    protected $primaryKey = 'id_buku';

    // White-list kolom: Daftar kolom yang diizinkan untuk diisi atau diubah (insert/update).
    // Ini adalah fitur keamanan CI4 agar tidak ada data liar yang masuk ke database.
    protected $allowedFields = [
        'judul',           // Judul buku
        'penulis',         // Nama pengarang
        'stok',            // Jumlah buku yang tersedia
        'denda_per_hari',  // Tarif denda jika telat mengembalikan buku ini
        'cover',           // Nama file gambar sampul buku
        'isbn',            // Nomor identitas buku internasional
        'kategori',        // Jenis buku (Misal: Novel, Sains, Religi)
        'penerbit',        // Perusahaan yang menerbitkan
        'tahun_terbit',    // Tahun buku rilis
        'deskripsi'        // Ringkasan atau sinopsis buku
    ];
}