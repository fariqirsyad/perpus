<?php

// Namespace untuk menentukan lokasi file Controller
namespace App\Controllers;

// Mengimport model-model yang diperlukan untuk olah data
use App\Models\PeminjamanModel;
use App\Models\BukuModel; 

class Peminjaman extends BaseController
{
    // --- FITUR UMUM (Dapat diakses Admin, Petugas, maupun Anggota) ---

    public function index()
{
    // Inisialisasi model peminjaman
    $model = new PeminjamanModel();
    
    // Mengambil filter 'cari' dan 'status' dari parameter URL (GET)
    $cari   = $this->request->getGet('cari');
    $status = $this->request->getGet('status');

    // Memulai query builder dengan join ke tabel users dan buku
    $builder = $model->select('peminjaman.*, users.nama, buku.judul, buku.denda_per_hari')
                     ->join('users', 'users.id = peminjaman.id_user')
                     ->join('buku', 'buku.id_buku = peminjaman.id_buku');

    // Jika ada keyword pencarian
    if ($cari) {
        $builder->groupStart()
                ->like('users.nama', $cari)
                ->orLike('buku.judul', $cari)
                ->groupEnd();
    }

    // Jika user memfilter berdasarkan status tertentu
    if ($status) {
        $builder->where('peminjaman.status', $status);
    }

    // Keamanan: Jika yang login 'anggota', batasi hanya milik dia sendiri
    if (session('role') == 'anggota') {
        $builder->where('peminjaman.id_user', session('id'));
    }

    // --- PERUBAHAN DISINI: Gunakan paginate() untuk menggantikan findAll() ---
    // Kita urutkan dari ID terbaru, lalu ambil 10 data per halaman
    $data['transaksi'] = $model->orderBy('peminjaman.id_pinjam', 'DESC')->paginate(10, 'transaksi');
    
    // Kirim objek pager ke view agar bisa nampilin link halaman
    $data['pager'] = $model->pager;
    
    // Kirim balik data pencarian agar form filter tetap terisi
    $data['cari'] = $cari;
    $data['status_saat_ini'] = $status;

    // Tampilkan halaman daftar peminjaman
    return view('peminjaman/index', $data);
}

    // --- FITUR KHUSUS ANGGOTA ---

    // Menampilkan katalog buku yang tersedia untuk dipinjam oleh anggota
    public function katalog()
    {
        // Koneksi database manual
        $db = \Config\Database::connect();
        // Mengambil input kategori dari dropdown filter
        $kategori_dipilih = $this->request->getVar('filter_kategori'); 
        
        $builder = $db->table('buku');

        // Filter buku berdasarkan kategori jika user memilih kategori tertentu
        if ($kategori_dipilih && $kategori_dipilih != 'Semua') {
            $builder->where('kategori', $kategori_dipilih);
        }

        // Ambil hasil filter buku
        $data['buku'] = $builder->get()->getResultArray();
        // Default kategori ke 'Semua' jika tidak ada yang dipilih
        $data['kategori_aktif'] = $kategori_dipilih ?? 'Semua';

        return view('peminjaman/katalog', $data);
    }

    // Fungsi bagi anggota untuk melakukan peminjaman buku secara mandiri
    public function pinjam_mandiri()
    {
        $db = \Config\Database::connect();
        // Mengambil ID user dari session yang aktif
        $id_user = session()->get('id');
        // Mengambil ID buku yang ingin dipinjam dari form POST
        $id_buku = $this->request->getPost('id_buku');

        // Validasi jika user tidak memilih buku
        if (!$id_buku) {
            return redirect()->back()->with('error', 'Pilih buku terlebih dahulu!');
        }

        // Cek jumlah buku yang sedang dipinjam (Maksimal 3 buku)
        $jumlah = $db->table('peminjaman')
            ->where('id_user', $id_user)
            ->whereIn('status', ['dipinjam', 'diajukan'])
            ->countAllResults();

        // Jika sudah pinjam 3, tolak peminjaman baru
        if ($jumlah >= 3) {
            return redirect()->back()->with('error', 'Maaf, maksimal pinjam 3 buku!');
        }

        // Set tanggal kembali otomatis 7 hari dari hari ini
        $tgl_kembali = date('Y-m-d', strtotime('+7 days'));
        
        // Simpan data peminjaman ke database
        $db->table('peminjaman')->insert([
            'id_user'     => $id_user,
            'id_buku'     => $id_buku,
            'tgl_pinjam'  => date('Y-m-d'),
            'tgl_kembali' => $tgl_kembali,
            'denda'       => 0,
            'status'      => 'dipinjam'
        ]);

        // Kurangi stok buku tersebut sebanyak 1 secara otomatis
        $db->query("UPDATE buku SET stok = stok - 1 WHERE id_buku = ?", [$id_buku]);

        return redirect()->to('/peminjaman')->with('msg', 'Buku berhasil dipinjam, selamat membaca!');
    }

    // Fungsi bagi anggota untuk lapor akan mengembalikan buku
    public function ajukan_kembali($id)
    {
        $db = \Config\Database::connect();
        
        // Update status peminjaman menjadi 'diajukan' (menunggu verifikasi admin)
        $update = $db->table('peminjaman')
            ->where('id_pinjam', $id)
            ->update([
                'status' => 'diajukan' 
            ]);

        if ($update) {
            return redirect()->to('/peminjaman')->with('msg', 'Permintaan pengembalian berhasil diajukan!');
        }

        return redirect()->to('/peminjaman')->with('error', 'Gagal mengajukan pengembalian.');
    }

    // --- FITUR KONFIRMASI ADMIN (Saat Admin menerima fisik buku) ---
    public function konfirmasi_kembali($id) {
        $db = \Config\Database::connect();
        
        // Cari data peminjaman berdasarkan ID
        $dataLama = $db->table('peminjaman')->getWhere(['id_pinjam' => $id])->getRowArray();
        
        if (!$dataLama) {
            return redirect()->back()->with('error', 'Data tidak ditemukan.');
        }

        // Logic hitung denda: bandingkan tanggal kembali dengan hari ini
        $tgl_kembali = new \DateTime($dataLama['tgl_kembali']);
        $tgl_sekarang = new \DateTime(date('Y-m-d'));
        $denda = 0;

        // Jika hari ini sudah lewat dari tanggal seharusnya kembali
        if ($tgl_sekarang > $tgl_kembali) {
            $selisih = $tgl_sekarang->diff($tgl_kembali);
            $denda = $selisih->days * 5000; // Hitung hari telat x tarif denda
        }

        // Update database: status 'kembali', catat denda, dan set bayar lunas
        $db->table('peminjaman')->where('id_pinjam', $id)->update([
            'status'           => 'kembali',
            'tgl_dikembalikan' => date('Y-m-d'),
            'denda'            => $denda,
            'status_bayar'     => 'lunas' 
        ]);
        
        return redirect()->to('/peminjaman')->with('msg', 'Buku telah dikembalikan dan denda lunas!');
    }

    // Menghapus data transaksi peminjaman
    public function hapus($id)
    {
        $db = \Config\Database::connect();
        // Cari data yang mau dihapus
        $data = $db->table('peminjaman')->where('id_pinjam', $id)->get()->getRow();

        if ($data) {
            // Jalankan perintah hapus
            $db->table('peminjaman')->where('id_pinjam', $id)->delete();
            return redirect()->to('/peminjaman')->with('msg', 'Data riwayat berhasil dihapus!');
        }

        return redirect()->to('/peminjaman')->with('error', 'Gagal menghapus data.');
    }

    // Fungsi bagi anggota untuk bayar denda via upload bukti transfer
    public function bayar_dan_ajukan($id)
    {
        // Ambil metode bayar dan file bukti dari form
        $metode = $this->request->getPost('metode_bayar');
        $fileBukti = $this->request->getFile('bukti_pembayaran');
        $namaFile = null;

        // Jika bayar via transfer, simpan file gambarnya ke server
        if ($metode == 'tf') {
            if ($fileBukti->isValid() && !$fileBukti->hasMoved()) {
                $namaFile = $fileBukti->getRandomName(); // Nama file random
                $fileBukti->move('uploads/bukti_bayar/', $namaFile); // Simpan ke folder
            }
        }

        $db = \Config\Database::connect();
        // Update status ke 'diajukan' dan status bayar ke 'proses' untuk diverifikasi admin
        $db->table('peminjaman')->where('id_pinjam', $id)->update([
            'status'           => 'diajukan',
            'status_bayar'     => 'proses', 
            'bukti_bayar'      => $namaFile,
            'tgl_dikembalikan' => date('Y-m-d')
        ]);

        return redirect()->to(base_url('peminjaman'))->with('msg', 'Berhasil diajukan! Menunggu verifikasi pembayaran.');
    }
}