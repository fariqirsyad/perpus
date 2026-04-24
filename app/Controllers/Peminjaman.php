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
    public function konfirmasi_kembali($id) {
    // Inisialisasi DB manual
    $db = \Config\Database::connect();
    
    $dataLama = $db->table('peminjaman')->getWhere(['id_pinjam' => $id])->getRowArray();
    
    if (!$dataLama) {
        return redirect()->back()->with('error', 'Data tidak ditemukan.');
    }

    // Logic hitung denda sederhana (biar sinkron sama View)
    $tgl_kembali = new \DateTime($dataLama['tgl_kembali']);
    $tgl_sekarang = new \DateTime(date('Y-m-d'));
    $denda = 0;

    if ($tgl_sekarang > $tgl_kembali) {
        $selisih = $tgl_sekarang->diff($tgl_kembali);
        $denda = $selisih->days * 5000; // sesuaikan tarif lu
    }

    // Update ke database
    $db->table('peminjaman')->where('id_pinjam', $id)->update([
        'status'           => 'kembali',
        'tgl_dikembalikan' => date('Y-m-d'),
        'denda'            => $denda,
        'status_bayar'     => 'lunas' // Karena admin sudah konfirmasi terima
    ]);
    
    return redirect()->to('/peminjaman')->with('msg', 'Buku telah dikembalikan dan denda lunas!');
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

public function bayar_dan_ajukan($id)
{
    $metode = $this->request->getPost('metode_bayar');
    $fileBukti = $this->request->getFile('bukti_pembayaran');
    $namaFile = null;

    if ($metode == 'tf') {
        if ($fileBukti->isValid() && !$fileBukti->hasMoved()) {
            $namaFile = $fileBukti->getRandomName();
            $fileBukti->move('uploads/bukti_bayar/', $namaFile);
        }
    }

    $db = \Config\Database::connect();
    $db->table('peminjaman')->where('id_pinjam', $id)->update([
        'status'           => 'diajukan',
        'status_bayar'     => 'proses', // Denda TETAP, status bayar jadi PROSES
        'bukti_bayar'      => $namaFile,
        'tgl_dikembalikan' => date('Y-m-d')
    ]);

    return redirect()->to(base_url('peminjaman'))->with('msg', 'Berhasil diajukan! Menunggu verifikasi pembayaran.');
}
    
}