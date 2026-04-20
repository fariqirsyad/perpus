<?php

namespace App\Controllers;

use App\Models\BukuModel;

class Buku extends BaseController
{
    protected $bukuModel;

    public function __construct() {
        $this->bukuModel = new BukuModel();
    }

   public function index()
{
    // 1. Ambil data dari URL (cari dan kategori)
    $keyword  = $this->request->getGet('cari');
    $kategori = $this->request->getGet('kategori');

    // 2. Inisialisasi Builder
    $builder = $this->bukuModel->builder();

    // 3. Logic Pencarian Teks
    if ($keyword) {
        $builder->groupStart()
                ->like('judul', $keyword)
                ->orLike('penulis', $keyword)
                ->orLike('isbn', $keyword)
                ->groupEnd();
    }

    // 4. Logic Filter Kategori (Ini yang bikin REAKSI!)
    // Kita cek jika kategori ada dan bukan "Semua"
    if ($kategori && $kategori != 'Semua') {
        $builder->where('kategori', $kategori);
    }

    $data = [
        'title'         => 'Daftar Buku',
        'list_kategori' => [
            'Semua', 'Umum', 'Agama', 'Sains & Teknologi', 
            'Sosial & Sejarah', 'Bahasa & Sastra', 'Seni & Rekreasi'
        ],
        // 5. Eksekusi Query (Gunakan get()->getResultArray() untuk builder)
        'buku'          => $builder->orderBy('id_buku', 'DESC')->get()->getResultArray(), 
        'keyword'       => $keyword,
        'kategori_now'  => $kategori // Kirim balik biar dropdown tetap terpilih
    ];

    return view('buku/index', $data);
}

    public function simpan()
    {
        // FIX: Tambahkan kategori, isbn, dan tahun_terbit agar tidak strip (-) lagi!
        $data = [
            'judul'          => $this->request->getPost('judul'),
            'penulis'        => $this->request->getPost('penulis'),
            'isbn'           => $this->request->getPost('isbn'),
            'kategori'       => $this->request->getPost('kategori'), // Sesuai kolom DB
            'penerbit'       => $this->request->getPost('penerbit'), // Sesuai kolom DB
            'tahun_terbit'   => $this->request->getPost('tahun_terbit'), // Sesuai kolom DB
            'stok'           => $this->request->getPost('stok'),
            'deskripsi'      => $this->request->getPost('deskripsi'),
            'denda_per_hari' => $this->request->getPost('denda_per_hari'),
        ];

        $fileCover = $this->request->getFile('cover');

        if ($fileCover && $fileCover->isValid() && !$fileCover->hasMoved()) {
            $namaGambar = $fileCover->getRandomName();
            $fileCover->move('uploads/cover', $namaGambar);
            $data['cover'] = $namaGambar;
        }

        $this->bukuModel->save($data);

        return redirect()->to('/buku')->with('msg', 'Buku baru berhasil ditambahkan!');
    }

    public function update($id)
    {
        // FIX: Tambahkan kategori, isbn, dan tahun_terbit di fungsi update juga!
        $data = [
            'judul'          => $this->request->getPost('judul'),
            'penulis'        => $this->request->getPost('penulis'),
            'isbn'           => $this->request->getPost('isbn'),
            'kategori'       => $this->request->getPost('kategori'),
            'penerbit'       => $this->request->getPost('penerbit'),
            'tahun_terbit'   => $this->request->getPost('tahun_terbit'),
            'stok'           => $this->request->getPost('stok'),
            'deskripsi'      => $this->request->getPost('deskripsi'),
            'denda_per_hari' => $this->request->getPost('denda_per_hari'),
        ];

        $fileCover = $this->request->getFile('cover');

        if ($fileCover && $fileCover->isValid() && !$fileCover->hasMoved()) {
            // Hapus cover lama
            $buku = $this->bukuModel->find($id);
            if ($buku['cover'] && file_exists('uploads/cover/' . $buku['cover'])) {
                unlink('uploads/cover/' . $buku['cover']);
            }

            $namaGambar = $fileCover->getRandomName();
            $fileCover->move('uploads/cover', $namaGambar);
            $data['cover'] = $namaGambar;
        }

        $this->bukuModel->update($id, $data);

        return redirect()->to('/buku')->with('msg', 'Data buku berhasil diperbarui!');
    }

    public function hapus($id)
    {
        $db = \Config\Database::connect();
        $db->table('peminjaman')->where('id_buku', $id)->delete();
        
        $this->bukuModel->delete($id);

        return redirect()->to('/buku')->with('msg', 'Buku dan riwayatnya berhasil dihapus!');
    }
}