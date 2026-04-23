<?php

namespace App\Controllers;

use App\Models\PeminjamanModel;
use App\Models\BukuModel; 

class Peminjaman extends BaseController
{
    // --- FITUR UMUM ---

    public function index()
    {
        $model = new PeminjamanModel();
        
        $cari   = $this->request->getGet('cari');
        $status = $this->request->getGet('status');

        $builder = $model->select('peminjaman.*, users.nama, buku.judul, buku.denda_per_hari')
                         ->join('users', 'users.id = peminjaman.id_user')
                         ->join('buku', 'buku.id_buku = peminjaman.id_buku');

        if ($cari) {
            $builder->groupStart()
                    ->like('users.nama', $cari)
                    ->orLike('buku.judul', $cari)
                    ->groupEnd();
        }

        if ($status) {
            $builder->where('peminjaman.status', $status);
        }

        if (session('role') == 'anggota') {
            $builder->where('peminjaman.id_user', session('id'));
        }

        $data['transaksi'] = $builder->orderBy('peminjaman.id_pinjam', 'DESC')->findAll();
        
        $data['cari'] = $cari;
        $data['status_saat_ini'] = $status;

        return view('peminjaman/index', $data);
    }

    // --- FITUR KHUSUS ANGGOTA ---

    public function katalog()
    {
        $db = \Config\Database::connect();
        $kategori_dipilih = $this->request->getVar('filter_kategori'); 
        
        $builder = $db->table('buku');

        if ($kategori_dipilih && $kategori_dipilih != 'Semua') {
            $builder->where('kategori', $kategori_dipilih);
        }

        $data['buku'] = $builder->get()->getResultArray();
        $data['kategori_aktif'] = $kategori_dipilih ?? 'Semua';

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

        $jumlah = $db->table('peminjaman')
            ->where('id_user', $id_user)
            ->whereIn('status', ['dipinjam', 'diajukan'])
            ->countAllResults();

        if ($jumlah >= 3) {
            return redirect()->back()->with('error', 'Maaf, maksimal pinjam 3 buku!');
        }

        $tgl_kembali = date('Y-m-d', strtotime('+7 days'));
        $db->table('peminjaman')->insert([
            'id_user'     => $id_user,
            'id_buku'     => $id_buku,
            'tgl_pinjam'  => date('Y-m-d'),
            'tgl_kembali' => $tgl_kembali,
            'denda'       => 0,
            'status'      => 'dipinjam'
        ]);

        $db->query("UPDATE buku SET stok = stok - 1 WHERE id_buku = ?", [$id_buku]);

        return redirect()->to('/peminjaman')->with('msg', 'Buku berhasil dipinjam, selamat membaca!');
    }

    public function ajukan_kembali($id)
    {
        $db = \Config\Database::connect();
        
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

    // --- UPDATE FITUR KONFIRMASI ADMIN ---
    public function konfirmasi_kembali($id)
{
    $db = \Config\Database::connect();
    $pinjam = $db->table('peminjaman')
                 ->select('peminjaman.*, buku.denda_per_hari')
                 ->join('buku', 'buku.id_buku = peminjaman.id_buku')
                 ->where('id_pinjam', $id)->get()->getRow();

    // PAKSA hitungan tanggal murni
    $deadline = strtotime(date('Y-m-d', strtotime($pinjam->tgl_kembali)));
    $hari_ini = strtotime(date('Y-m-d'));
    
    $total_denda = 0;
    if ($hari_ini > $deadline) {
        $selisih_detik = $hari_ini - $deadline;
        $selisih_hari = floor($selisih_detik / (60 * 60 * 24));
        
        $tarif = ($pinjam->denda_per_hari > 0) ? $pinjam->denda_per_hari : 5000;
        $total_denda = $selisih_hari * $tarif;
    }

    $db->table('peminjaman')->where('id_pinjam', $id)->update([
        'tgl_dikembalikan' => date('Y-m-d'),
        'denda'            => $total_denda,
        'status'           => 'kembali'
    ]);

    $db->query("UPDATE buku SET stok = stok + 1 WHERE id_buku = ?", [$pinjam->id_buku]);
    return redirect()->to('/peminjaman')->with('msg', 'Buku kembali. Denda: Rp '.number_format($total_denda,0,',','.'));
}

    public function hapus($id)
    {
        $db = \Config\Database::connect();
        $data = $db->table('peminjaman')->where('id_pinjam', $id)->get()->getRow();

        if ($data) {
            $db->table('peminjaman')->where('id_pinjam', $id)->delete();
            return redirect()->to('/peminjaman')->with('msg', 'Data riwayat berhasil dihapus!');
        }

        return redirect()->to('/peminjaman')->with('error', 'Gagal menghapus data.');
    }


    
}