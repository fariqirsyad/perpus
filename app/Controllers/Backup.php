<?php

namespace App\Controllers;

// Mengimport base controller dari CodeIgniter 4
use CodeIgniter\Controller;

class Backup extends Controller
{
    // Method utama untuk menjalankan proses backup database
    public function index()
    {
        // Proteksi keamanan: Hanya user dengan role 'admin' yang bisa akses
        // Jika bukan admin, tendang balik ke halaman dashboard
        if (session()->get('role') != 'admin') {
            return redirect()->to('/dashboard');
        }
        
        // Inisialisasi koneksi database untuk mengambil nama DB yang aktif
        $db      = \Config\Database::connect();
        $dbName  = $db->getDatabase(); // Mengambil nama database dari config

        // Mengambil kredensial database (username, password, host) dari file .env
        $user    = env('database.default.username');
        $pass    = env('database.default.password');
        $host    = env('database.default.hostname');

        // Menentukan lokasi penyimpanan file backup di folder writable/backup/
        // Nama file dibuat dinamis berdasarkan tanggal dan waktu saat ini
        $backupFile = WRITEPATH . 'backup/backup-' . date('Y-m-d_H-i-s') . '.sql';

        // Cek apakah folder 'backup' di dalam directory 'writable' sudah ada atau belum
        if (!is_dir(WRITEPATH . 'backup')) {
            // Jika belum ada, buat foldernya secara otomatis dengan permission 0777
            mkdir(WRITEPATH . 'backup', 0777, true);
        }

        // Path ke executable mysqldump (lokasi standar jika menggunakan XAMPP di Windows)
        $mysqldumpPath = 'C:\xampp\mysql\bin\mysqldump'; 

        // Menyusun perintah shell/command line untuk melakukan dump database ke file .sql
        // Format: mysqldump --user=root --password=pass --host=localhost nama_db > lokasi_file.sql
        $command = "{$mysqldumpPath} --user={$user} --password={$pass} --host={$host} {$dbName} > {$backupFile}";

        // Menjalankan perintah shell tersebut ke sistem operasi
        // $output akan menampung kode status hasil eksekusi
        system($command, $output);

        // Validasi: Cek apakah file backup-nya benar-benar tercipta dan isinya tidak kosong
        if (file_exists($backupFile) && filesize($backupFile) > 0) {
            // Jika berhasil, otomatis download file backup tersebut ke browser user
            return $this->response->download($backupFile, null);
        } else {
            // Jika gagal (file tidak ada atau 0 byte), tampilkan pesan error
            return "Backup gagal. Periksa konfigurasi database Anda atau perintah mysqldump.";
        }
    }
}