<?php

namespace App\Controllers;

use App\Models\PeminjamanModel;

class Peminjaman extends BaseController
{
    // --- FITUR UMUM ---

    public function index()
    {
        $model = new PeminjamanModel();
        $cari = $this->request->getVar('cari');

        // Jika ada pencarian, panggil fungsi search di model
        if ($cari) {
            $data['transaksi'] = $model->searchPeminjaman($cari);
        } else {
            // Jika login sebagai anggota, hanya tampilkan pinjamannya sendiri
            if (session('role') == 'anggota') {
                $model->where('peminjaman.id_user', session('id'));
            }

            $data['transaksi'] = $model->select('peminjaman.*, users.nama, buku.judul')
                ->join('users', 'users.id = peminjaman.id_user')
                ->join('buku', 'buku.id_buku = peminjaman.id_buku')
                ->findAll();
        }

        return view('peminjaman/index', $data);
    }

    // --- FITUR KHUSUS ANGGOTA ---

    public function katalog()
    {
        $db = \Config\Database::connect();
        // Hanya tampilkan buku yang stoknya di atas 0
        $data['buku'] = $db->table('buku')->where('stok >', 0)->get()->getResultArray();
        return view('peminjaman/katalog', $data);
    }

    public function pinjam_mandiri()
    {
        $db = \Config\Database::connect();
        $id_user = session()->get('id');
        $id_buku = $this->request->getPost('id_buku');

        // Validasi maksimal pinjam 3 buku yang statusnya masih 'dipinjam' atau 'diajukan'
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
            'id_user'    => $id_user,
            'id_buku'    => $id_buku,
            'tgl_pinjam' => date('Y-m-d'),
            'tgl_kembali' => $tgl_kembali,
            'status'     => 'dipinjam'
        ]);

        // Kurangi stok buku
        $db->query("UPDATE buku SET stok = stok - 1 WHERE id_buku = ?", [$id_buku]);

        return redirect()->to('/peminjaman')->with('msg', 'Berhasil meminjam buku!');
    }

    public function ajukan_kembali($id)
    {
        $db = \Config\Database::connect();
        $db->table('peminjaman')->where('id_pinjam', $id)->update([
            'status' => 'diajukan'
        ]);

        return redirect()->to('/peminjaman')->with('msg', 'Permintaan pengembalian telah dikirim ke admin.');
    }

    // --- FITUR KHUSUS ADMIN ---

    public function konfirmasi_kembali($id)
    {
        $db = \Config\Database::connect();
        $pinjam = $db->table('peminjaman')->where('id_pinjam', $id)->get()->getRow();

        if (!$pinjam) return redirect()->to('/peminjaman');

        // Hitung Denda (misal 5000 per hari)
        $tgl_deadline = strtotime($pinjam->tgl_kembali);
        $tgl_sekarang = strtotime(date('Y-m-d'));
        $denda = 0;

        if ($tgl_sekarang > $tgl_deadline) {
            $selisih = ($tgl_sekarang - $tgl_deadline) / (60 * 60 * 24);
            $denda = $selisih * 5000;
        }

        // Update status jadi kembali dan catat denda
        $db->table('peminjaman')->where('id_pinjam', $id)->update([
            'tgl_dikembalikan' => date('Y-m-d'),
            'denda' => $denda,
            'status' => 'kembali'
        ]);

        // Tambahkan kembali stok buku
        $db->query("UPDATE buku SET stok = stok + 1 WHERE id_buku = ?", [$pinjam->id_buku]);

        return redirect()->to('/peminjaman')->with('msg', 'Buku berhasil dikonfirmasi kembali!');
    }
}