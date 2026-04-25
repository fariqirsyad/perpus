<?php

// Namespace controller: menentukan lokasi file agar bisa dipanggil oleh Autoloader CI4
namespace App\Controllers;

// Mengimport model UsersModel agar bisa berinteraksi dengan tabel 'users' di database
use App\Models\UsersModel;

// Class Users yang merupakan turunan dari BaseController
class Users extends BaseController
{
    // Properti dilindungi untuk menyimpan instance model agar bisa diakses di seluruh method class ini
    protected $users;

    // Constructor: fungsi yang otomatis jalan pertama kali saat class diinisialisasi
    public function __construct()
    {
        // Mengisi properti $users dengan instance baru dari UsersModel
        $this->users = new UsersModel();
    }

    // Method untuk menampilkan halaman form tambah user baru
    public function create()
    {
        // Mengembalikan tampilan (view) yang terletak di folder /app/Views/users/create.php
        return view('users/create');
    }

    // Method untuk memproses penyimpanan data user baru (POST)
    public function store()
    {
        // ================= VALIDASI =================
        // Memanggil service validation bawaan CodeIgniter 4
        $validation = \Config\Services::validation();

        // Menetapkan aturan pengecekan input form
        $validation->setRules([
            'nama'     => 'required', // Input 'nama' tidak boleh kosong
            'email'    => 'required|valid_email', // Input 'email' harus format email asli
            'username' => 'required|is_unique[users.username]', // Username harus unik (belum ada di tabel users)
            'password' => 'required|min_length[4]', // Password minimal harus 4 karakter
            'role'     => 'required', // Pilihan role wajib dipilih
        ]);

        // Jika data yang dikirim tidak memenuhi aturan di atas
        if (!$validation->withRequest($this->request)->run()) {
            // Kembali ke halaman form sebelumnya dengan membawa pesan error
            return redirect()->back()->with('error', implode('<br>', $validation->getErrors()));
        }

        // ================= UPLOAD FOTO =================
        // Menangkap file yang diunggah dari input form name="foto"
        $foto = $this->request->getFile('foto');

        // Menyiapkan variabel nama foto dengan nilai awal null (kosong)
        $namaFoto = null;

        // Cek jika file ada, valid, dan belum pernah dipindahkan sebelumnya
        if ($foto && $foto->isValid() && !$foto->hasMoved()) {
            // Membuat nama file acak yang unik agar tidak bentrok di server
            $namaFoto = $foto->getRandomName();

            // Memindahkan file foto ke folder fisik: /public/uploads/users/
            $foto->move(FCPATH . 'uploads/users', $namaFoto);
        }

        // ================= SIMPAN DATA =================
        // Menjalankan perintah insert ke database menggunakan model
        $this->users->save([
            'nama'     => $this->request->getPost('nama'), 
            'email'    => $this->request->getPost('email'),
            'username' => $this->request->getPost('username'),
            // Mengamankan password menggunakan algoritma hash bawaan PHP
            'password' => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
            'role'     => $this->request->getPost('role'),
            'foto'     => $namaFoto // Menyimpan nama file foto yang sudah di-generate tadi
        ]);

        // Berpindah ke halaman login dengan pesan sukses (flash message)
        return redirect()->to('/login')->with('success', 'User berhasil ditambahkan!');
    }

    // Method untuk menampilkan daftar user dengan fitur pencarian dan pembagian halaman
    public function index()
    {
        // Menangkap data pencarian dan filter role dari URL (parameter GET)
        $keyword = $this->request->getGet('keyword');
        $role    = $this->request->getGet('role');

        // Menggunakan instance model sebagai query builder
        $builder = $this->users;

        // Logic Filter Kata Kunci (mencari di kolom Nama, Username, atau Email)
        if ($keyword) {
            $builder->groupStart() // Mulai pengelompokan query WHERE ( ... )
                    ->like('nama', $keyword)
                    ->orLike('username', $keyword)
                    ->orLike('email', $keyword)
                    ->groupEnd(); // Akhiri pengelompokan query
        }

        // Logic Filter Jabatan/Role jika dipilih
        if ($role) {
            $builder->where('role', $role);
        }

        // Mengambil data user, diurutkan dari yang terbaru, dibatasi 10 data per halaman
        $data['users'] = $builder->orderBy('id', 'DESC')->paginate(10, 'default');
        // Menyiapkan navigasi halaman (pagination link) untuk ditampilkan di view
        $data['pager'] = $this->users->pager;

        // Mengirim data ke view index user
        return view('users/index', $data);
    }

    // Method untuk menampilkan form edit user tertentu berdasarkan ID
    public function edit($id)
    {
        // Mengambil satu data user dari tabel berdasarkan Primary Key-nya
        $data['user'] = $this->users->find($id);

        // Menampilkan view edit dengan membawa data user tersebut
        return view('users/edit', $data);
    }

    // Method untuk memproses pembaruan data user (POST)
public function update($id)
{
    // Mengambil data user yang lama dari database
    $user = $this->users->find($id);

    // Menangkap file foto baru jika ada yang diupload
    $fotoBaru = $this->request->getFile('foto');

    // Default: tetap gunakan nama foto yang lama jika tidak ada upload baru
    $namaFoto = $user['foto'];

    // Cek jika user mengunggah foto baru yang valid
    if ($fotoBaru && $fotoBaru->isValid() && !$fotoBaru->hasMoved()) {

        // Menghapus file foto lama dari folder server agar tidak menumpuk
        if (!empty($user['foto']) && file_exists(FCPATH . 'uploads/users/' . $user['foto'])) {
            unlink(FCPATH . 'uploads/users/' . $user['foto']);
        }

        // Generate nama file unik untuk foto yang baru
        $namaFoto = $fotoBaru->getRandomName();

        // Pindahkan file foto baru ke folder penyimpanan
        $fotoBaru->move(FCPATH . 'uploads/users', $namaFoto);
    }

    // Menyiapkan array data yang akan diperbarui
    $data = [
        'nama'     => $this->request->getPost('nama'),
        'email'    => $this->request->getPost('email'),
        'username' => $this->request->getPost('username'),
        'role'     => $this->request->getPost('role'),
        'foto'     => $namaFoto
    ];

    // Jika kolom password di form diisi, maka update password (hash)
    if ($this->request->getPost('password') != "") {
        $data['password'] = password_hash($this->request->getPost('password'), PASSWORD_DEFAULT);
    }

    // Menjalankan perintah update di database berdasarkan ID user
    $this->users->update($id, $data);

    // --- LOGIKA UPDATE SESSION (Biar Sidebar Langsung Berubah) ---
    if (session('id') == $id) {
        session()->set([
            'nama' => $data['nama'],
            'foto' => $data['foto'],
            // Kita tidak update role di session demi keamanan, 
            // tapi nama dan foto wajib biar UI langsung seger!
        ]);
    }

    // Kembali ke daftar user dengan pesan sukses
    return redirect()->to('/users')->with('success', 'Data user berhasil diupdate!');
}

    // Method untuk menghapus user dari sistem
    public function delete($id)
    {
        // Ambil data user terlebih dahulu untuk pengecekan file
        $user = $this->users->find($id);

        // Hapus file foto dari folder server jika user tersebut memiliki foto
        if ($user['foto'] && file_exists(FCPATH . 'uploads/users/' . $user['foto'])) {
            unlink(FCPATH . 'uploads/users/' . $user['foto']);
        }

        // Menghapus baris data user dari tabel database
        $this->users->delete($id);

        // Redirect kembali ke daftar user dengan notifikasi sukses
        return redirect()->to('/users')->with('success', 'User berhasil dihapus!');
    }

    // ================= DETAIL USER =================
    // Method untuk melihat profil lengkap seorang user
    public function detail($id)
    {
        // Cari data user berdasarkan ID
        $user = $this->users->find($id);

        // Jika data tidak ditemukan di database
        if (!$user) {
            return redirect()->to('/users')->with('error', 'Data tidak ditemukan');
        }

        // Menampilkan view detail profil
        return view('users/detail', ['user' => $user]);
    }

    // ================= PRINT DATA =================
    // Method untuk mencetak laporan daftar user ke kertas/PDF
    public function print()
    {
        // Ambil filter yang sedang aktif agar data yang dicetak sesuai dengan yang difilter
        $keyword = $this->request->getGet('keyword');
        $role = $this->request->getGet('role');

        $builder = $this->users;

        // Terapkan filter yang sama dengan halaman index
        if ($keyword) {
            $builder = $builder->like('nama', $keyword);
        }

        if ($role) {
            $builder = $builder->where('role', $role);
        }

        // Ambil seluruh data hasil filter tanpa batasan halaman (pagination)
        $data['users'] = $builder->findAll();

        // Menampilkan view khusus cetak yang biasanya berisi window.print() di Javascript
        return view('users/print', $data);
    }

    // ================= KIRIM WHATSAPP =================
    // Method untuk mengirim detail data user melalui WhatsApp API
    public function wa($id)
    {
        // Cari data user
        $user = $this->users->find($id);

        // Jika user tidak ada
        if (!$user) {
            return redirect()->back()->with('error', 'Data tidak ditemukan');
        }

        // ================= FORMAT PESAN =================
        // Menyusun teks pesan yang akan dikirim (menggunakan \n untuk baris baru)
        $pesan = "DATA USER\n\n";
        $pesan .= "ID: " . $user['id'] . "\n";
        $pesan .= "Nama: " . $user['nama'] . "\n";
        $pesan .= "Email: " . $user['email'] . "\n";
        $pesan .= "Username: " . $user['username'] . "\n";
        $pesan .= "Role: " . ucfirst($user['role']) . "\n"; // ucfirst: mengubah huruf pertama jadi kapital

        // Mengubah teks pesan menjadi format yang aman untuk URL browser (urlencode)
        $url = "https://wa.me/6285175017991?text=" . urlencode($pesan);

        // Mengalihkan (redirect) browser langsung ke link WhatsApp tersebut
        return redirect()->to($url);
    }
}