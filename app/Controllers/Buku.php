<?php

namespace App\Controllers;

// Mengimport model BukuModel untuk akses data tabel buku
use App\Models\BukuModel;

class Buku extends BaseController
{
    // Properti untuk menampung instance BukuModel
    protected $bukuModel;

    // Constructor: Fungsi yang otomatis jalan pertama kali saat class dipanggil
    public function __construct() {
        // Inisialisasi model buku agar bisa dipakai di semua method dalam class ini
        $this->bukuModel = new BukuModel();
    }

    // Method untuk menampilkan daftar buku dengan fitur pencarian & filter
    public function index()
    {
        // 1. Mengambil data dari parameter URL (?cari=...&kategori=...)
        $keyword   = $this->request->getGet('cari');
        $kategori = $this->request->getGet('kategori');

        // 2. Memanggil Query Builder dari model
        $builder = $this->bukuModel->builder();

        // 3. Logika Pencarian Teks (Judul, Penulis, atau ISBN)
        if ($keyword) {
            // groupStart & groupEnd berfungsi seperti kurung buka-tutup di SQL (WHERE (...) AND ...)
            $builder->groupStart()
                    ->like('judul', $keyword)
                    ->orLike('penulis', $keyword)
                    ->orLike('isbn', $keyword)
                    ->groupEnd();
        }

        // 4. Logika Filter Kategori
        // Jika kategori dipilih dan nilainya bukan "Semua", tambahkan filter WHERE
        if ($kategori && $kategori != 'Semua') {
            $builder->where('kategori', $kategori);
        }

        // 5. Menyiapkan data untuk dikirim ke view
        $data = [
            'title'         => 'Daftar Buku',
            'list_kategori' => [ // Daftar pilihan kategori untuk dropdown di view
                'Semua', 'Umum', 'Agama', 'Sains & Teknologi', 
                'Sosial & Sejarah', 'Bahasa & Sastra', 'Seni & Rekreasi'
            ],
            // Eksekusi query: urutkan dari yang terbaru (DESC) lalu ambil hasilnya dalam bentuk array
            'buku'          => $builder->orderBy('id_buku', 'DESC')->get()->getResultArray(), 
            'keyword'       => $keyword,
            'kategori_now'  => $kategori // Dikirim balik agar dropdown tetap menampilkan pilihan user
        ];

        // Memanggil halaman view index buku dengan membawa data di atas
        return view('buku/index', $data);
    }

    // Method untuk menyimpan data buku baru
    public function simpan()
    {
        // Menangkap data dari form input POST
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

        // Menangkap file cover yang diupload
        $fileCover = $this->request->getFile('cover');

        // Jika ada file yang diupload dan valid
        if ($fileCover && $fileCover->isValid() && !$fileCover->hasMoved()) {
            // Generate nama file acak agar tidak bentrok
            $namaGambar = $fileCover->getRandomName();
            // Pindahkan file ke folder public/uploads/cover
            $fileCover->move('uploads/cover', $namaGambar);
            // Simpan nama file ke array data
            $data['cover'] = $namaGambar;
        }

        // Jalankan perintah save (insert) ke database
        $this->bukuModel->save($data);

        // Redirect kembali ke halaman buku dengan pesan sukses
        return redirect()->to('/buku')->with('msg', 'Buku baru berhasil ditambahkan!');
    }

    // Method untuk memperbarui data buku yang sudah ada
    public function update($id)
    {
        // Menangkap data baru dari form edit
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

        // Jika user mengupload cover baru
        if ($fileCover && $fileCover->isValid() && !$fileCover->hasMoved()) {
            // Ambil data buku lama untuk mencari nama file cover lama
            $buku = $this->bukuModel->find($id);
            // Hapus file cover lama dari folder jika ada
            if ($buku['cover'] && file_exists('uploads/cover/' . $buku['cover'])) {
                unlink('uploads/cover/' . $buku['cover']);
            }

            // Simpan cover baru
            $namaGambar = $fileCover->getRandomName();
            $fileCover->move('uploads/cover', $namaGambar);
            $data['cover'] = $namaGambar;
        }

        // Jalankan perintah update berdasarkan ID buku
        $this->bukuModel->update($id, $data);

        return redirect()->to('/buku')->with('msg', 'Data buku berhasil diperbarui!');
    }

    // Method untuk menghapus buku
    public function hapus($id)
    {
        // Koneksi DB manual untuk menghapus data di tabel relasi (peminjaman)
        // Ini dilakukan agar tidak terjadi error foreign key (jika tidak pakai cascade)
        $db = \Config\Database::connect();
        $db->table('peminjaman')->where('id_buku', $id)->delete();
        
        // Hapus data buku dari tabel utama
        $this->bukuModel->delete($id);

        return redirect()->to('/buku')->with('msg', 'Buku dan riwayatnya berhasil dihapus!');
    }
}