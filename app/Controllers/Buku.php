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

    public function simpan() {
        $this->bukuModel->save([
            'judul'          => $this->request->getPost('judul'),
            'penulis'        => $this->request->getPost('penulis'),
            'stok'           => $this->request->getPost('stok'),
            'denda_per_hari' => $this->request->getPost('denda_per_hari'), // Tambahkan ini
        ]);
        return redirect()->to('/buku')->with('msg', 'Buku berhasil ditambah!');
    }

    public function update($id) {
        $this->bukuModel->update($id, [
            'judul'          => $this->request->getPost('judul'),
            'penulis'        => $this->request->getPost('penulis'),
            'stok'           => $this->request->getPost('stok'),
            'denda_per_hari' => $this->request->getPost('denda_per_hari'), // Tambahkan ini
        ]);
        return redirect()->to('/buku')->with('msg', 'Data buku diperbarui!');
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