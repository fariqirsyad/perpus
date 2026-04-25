<?php

namespace App\Controllers;

// Mengimport model UsersModel agar bisa digunakan untuk query ke database
use App\Models\UsersModel;
// Mengimport base controller dari CodeIgniter 4
use CodeIgniter\Controller;

class Auth extends Controller
{
    // Method untuk menampilkan halaman login
    public function login()
    {
        // Mengembalikan view yang berada di folder views/auth/login.php
        return view('auth/login');
    }

    // Method untuk memproses data yang dikirim dari form login
    public function prosesLogin()
    {
        // Memanggil library session untuk menyimpan data login user
        $session = session();
        // Inisialisasi/membuat object baru dari UsersModel
        $usersModel = new UsersModel();
        
        // Mengambil data 'username' yang diinput user melalui method POST
        $username = $this->request->getPost('username');
        // Mengambil data 'password' yang diinput user melalui method POST
        $password = $this->request->getPost('password');

        // Mencari data user di database berdasarkan username menggunakan method dari model
        $users = $usersModel->getUsersByUsername($username);

        // Jika data user ditemukan di database
        if ($users) {
            // Mengecek apakah password yang diinput cocok dengan password yang sudah di-hash di database
            if (password_verify($password, $users['password'])) {
                // Jika cocok, simpan data user ke dalam session aplikasi
                $session->set([
                    'id'        => $users['id'],
                    'nama'      => $users['nama'],
                    'email'     => $users['email'],
                    'username'  => $users['username'],
                    'role'      => $users['role'],
                    'foto'      => $users['foto'],
                    'logged_in' => true // Penanda bahwa user sudah berhasil login
                ]);

                // Arahkan user ke halaman dashboard setelah berhasil login
                return redirect()->to('/dashboard');
            } else {
                // Jika password tidak cocok, buat pesan error sementara (flashdata)
                $session->setFlashdata('salahpw', 'Password salah');
                // Kembalikan user ke halaman login
                return redirect()->to('/login');
            }
        } else {
            // Jika username tidak ditemukan di database, buat pesan error sementara
            $session->setFlashdata('error', 'Nama tidak ditemukan');
            // Kembalikan user ke halaman login
            return redirect()->to('/login');
        }
    }

    // Method untuk menghancurkan session dan keluar dari aplikasi
    public function logout()
    {
        // Menghapus semua data session yang sedang berjalan
        session()->destroy();
        // Arahkan user kembali ke halaman login setelah logout
        return redirect()->to('/login');
    }
}