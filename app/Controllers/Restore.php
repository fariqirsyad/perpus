<?php

namespace App\Controllers;

use CodeIgniter\Controller;

class Restore extends Controller
{
    // Password khusus untuk akses halaman restore (Keamanan ekstra karena fitur ini bahaya)
    private $restorePassword = 'admin123'; // GANTI PASSWORD INI DI PRODUCTION!

    // Method untuk menampilkan halaman login restore
    public function index()
    {
        return view('restore/restore_login');
    }

    // Method untuk memverifikasi password restore
    public function auth()
    {
        // Mengambil password dari input POST
        $password = $this->request->getPost('password');

        // Cek apakah password cocok dengan properti $restorePassword
        if ($password === $this->restorePassword) {
            // Jika cocok, buat session khusus akses restore
            session()->set('restore_access', true);
            return redirect()->to('/restore/form');
        }

        // Jika salah, balikkan dengan pesan error
        return redirect()->back()->with('error', 'Password salah!');
    }

    // Method untuk menampilkan form upload file SQL
    public function form()
    {
        // Proteksi: Jika tidak punya session restore_access, tendang ke login restore
        if (!session()->get('restore_access')) {
            return redirect()->to('/restore');
        }

        return view('restore/restore');
    }

    // Method inti untuk memproses file SQL ke database
    public function process()
    {
        // Cek kembali session akses
        if (!session()->get('restore_access')) {
            return redirect()->to('/restore');
        }

        // Mengambil file yang diupload
        $file = $this->request->getFile('file_sql');

        // Validasi: Pastikan file ada dan tidak korup
        if (!$file || !$file->isValid()) {
            return redirect()->back()->with('error', 'File tidak valid');
        }

        // Cek ekstensi file
        $ext = strtolower($file->getClientExtension());
        if ($ext !== 'sql') {
            return redirect()->back()->with('error', 'File harus berformat .sql');
        }

        // --- TAMBAHAN : OTOMATIS MEMBUAT DATABASE JIKA BELUM ADA ---
        $dbName = 'perpus'; // Nama database target

        // Membuka koneksi MySQL menggunakan native PHP (mysqli)
        // Parameter: localhost, username, password, database (kosong dulu)
        $conn = new \mysqli('localhost', 'root', '', '');

        if ($conn->connect_error) {
            die('Koneksi gagal: ' . $conn->connect_error);
        }

        // Jalankan perintah SQL untuk membuat database jika belum ada di server
        $conn->query("CREATE DATABASE IF NOT EXISTS $dbName");

        // Memilih database yang baru dibuat/sudah ada tersebut
        $conn->select_db($dbName);
        // -----------------------------------------------------------

        // Koneksi menggunakan library database CodeIgniter
        $db = \Config\Database::connect();

        try {
            // Membaca isi file SQL per baris ke dalam array
            $sqlLines = file($file->getTempName());
            $query = '';

            // Looping untuk menjalankan setiap baris perintah SQL
            foreach ($sqlLines as $line) {
                $line = trim($line); // Bersihkan spasi di awal/akhir baris

                // Abaikan jika baris kosong atau baris komentar SQL (diawali '--')
                if ($line == '' || substr($line, 0, 2) == '--') {
                    continue;
                }

                // Gabungkan baris-baris menjadi satu string query utuh
                $query .= $line;

                // Jika di ujung baris ada titik koma (;) berarti satu perintah SQL selesai
                if (substr($line, -1) == ';') {
                    // Eksekusi query tersebut ke database
                    $db->query($query);
                    // Kosongkan variabel query untuk menampung perintah selanjutnya
                    $query = '';
                }
            }

            // Hapus session akses restore setelah selesai demi keamanan
            session()->remove('restore_access');

            // Redirect ke halaman utama dengan pesan sukses
            return redirect()->to('/')->with('success', 'Restore berhasil!');
        } catch (\Exception $e) {
            // Jika ada error (misal query salah), tampilkan pesan errornya
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}