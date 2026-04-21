
<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// Variabel Filter
$authFilter = ['filter' => 'auth'];

// Variabel Role
$admin     = ['filter' => 'role:admin'];
$petugas     = ['filter' => 'role:petugas'];
$anggota     = ['filter' => 'role:anggota'];
$intRole   = ['filter' => 'role:admin, petugas'];
$allRole   = ['filter' => 'role:admin, petugas, anggota'];

// Login
$routes->get('/login', 'Auth::login');
$routes->post('/proses-login', 'Auth::prosesLogin');
$routes->get('/logout', 'Auth::logout');

// Halaman utama
$routes->get('/', 'Home::index', $authFilter);
$routes->get('/dashboard', 'Home::index', $authFilter);

$routes->get('/users/create', 'Users::create'); // form tambah user
$routes->post('/users/store', 'Users::store'); // aksi simpan user

$routes->get('/users', 'Users::index', $intRole); // menampilkan data user hanya untuk admin dan petugas
$routes->get('/users/edit/(:num)', 'Users::edit/$1', $allRole); // form edit user
$routes->post('/users/update/(:num)', 'Users::update/$1', $allRole); // aksi update user
$routes->get('/users/delete/(:num)', 'Users::delete/$1', $allRole); // aksi hapus user

$routes->get('users/detail/(:num)', 'Users::detail/$1', $allRole); // aksi detail user
$routes->get('users/print', 'Users::print', $allRole); // aksi print data user
$routes->get('users/wa/(:num)', 'Users::wa/$1', $allRole); // aksi kirim ke whatsapp

$routes->get('peminjaman', 'Peminjaman::index');
$routes->post('peminjaman/simpan', 'Peminjaman::tambah');
$routes->get('peminjaman/kembalikan/(:num)', 'Peminjaman::kembalikan/$1');
$routes->get('peminjaman/hapus/(:num)', 'Peminjaman::hapus/$1');

$routes->get('peminjaman/katalog', 'Peminjaman::katalog');
$routes->post('peminjaman/pinjam_mandiri', 'Peminjaman::pinjam_mandiri');

$routes->get('peminjaman/ajukan_kembali/(:num)', 'Peminjaman::ajukan_kembali/$1');
$routes->get('peminjaman/konfirmasi_kembali/(:num)', 'Peminjaman::konfirmasi_kembali/$1');

$routes->get('/buku', 'Buku::index');
$routes->post('/buku/simpan', 'Buku::simpan');
$routes->get('/buku/hapus/(:num)', 'Buku::hapus/$1');
$routes->post('buku/update/(:num)', 'Buku::update/$1');

$routes->get('/', 'Home::index');
$routes->get('dashboard', 'Home::index'); // Tambahkan ini juga biar aman

$routes->get('/backup', 'Backup::index');

$routes->get('/restore', 'Restore::index');
$routes->post('/restore/auth', 'Restore::auth');
$routes->get('/restore/form', 'Restore::form');
$routes->post('/restore/process', 'Restore::process');