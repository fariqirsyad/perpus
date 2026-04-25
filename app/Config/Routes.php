<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// --- KONFIGURASI FILTER (HAK AKSES) ---
// Filter 'auth' memastikan user sudah login sebelum bisa akses
$authFilter = ['filter' => 'auth'];

// Filter 'role' membatasi halaman berdasarkan jabatan user di database
$admin     = ['filter' => 'role:admin'];
$anggota   = ['filter' => 'role:anggota'];
$intRole   = ['filter' => 'role:admin']; 
$allRole   = ['filter' => 'role:admin, anggota']; // Bisa diakses semua yang sudah login

// --- GROUP AUTHENTICATION (MASUK & KELUAR) ---
$routes->get('/login', 'Auth::login');             // Menampilkan halaman login
$routes->post('/proses-login', 'Auth::prosesLogin'); // Memproses data login (POST)
$routes->get('/logout', 'Auth::logout');           // Menghapus session dan keluar

// --- HALAMAN UTAMA / DASHBOARD ---
$routes->get('/', 'Home::index', $authFilter);          // Halaman awal setelah login
$routes->get('/dashboard', 'Home::index', $authFilter); // Alias halaman dashboard

// --- MANAJEMEN USERS (PENGGUNA) ---
$routes->get('/users/create', 'Users::create'); // Form pendaftaran user baru (biasanya buat registrasi)
$routes->post('/users/store', 'Users::store');   // Proses simpan data user baru ke DB

$routes->get('/users', 'Users::index', $intRole); // Daftar user (Hanya Admin yang bisa lihat)
$routes->get('/users/edit/(:num)', 'Users::edit/$1', $allRole); // Form edit user berdasarkan ID
$routes->post('/users/update/(:num)', 'Users::update/$1', $allRole); // Proses update data user
$routes->get('/users/delete/(:num)', 'Users::delete/$1', $allRole); // Proses hapus user

$routes->get('users/detail/(:num)', 'Users::detail/$1', $allRole); // Lihat profil lengkap user
$routes->get('users/print', 'Users::print', $allRole);           // Cetak laporan data user
$routes->get('users/wa/(:num)', 'Users::wa/$1', $allRole);      // Fitur kirim pesan data user ke WhatsApp

// --- MANAJEMEN PEMINJAMAN ---
$routes->get('peminjaman', 'Peminjaman::index'); // Daftar transaksi peminjaman
$routes->post('peminjaman/simpan', 'Peminjaman::tambah'); // Simpan transaksi (oleh admin)
$routes->get('peminjaman/kembalikan/(:num)', 'Peminjaman::kembalikan/$1'); // Proses pengembalian buku
$routes->get('peminjaman/hapus/(:num)', 'Peminjaman::hapus/$1'); // Hapus riwayat transaksi

// --- FITUR MANDIRI ANGGOTA ---
$routes->get('peminjaman/katalog', 'Peminjaman::katalog'); // Lihat daftar buku yang bisa dipinjam
$routes->post('peminjaman/pinjam_mandiri', 'Peminjaman::pinjam_mandiri'); // Aksi klik tombol pinjam

// --- ALUR PENGEMBALIAN & KONFIRMASI ---
$routes->get('peminjaman/ajukan_kembali/(:num)', 'Peminjaman::ajukan_kembali/$1'); // Anggota lapor balik buku
$routes->get('peminjaman/konfirmasi_kembali/(:num)', 'Peminjaman::konfirmasi_kembali/$1'); // Admin konfirmasi buku diterima

// --- MANAJEMEN DATA BUKU (STOK & JUDUL) ---
$routes->get('/buku', 'Buku::index');             // Daftar koleksi buku
$routes->post('/buku/simpan', 'Buku::simpan');    // Tambah buku baru
$routes->get('/buku/hapus/(:num)', 'Buku::hapus/$1'); // Hapus buku
$routes->post('buku/update/(:num)', 'Buku::update/$1'); // Update data buku

// --- BACKUP & RESTORE DATABASE ---
$routes->get('/backup', 'Backup::index');   // Fitur download database (.sql)

$routes->get('/restore', 'Restore::index');           // Halaman login khusus restore
$routes->post('/restore/auth', 'Restore::auth');     // Verifikasi password restore
$routes->get('/restore/form', 'Restore::form');       // Form upload file .sql
$routes->post('/restore/process', 'Restore::process'); // Eksekusi pengembalian database

// --- FITUR DENDA & PEMBAYARAN ---
// Mengirim bukti transfer denda (POST)
$routes->post('peminjaman/bayar_dan_ajukan/(:num)', 'Peminjaman::bayar_dan_ajukan/$1');
// Menampilkan halaman/proses bayar dan ajukan
$routes->get('peminjaman/bayar_dan_ajukan/(:num)', 'Peminjaman::bayar_dan_ajukan/$1');