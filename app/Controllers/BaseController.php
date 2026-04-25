<?php

namespace App\Controllers;

// Mengimport core class dari CodeIgniter untuk menangani Controller, Request, Response, dan Logging
use CodeIgniter\Controller;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

/**
 * BaseController menyediakan tempat yang nyaman untuk memuat komponen
 * dan menjalankan fungsi yang dibutuhkan oleh semua controller Anda.
 *
 * Semua controller baru yang lu bikin harus extend ke class ini, contoh:
 * class Home extends BaseController
 */
abstract class BaseController extends Controller
{
    /**
     * Properti untuk menyimpan instance dari objek atau library.
     * Di PHP 8.2 ke atas, kita wajib mendeklarasikan properti secara eksplisit
     * karena pembuatan properti dinamis sudah tidak diperbolehkan (deprecated).
     */
    
    // Contoh: protected $session; // Buka komen ini jika ingin menggunakan session di semua controller

    /**
     * Method initController dijalankan otomatis oleh sistem saat controller dipanggil.
     * Ini seperti 'constructor' khusus untuk controller di CodeIgniter 4.
     * * @return void
     */
    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        // Tempat untuk memuat helper yang lu ingin tersedia di SEMUA controller.
        // Letakkan di atas parent::initController() agar helper siap sebelum sistem berjalan.
        // Contoh: $this->helpers = ['form', 'url', 'auth_helper'];

        // Menjalankan inisialisasi controller dari class induk (CodeIgniter\Controller).
        // Jangan hapus baris ini karena ini inti dari sistem controllernya.
        parent::initController($request, $response, $logger);

        // Tempat untuk preload model, library, atau service secara global.
        // Contoh: $this->session = service('session'); 
        // Jadi lu nggak perlu panggil session() lagi di tiap file controller.
    }
}