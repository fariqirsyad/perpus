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

        $data['transaksi'] = $model->orderBy('peminjaman.id_pinjam', 'DESC')->paginate(10, 'transaksi');
        $data['pager'] = $model->pager;
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

    // --- PROTEKSI DENDA REAL-TIME ---
    $pinjaman = $db->table('peminjaman')
                   ->where('id_user', $id_user)
                   ->where('status !=', 'kembali') // Cari buku yang belum balik
                   ->get()->getResultArray();

    foreach ($pinjaman as $p) {
        $tgl_kembali = new \DateTime($p['tgl_kembali']);
        $tgl_sekarang = new \DateTime(date('Y-m-d'));

        // CEK 1: Berdasarkan Tanggal (Denda berjalan)
        if ($tgl_sekarang > $tgl_kembali) {
            return redirect()->back()->with('error', 'Gagal! Kamu punya buku yang telat dikembalikan. Lunasi denda dulu!');
        }
        
        // CEK 2: Berdasarkan Status Bayar di DB
        if ($p['denda'] > 0 && $p['status_bayar'] != 'lunas') {
            return redirect()->back()->with('error', 'Gagal! Kamu masih punya denda yang belum dibayar.');
        }
    }

    // --- PROTEKSI JUMLAH PINJAM ---
    if (count($pinjaman) >= 3) {
        return redirect()->back()->with('error', 'Maksimal pinjam 3 buku!');
    }

    // --- PROSES INSERT ---
    $db->table('peminjaman')->insert([
        'id_user'      => $id_user,
        'id_buku'      => $id_buku,
        'tgl_pinjam'   => date('Y-m-d'),
        'tgl_kembali'  => date('Y-m-d', strtotime('+7 days')),
        'denda'        => 0,
        'status'       => 'dipinjam',
        'status_bayar' => 'belum'
    ]);

    $db->query("UPDATE buku SET stok = stok - 1 WHERE id_buku = ?", [$id_buku]);

    return redirect()->to('/peminjaman')->with('msg', 'Berhasil meminjam buku!');
}

    public function ajukan_kembali($id)
    {
        $db = \Config\Database::connect();
        $update = $db->table('peminjaman')->where('id_pinjam', $id)->update(['status' => 'diajukan']);
        return ($update) ? redirect()->to('/peminjaman')->with('msg', 'Berhasil diajukan!') : redirect()->to('/peminjaman')->with('error', 'Gagal!');
    }

    // --- FITUR KONFIRMASI ADMIN ---
   public function konfirmasi_kembali($id) {
    $db = \Config\Database::connect();
    $dataLama = $db->table('peminjaman')->getWhere(['id_pinjam' => $id])->getRowArray();
    
    if (!$dataLama) return redirect()->back()->with('error', 'Data tidak ditemukan.');

    $id_buku = $dataLama['id_buku'];
    $tgl_kembali = new \DateTime($dataLama['tgl_kembali']);
    $tgl_sekarang = new \DateTime(date('Y-m-d'));
    $denda = 0;

    if ($tgl_sekarang > $tgl_kembali) {
        $selisih = $tgl_sekarang->diff($tgl_kembali);
        $denda = $selisih->days * 5000; 
    }

    // --- LOGIKA ANTI-RESET DISINI ---
    // Jika sebelumnya user sudah upload bukti (proses), maka langsung set lunas.
    // Jika denda 0, juga lunas.
    // Selain itu baru 'belum'.
    $status_bayar_baru = 'belum';
    if ($denda == 0 || $dataLama['status_bayar'] == 'proses' || $dataLama['status_bayar'] == 'lunas') {
        $status_bayar_baru = 'lunas';
    }

    $db->table('peminjaman')->where('id_pinjam', $id)->update([
        'status'           => 'kembali',
        'tgl_dikembalikan' => date('Y-m-d'),
        'denda'            => $denda,
        'status_bayar'     => $status_bayar_baru 
    ]);

    $db->query("UPDATE buku SET stok = stok + 1 WHERE id_buku = ?", [$id_buku]);
    return redirect()->to('/peminjaman')->with('msg', 'Buku telah dikembalikan dan status diperbarui!');
}

    public function hapus($id)
    {
        $db = \Config\Database::connect();
        $data = $db->table('peminjaman')->where('id_pinjam', $id)->get()->getRow();
        if ($data) {
            $db->table('peminjaman')->where('id_pinjam', $id)->delete();
            return redirect()->to('/peminjaman')->with('msg', 'Data riwayat berhasil dihapus!');
        }
        return redirect()->to('/peminjaman')->with('error', 'Gagal!');
    }

    public function bayar_dan_ajukan($id)
    {
        $metode = $this->request->getPost('metode_bayar');
        $fileBukti = $this->request->getFile('bukti_pembayaran');
        $namaFile = null;

        if ($metode == 'tf' && $fileBukti->isValid() && !$fileBukti->hasMoved()) {
            $namaFile = $fileBukti->getRandomName();
            $fileBukti->move('uploads/bukti_bayar/', $namaFile);
        }

        $db = \Config\Database::connect();
        // --- PERBAIKAN DISINI: status_bayar jadi 'proses' bukan langsung lunas ---
        $db->table('peminjaman')->where('id_pinjam', $id)->update([
            'status'           => 'diajukan',
            'status_bayar'     => 'proses', 
            'bukti_bayar'      => $namaFile,
            'tgl_dikembalikan' => date('Y-m-d')
        ]);

        return redirect()->to(base_url('peminjaman'))->with('msg', 'Pembayaran sedang diproses admin!');
    }

public function update_denda($id)
{
    $db = \Config\Database::connect();
    $data = $db->table('peminjaman')->getWhere(['id_pinjam' => $id])->getRowArray();

    if ($data) {
        $tgl_kembali = new \DateTime($data['tgl_kembali']);
        $tgl_sekarang = new \DateTime(date('Y-m-d'));
        $denda = 0;

        // Hitung denda real-time
        if ($tgl_sekarang > $tgl_kembali) {
            $selisih = $tgl_sekarang->diff($tgl_kembali);
            $denda = $selisih->days * 5000;
        }

        // Simpan angka denda ke database agar tidak berubah-ubah lagi
        $db->table('peminjaman')->where('id_pinjam', $id)->update([
            'denda' => $denda
        ]);
        
        return redirect()->back()->with('msg', 'Data denda berhasil diperbarui!');
    }
    return redirect()->back()->with('error', 'Data tidak ditemukan!');
}

public function lunas_denda($id)
{
    $db = \Config\Database::connect();
    
    // 1. Ambil data dulu buat pastiin dendanya dicatat
    $data = $db->table('peminjaman')->getWhere(['id_pinjam' => $id])->getRowArray();
    
    // 2. Hitung denda terakhir biar angkanya gak 0 di DB
    $tgl_kembali = new \DateTime($data['tgl_kembali']);
    $tgl_sekarang = new \DateTime(date('Y-m-d'));
    $denda_akhir = 0;
    
    if ($tgl_sekarang > $tgl_kembali) {
        $selisih = $tgl_sekarang->diff($tgl_kembali);
        $denda_akhir = $selisih->days * 5000;
    }

    // 3. Update status_bayar jadi LUNAS secara permanen di DB
    $db->table('peminjaman')->where('id_pinjam', $id)->update([
        'denda'        => $denda_akhir,
        'status_bayar' => 'lunas' // Ini kuncinya biar jadi HIJAU
    ]);

    return redirect()->back()->with('msg', 'Pembayaran denda telah dikonfirmasi dan status jadi Lunas!');
}

}