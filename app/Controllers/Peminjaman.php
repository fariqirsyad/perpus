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
    
    // Ambil data dari URL (Pencarian & Filter Status)
    $cari   = $this->request->getGet('cari');
    $status = $this->request->getGet('status');

    // Gunakan Query Builder agar join selalu presisi
    $builder = $model->select('peminjaman.*, users.nama, buku.judul, buku.denda_per_hari')
                     ->join('users', 'users.id = peminjaman.id_user')
                     ->join('buku', 'buku.id_buku = peminjaman.id_buku');

    // 1. Logic Pencarian (Nama atau Judul)
    if ($cari) {
        $builder->groupStart()
                ->like('users.nama', $cari)
                ->orLike('buku.judul', $cari)
                ->groupEnd();
    }

    // 2. Logic Filter Status (TAMBAHAN BARU)
    if ($status) {
        // Pastikan nama kolom 'status_peminjaman' sesuai dengan yang ada di database kamu
        $builder->where('peminjaman.status', $status);
    }

    // 3. Filter jika login sebagai anggota (hanya lihat data sendiri)
    if (session('role') == 'anggota') {
        $builder->where('peminjaman.id_user', session('id'));
    }

    $data['transaksi'] = $builder->orderBy('peminjaman.id_pinjam', 'DESC')->findAll();
    
    // Kirim juga variabel status ke view biar dropdown-nya tetap terpilih (stay selected)
    $data['cari'] = $cari;
    $data['status_saat_ini'] = $status;

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

    // Simpan data peminjaman
    $tgl_kembali = date('Y-m-d', strtotime('+7 days'));
    $db->table('peminjaman')->insert([
        'id_user'     => $id_user,
        'id_buku'     => $id_buku,
        'tgl_pinjam'  => date('Y-m-d'),
        'tgl_kembali' => $tgl_kembali,
        'denda'       => 0,
        'status'      => 'dipinjam'
    ]);

    // Kurangi stok buku
    $db->query("UPDATE buku SET stok = stok - 1 WHERE id_buku = ?", [$id_buku]);

    // PASTIKAN REDIRECT KE HALAMAN YANG ADA SCRIPT SWEETALERT-NYA
    return redirect()->to('/peminjaman')->with('msg', 'Buku berhasil dipinjam, selamat membaca!');
}

    // --- UNTUK ANGGOTA (MINTA BALIKIN) ---
    public function ajukan_kembali($id)
    {
        $db = \Config\Database::connect();
        
        // Update status jadi 'diajukan' (bukan kembali, karena admin belum nerima)
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

    // --- UNTUK ADMIN (TERIMA BUKU) ---
    public function konfirmasi_kembali($id)
    {
        $db = \Config\Database::connect();

        $pinjam = $db->table('peminjaman')
                     ->select('peminjaman.*, buku.denda_per_hari')
                     ->join('buku', 'buku.id_buku = peminjaman.id_buku')
                     ->where('id_pinjam', $id)
                     ->get()
                     ->getRow();

        if (!$pinjam) {
            return redirect()->to('/peminjaman')->with('error', 'Data tidak ditemukan.');
        }

        // Hitung Denda
        $tgl_deadline = strtotime($pinjam->tgl_kembali);
        $tgl_sekarang = strtotime(date('Y-m-d'));
        $total_denda = 0;

        if ($tgl_sekarang > $tgl_deadline) {
            $selisih_hari = floor(($tgl_sekarang - $tgl_deadline) / (60 * 60 * 24));
            $tarif_denda = ($pinjam->denda_per_hari > 0) ? $pinjam->denda_per_hari : 5000;
            $total_denda = $selisih_hari * $tarif_denda;
        }

        // Update Status jadi 'kembali' dan simpan denda
        $db->table('peminjaman')->where('id_pinjam', $id)->update([
            'tgl_dikembalikan' => date('Y-m-d'),
            'denda'            => $total_denda,
            'status'           => 'kembali'
        ]);

        // Stok Buku baru nambah di sini (pas dikonfirmasi admin)
        $db->query("UPDATE buku SET stok = stok + 1 WHERE id_buku = ?", [$pinjam->id_buku]);

        $pesan = ($total_denda > 0) 
                 ? "Buku diterima! Terlambat $selisih_hari hari, denda: Rp " . number_format($total_denda, 0, ',', '.') 
                 : "Buku diterima tepat waktu.";

        return redirect()->to('/peminjaman')->with('msg', $pesan);
    }

public function hapus($id)
{
    $db = \Config\Database::connect();
    
    // Cek apakah data ada
    $data = $db->table('peminjaman')->where('id_pinjam', $id)->get()->getRow();

    if ($data) {
        // Proses hapus
        $hapus = $db->table('peminjaman')->where('id_pinjam', $id)->delete();

        if ($hapus) {
            return redirect()->to('/peminjaman')->with('msg', 'Data riwayat berhasil dihapus!');
        }
    }

    return redirect()->to('/peminjaman')->with('error', 'Gagal menghapus data.');
}

}