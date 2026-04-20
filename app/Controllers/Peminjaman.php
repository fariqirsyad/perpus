<?php

namespace App\Controllers;

use App\Models\PeminjamanModel;
use App\Models\BukuModel; // Tambahkan ini agar lebih rapi

class Peminjaman extends BaseController
{
    // --- FITUR UMUM ---

    public function index()
    {
        $model = new PeminjamanModel();
        $cari = $this->request->getVar('cari');

        // Gunakan Query Builder agar join selalu presisi
        $builder = $model->select('peminjaman.*, users.nama, buku.judul, buku.denda_per_hari')
                         ->join('users', 'users.id = peminjaman.id_user')
                         ->join('buku', 'buku.id_buku = peminjaman.id_buku');

        if ($cari) {
            // Pencarian berdasarkan nama peminjam atau judul buku
            $builder->like('users.nama', $cari)->orLike('buku.judul', $cari);
        }

        // Jika login sebagai anggota, filter data miliknya sendiri
        if (session('role') == 'anggota') {
            $builder->where('peminjaman.id_user', session('id'));
        }

        $data['transaksi'] = $builder->orderBy('peminjaman.id_pinjam', 'DESC')->findAll();

        return view('peminjaman/index', $data);
    }

    // --- FITUR KHUSUS ANGGOTA ---

    public function katalog()
{
    $db = \Config\Database::connect();
    $kategori_dipilih = $this->request->getVar('filter_kategori'); // Ambil pilihan user
    
    $builder = $db->table('buku');

    // Jika user memilih kategori tertentu (dan bukan 'Semua')
    if ($kategori_dipilih && $kategori_dipilih != 'Semua') {
        $builder->where('kategori', $kategori_dipilih);
    }

    $data['buku'] = $builder->get()->getResultArray();
    $data['kategori_aktif'] = $kategori_dipilih ?? 'Semua'; // Untuk tanda tombol mana yang lagi aktif

    return view('peminjaman/katalog', $data);
}

    public function pinjam_mandiri()
    {
        $db = \Config\Database::connect();
        $id_user = session()->get('id');
        $id_buku = $this->request->getPost('id_buku');

        if (!$id_buku) {
            return redirect()->back()->with('error', 'Pilih buku terlebih dahulu!');
        }

        // Validasi maksimal pinjam 3 buku aktif
        $jumlah = $db->table('peminjaman')
            ->where('id_user', $id_user)
            ->whereIn('status', ['dipinjam', 'diajukan'])
            ->countAllResults();

        if ($jumlah >= 3) {
            return redirect()->back()->with('error', 'Maaf, maksimal pinjam 3 buku!');
        }

        // Simpan data peminjaman (Pinjam 7 hari)
        $tgl_kembali = date('Y-m-d', strtotime('+7 days'));
        $db->table('peminjaman')->insert([
            'id_user'     => $id_user,
            'id_buku'     => $id_buku,
            'tgl_pinjam'  => date('Y-m-d'),
            'tgl_kembali' => $tgl_kembali,
            'denda'       => 0, // Inisialisasi denda 0
            'status'      => 'dipinjam'
        ]);

        // Kurangi stok buku
        $db->query("UPDATE buku SET stok = stok - 1 WHERE id_buku = ?", [$id_buku]);

        return redirect()->to('/peminjaman')->with('msg', 'Berhasil meminjam buku!');
    }

    public function ajukan_kembali($id)
    {
        $db = \Config\Database::connect();
        // Update status jadi 'diajukan'
        $db->table('peminjaman')->where('id_pinjam', $id)->update([
            'status' => 'diajukan'
        ]);

        return redirect()->to('/peminjaman')->with('msg', 'Permintaan pengembalian dikirim. Serahkan buku ke Admin.');
    }

    // --- FITUR KHUSUS ADMIN ---

    public function konfirmasi_kembali($id)
    {
        $db = \Config\Database::connect();

        // 1. Ambil data transaksi dan nominal denda per hari dari tabel buku
        $pinjam = $db->table('peminjaman')
                     ->select('peminjaman.*, buku.denda_per_hari')
                     ->join('buku', 'buku.id_buku = peminjaman.id_buku')
                     ->where('id_pinjam', $id)
                     ->get()
                     ->getRow();

        if (!$pinjam) {
            return redirect()->to('/peminjaman')->with('error', 'Data tidak ditemukan.');
        }

        // 2. LOGIKA HITUNG DENDA
        $tgl_deadline = strtotime($pinjam->tgl_kembali);
        $tgl_sekarang = strtotime(date('Y-m-d')); // Tanggal hari ini
        $total_denda = 0;

        if ($tgl_sekarang > $tgl_deadline) {
            $selisih_detik = $tgl_sekarang - $tgl_deadline;
            $selisih_hari  = floor($selisih_detik / (60 * 60 * 24)); // Konversi ke hari
            
            // Gunakan denda dari database, jika kosong default ke 5000
            $tarif_denda = ($pinjam->denda_per_hari > 0) ? $pinjam->denda_per_hari : 5000;
            $total_denda = $selisih_hari * $tarif_denda;
        }

        // 3. Update Status, Tanggal Dikembalikan, dan Denda
        $db->table('peminjaman')->where('id_pinjam', $id)->update([
            'tgl_dikembalikan' => date('Y-m-d'),
            'denda'            => $total_denda,
            'status'           => 'kembali'
        ]);

        // 4. Kembalikan Stok Buku (+1)
        $db->query("UPDATE buku SET stok = stok + 1 WHERE id_buku = ?", [$pinjam->id_buku]);

        $pesan = ($total_denda > 0) 
                 ? "Buku kembali! Member telat $selisih_hari hari, denda: Rp " . number_format($total_denda, 0, ',', '.') 
                 : "Buku kembali tepat waktu. Tidak ada denda.";

        return redirect()->to('/peminjaman')->with('msg', $pesan);
    }
}