<?php

namespace App\Controllers;

use App\Models\BukuModel;

class Buku extends BaseController
{
    protected $bukuModel;

    public function __construct() {
        $this->bukuModel = new BukuModel();
    }

   public function index() {
    $cari = $this->request->getVar('cari');
    
    if ($cari) {
        // Jika ada pencarian, cari di kolom judul atau penulis
        $data['buku'] = $this->bukuModel->like('judul', $cari)
                                        ->orLike('penulis', $cari)
                                        ->findAll();
    } else {
        $data['buku'] = $this->bukuModel->findAll();
    }
    
    return view('buku/index', $data);
}

    public function simpan()
{
    $bukuModel = new \App\Models\BukuModel();

    // Mengambil data teks biasa
    $data = [
        'judul'          => $this->request->getPost('judul'),
        'penulis'        => $this->request->getPost('penulis'),
        'stok'           => $this->request->getPost('stok'),
        'denda_per_hari' => $this->request->getPost('denda_per_hari'),
    ];

    
    // Ganti baris 43 ke bawah jadi begini:
$fileCover = $this->request->getFile('cover');

// Cek apakah variabel $fileCover itu ada isinya (tidak null)
if ($fileCover && $fileCover->isValid() && !$fileCover->hasMoved()) {
    $namaGambar = $fileCover->getRandomName();
    $fileCover->move('uploads/cover', $namaGambar);
    $data['cover'] = $namaGambar;
} else {
    // Kalau nggak ada gambar, kasih gambar default atau biarkan kosong sesuai DB kamu
    $data['cover'] = 'default.jpg'; 
}

    $bukuModel->save($data);

    return redirect()->to('/buku')->with('msg', 'Buku baru berhasil ditambahkan!');
}

    public function update($id)
{
    $bukuModel = new \App\Models\BukuModel();

    // Data teks
    $data = [
        'judul'          => $this->request->getPost('judul'),
        'penulis'        => $this->request->getPost('penulis'),
        'stok'           => $this->request->getPost('stok'),
        'denda_per_hari' => $this->request->getPost('denda_per_hari'),
    ];

    $fileCover = $this->request->getFile('cover');

    if ($fileCover->isValid() && !$fileCover->hasMoved()) {
        // HAPUS COVER LAMA (Biar gak menuhin storage)
        $buku = $bukuModel->find($id);
        if ($buku['cover'] && file_exists('uploads/cover/' . $buku['cover'])) {
            unlink('uploads/cover/' . $buku['cover']); // Hapus file fisiknya
        }

        // Upload Cover BARU
        $namaGambar = $fileCover->getRandomName();
        $fileCover->move('uploads/cover', $namaGambar);
        $data['cover'] = $namaGambar;
    }
    // Jika admin tidak upload cover baru, data 'cover' lama tidak akan berubah.

    $bukuModel->update($id, $data);

    return redirect()->to('/buku')->with('msg', 'Data buku berhasil diperbarui!');
}

    public function hapus($id)
{
    $db = \Config\Database::connect();
    
    // 1. Hapus dulu semua riwayat pinjam yang berhubungan dengan buku ini
    $db->table('peminjaman')->where('id_buku', $id)->delete();
    
    // 2. Baru hapus bukunya
    $this->bukuModel->delete($id);

    return redirect()->to('/buku')->with('msg', 'Buku dan riwayatnya berhasil dihapus!');
}
}