<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="mb-4">
    <h2 class="fw-bold text-dark">Dashboard</h2>
    <p class="text-muted">Selamat datang kembali, <b><?= session('nama'); ?></b>! Berikut adalah ringkasan perpustakaan hari ini.</p>
</div>

<div class="row g-4 mb-4">
    <div class="col-md-4">
        <div class="card border-0 shadow-sm" style="border-radius: 20px; background: linear-gradient(45deg, #06b6d4, #0891b2); color: white; height: 100%;">
            <div class="card-body p-4 d-flex flex-column justify-content-between">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-uppercase mb-2" style="font-size: 12px; opacity: 0.8; letter-spacing: 1px; font-weight: 500;">Total Koleksi</h6>
                        <h1 class="display-5 fw-bold mb-0"><?= $total_buku ?></h1>
                    </div>
                    <div class="bg-white bg-opacity-25 rounded-circle d-flex align-items-center justify-content-center" style="width: 75px; height: 75px;">
                        <i class="bi bi-book fs-1"></i>
                    </div>
                </div>
                <p class="mt-4 mb-0" style="font-size: 13px; opacity: 0.9;">Judul buku terdaftar</p>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card border-0 shadow-sm" style="border-radius: 20px; background: white; border: 1px solid #e2e8f0 !important; height: 100%;">
            <div class="card-body p-4 d-flex flex-column justify-content-between">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-uppercase mb-2" style="font-size: 12px; color: #64748b; letter-spacing: 1px; font-weight: 500;">Sedang Dipinjam</h6>
                        <h1 class="display-5 fw-bold mb-0 text-dark"><?= $total_pinjam ?></h1>
                    </div>
                    <div class="bg-light rounded-circle d-flex align-items-center justify-content-center text-primary" style="width: 75px; height: 75px;">
                        <i class="bi bi-arrow-repeat fs-1"></i>
                    </div>
                </div>
                <p class="mt-4 mb-0 text-muted" style="font-size: 13px;">Transaksi aktif saat ini</p>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card border-0 shadow-sm" style="border-radius: 20px; background: white; border: 1px solid #e2e8f0 !important; height: 100%;">
            <div class="card-body p-4 d-flex flex-column justify-content-between">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-uppercase mb-2" style="font-size: 12px; color: #64748b; letter-spacing: 1px; font-weight: 500;">Status Pengembalian</h6>
                        <h1 class="display-5 fw-bold mb-0 <?= ($jumlah_terlambat > 0) ? 'text-danger' : 'text-dark' ?>">
                            <?= ($jumlah_terlambat > 0) ? $jumlah_terlambat : 'Aman' ?>
                        </h1>
                    </div>
                    <div class="rounded-circle d-flex align-items-center justify-content-center" 
                         style="width: 75px; height: 75px; background-color: <?= ($jumlah_terlambat > 0) ? '#fff5f5' : '#f8fafc' ?>; flex-shrink: 0;">
                        <i class="bi <?= ($jumlah_terlambat > 0) ? 'bi-exclamation-triangle text-danger' : 'bi-shield-check text-success' ?> fs-1"></i>
                    </div>
                </div>
                <p class="mt-4 mb-0 text-muted" style="font-size: 13px;">
                    <?= ($jumlah_terlambat > 0) ? 'Buku melewati batas waktu' : 'Sistem berjalan normal' ?>
                </p>
            </div>
        </div>
    </div>
</div>

<div class="card border-0 shadow-sm p-4 mb-4" style="border-radius: 20px; background: white;">
    <div class="d-flex align-items-center gap-3">
        <div class="p-3 rounded-circle bg-info bg-opacity-10 text-info">
            <i class="bi bi-info-circle fs-4"></i>
        </div>
        <div>
            <h5 class="fw-bold mb-1">Tips Hari Ini</h5>
            <p class="text-muted mb-0">Jangan lupa untuk selalu mengecek denda buku yang telat dikembalikan setiap harinya.</p>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-8 mb-4">
        <div class="card border-0 shadow-sm h-100" style="border-radius: 20px;">
            <div class="card-header bg-white border-0 p-4 pb-0">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="fw-bold mb-0"><i class="bi bi-clock-history text-primary me-2"></i>Aktivitas Terbaru</h5>
                    <a href="<?= base_url('peminjaman') ?>" class="text-decoration-none small fw-bold text-primary">Lihat Semua</a>
                </div>
            </div>
            <div class="card-body p-4">
                <?php if (!empty($recent_transactions)) : ?>
                    <div class="table-responsive">
                        <table class="table table-borderless align-middle">
                            <thead>
                                <tr class="text-muted" style="font-size: 12px; letter-spacing: 0.5px;">
                                    <th>PEMINJAM</th>
                                    <th>BUKU</th>
                                    <th class="text-center">STATUS</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($recent_transactions as $rt) : ?>
                                    <tr>
                                        <td>
                                            <div class="fw-bold text-dark" style="font-size: 14px;"><?= $rt['nama'] ?></div>
                                            <small class="text-muted" style="font-size: 11px;"><?= date('d M Y', strtotime($rt['tgl_pinjam'])) ?></small>
                                        </td>
                                        <td style="font-size: 14px; max-width: 250px;" class="text-truncate">
                                            <?= $rt['judul'] ?>
                                        </td>
                                        <td class="text-center">
                                            <?php if ($rt['status'] == 'kembali') : ?>
                                                <span class="badge" style="background: #dcfce7; color: #15803d; padding: 8px 12px; border-radius: 10px; font-size: 11px;">Selesai</span>
                                            <?php elseif ($rt['status'] == 'diajukan') : ?>
                                                <span class="badge" style="background: #fef9c3; color: #a16207; padding: 8px 12px; border-radius: 10px; font-size: 11px;">Proses</span>
                                            <?php else : ?>
                                                <span class="badge" style="background: #dbeafe; color: #1e40af; padding: 8px 12px; border-radius: 10px; font-size: 11px;">Dipinjam</span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else : ?>
                    <div class="text-center py-5">
                        <div class="bg-light d-inline-block rounded-circle p-4 mb-3">
                            <i class="bi bi-inbox text-muted" style="font-size: 3rem;"></i>
                        </div>
                        <p class="text-muted mt-2">Belum ada aktivitas transaksi terekam.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="col-md-4 mb-4">
        <div class="card border-0 shadow-sm h-100" style="border-radius: 20px; background: white;">
            <div class="card-body p-4">
                <h5 class="fw-bold mb-4"><i class="bi bi-lightning-charge-fill text-warning me-2"></i>Aksi Cepat</h5>
                
                <div class="d-grid gap-3">
                    <?php if (session('role') == 'admin') : ?>
                        <button type="button" class="btn btn-light border-0 py-3 text-start px-3 shadow-none" style="border-radius: 15px;" data-bs-toggle="modal" data-bs-target="#modalTambahBuku">
                            <i class="bi bi-plus-circle-fill text-primary me-2"></i> Tambah Koleksi Buku
                        </button>

                        <a href="<?= base_url('peminjaman') ?>" class="btn btn-light border-0 py-3 text-start px-3 text-decoration-none" style="border-radius: 15px; color: inherit;">
                            <i class="bi bi-journal-plus text-success me-2"></i> Kelola Peminjaman
                        </a>
                    <?php else : ?>
                        <a href="<?= base_url('peminjaman/katalog') ?>" class="btn btn-white shadow-sm border-0 px-4 py-3 text-decoration-none" style="border-radius: 15px; background: #fff;">
                            <div class="text-center">
                                <i class="bi bi-search fs-4 text-info d-block mb-1"></i>
                                <span class="small fw-bold text-dark">Cari Buku</span>
                            </div>
                        </a>

                        <a href="<?= base_url('peminjaman') ?>" class="btn btn-light border-0 py-3 text-start px-3 text-decoration-none" style="border-radius: 15px; color: inherit;">
                            <i class="bi bi-clock-history text-warning me-2"></i> Riwayat Pinjaman
                        </a>

                        <button type="button" class="btn btn-light border-0 py-3 text-start px-3 w-100 shadow-none" style="border-radius: 15px;" data-bs-toggle="modal" data-bs-target="#modalKartuDigital">
                            <i class="bi bi-person-vcard-fill text-primary me-2"></i> Lihat Kartu Digital
                        </button>
                    <?php endif; ?>
                </div>

                <div class="mt-4 p-3 bantuan-box" style="border-radius: 15px; background: #f8fafc;">
                    <small class="text-uppercase fw-bold text-muted" style="font-size: 10px; letter-spacing: 1px;">Bantuan</small>
                    <a href="https://wa.me/628123456789" target="_blank" class="d-flex align-items-center mt-2 text-decoration-none">
                        <div class="bg-info-subtle p-2 rounded-circle me-3">
                            <i class="bi bi-whatsapp text-info fs-5"></i>
                        </div>
                        <div>
                            <p class="mb-0 fw-semibold text-dark" style="font-size: 13px;">Hubungi IT Support</p>
                            <small class="text-muted" style="font-size: 11px;">Tersedia via WhatsApp</small>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div> <div class="modal" id="modalTambahBuku" tabindex="-1" aria-hidden="true" data-bs-backdrop="static" style="background: rgba(0,0,0,0.5);">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 600px;">
        <div class="modal-content" style="border-radius: 20px; border: none; box-shadow: 0 10px 40px rgba(0,0,0,0.2);">
            <div class="modal-header border-0 p-4 pb-0">
                <h5 class="fw-bold text-dark m-0">Tambah Buku Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            
            <form action="<?= base_url('buku/simpan') ?>" method="post" enctype="multipart/form-data">
                <?= csrf_field() ?>
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label small fw-bold text-dark mb-1">JUDUL BUKU</label>
                        <input type="text" name="judul" class="form-control border shadow-none" placeholder="Contoh: Laskar Pelangi" required style="border-radius: 8px; padding: 10px;">
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-dark mb-1">PENULIS</label>
                            <input type="text" name="penulis" class="form-control border shadow-none" placeholder="Nama penulis" required style="border-radius: 8px; padding: 10px;">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-dark mb-1">ISBN</label>
                            <input type="text" name="isbn" class="form-control border shadow-none" placeholder="978-xxx-xxx" style="border-radius: 8px; padding: 10px;">
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-dark mb-1">KATEGORI</label>
                            <select name="kategori" class="form-select border shadow-none" style="border-radius: 8px; padding: 10px;">
                                <option value="Umum">Umum</option>
                                <option value="Agama">Agama</option>
                                <option value="Sains & Teknologi">Sains & Teknologi</option>
                                <option value="Sosial & Sejarah">Sosial & Sejarah</option>
                                <option value="Bahasa & Sastra">Bahasa & Sastra</option>
                                <option value="Seni & Rekreasi">Seni & Rekreasi</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-dark mb-1">PENERBIT</label>
                            <input type="text" name="penerbit" class="form-control border shadow-none" placeholder="Nama penerbit" style="border-radius: 8px; padding: 10px;">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-bold text-dark mb-1">DESKRIPSI</label>
                        <textarea name="deskripsi" class="form-control border shadow-none" rows="3" placeholder="Tuliskan deskripsi buku..." style="border-radius: 8px; padding: 10px; resize: none;"></textarea>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label class="form-label small fw-bold text-dark mb-1">TAHUN</label>
                            <input type="number" name="tahun" class="form-control border shadow-none" placeholder="2024" style="border-radius: 8px; padding: 10px;">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-bold text-dark mb-1">STOK</label>
                            <input type="number" name="stok" class="form-control border shadow-none" value="1" min="1" required style="border-radius: 8px; padding: 10px;">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-bold text-dark mb-1">DENDA/HARI</label>
                            <input type="number" name="denda_per_hari" class="form-control border shadow-none" value="5000" style="border-radius: 8px; padding: 10px;">
                        </div>
                    </div>

                    <div class="mb-0">
                        <label class="form-label small fw-bold text-dark mb-1">COVER BUKU</label>
                        <input type="file" name="cover" class="form-control border shadow-none" style="border-radius: 8px; padding: 8px;">
                    </div>
                </div>

                <div class="modal-footer border-0 p-4 pt-0 d-flex justify-content-end gap-2">
                    <button type="button" class="btn btn-light px-4" data-bs-dismiss="modal" style="border-radius: 8px; font-weight: 600;">Batal</button>
                    <button type="submit" class="btn btn-info text-white px-4" style="border-radius: 8px; font-weight: 600; background: #17a2b8; border: none;">Simpan Data</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="modalKartuDigital" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0" style="border-radius: 25px; background: linear-gradient(135deg, #0891b2 0%, #0ea5e9 100%); overflow: hidden;">
            <div class="modal-body p-0">
                <div class="p-4 text-white position-relative" style="min-height: 250px;">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div class="d-flex align-items-center gap-2">
                            <i class="bi bi-book-half fs-4"></i>
                            <span class="fw-bold tracking-wider" style="letter-spacing: 1px;">E-PERPUS PINTAR</span>
                        </div>
                        <i class="bi bi-wifi fs-4 opacity-50"></i>
                    </div>

                    <div class="d-flex align-items-center gap-4 mt-4">
                        <div class="bg-white p-1 rounded-circle shadow-lg">
                            <img src="https://ui-avatars.com/api/?name=<?= session('nama') ?>&background=0891b2&color=fff&size=128" class="rounded-circle" width="90" height="90" alt="Profile">
                        </div>
                        
                        <div class="text-start">
                            <h3 class="fw-bold mb-0 text-capitalize"><?= session('nama') ?></h3>
                            <p class="mb-2 opacity-75">Anggota Perpustakaan</p>
                            <div class="bg-white bg-opacity-25 px-3 py-1 rounded-pill d-inline-block">
                                <small class="fw-bold text-uppercase" style="font-size: 11px; letter-spacing: 1px;">
                                    ID: #<?= str_pad(session('id_user'), 5, '0', STR_PAD_LEFT) ?>
                                </small>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white p-4 text-center">
                    <div class="row align-items-center">
                        <div class="col-7 text-start">
                            <p class="text-muted small mb-1">Status Keanggotaan</p>
                            <h6 class="fw-bold text-success mb-0"><i class="bi bi-patch-check-fill me-1"></i> AKTIF SEUMUR HIDUP</h6>
                        </div>
                        <div class="col-5">
                            <div class="p-2 border rounded-3 d-inline-block">
                                <i class="bi bi-qr-code text-dark" style="font-size: 45px;"></i>
                            </div>
                        </div>
                    </div>
                    <button type="button" class="btn btn-light w-100 mt-3 border-0 py-2 text-muted" data-bs-dismiss="modal" style="border-radius: 12px; font-size: 13px;">
                        Tutup Kartu
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .bantuan-box {
        transition: all 0.3s ease;
        border: 1px solid transparent;
    }
    .bantuan-box:hover {
        background: #ffffff !important;
        border-color: #06b6d4;
        transform: translateY(-3px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.05);
    }
</style>

<?= $this->endSection() ?>