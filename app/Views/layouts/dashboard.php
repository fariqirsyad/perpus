<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="mb-4">
    <h2 class="fw-bold">Dashboard</h2>
    <p class="text-muted">Selamat datang kembali, <b><?= session('nama'); ?></b>! Berikut adalah ringkasan perpustakaan hari ini.</p>
</div>

<div class="row g-4 mb-4">
    <div class="col-md-4">
        <div class="card border-0 shadow-sm" style="border-radius: 20px; background: linear-gradient(45deg, #06b6d4, #0891b2); color: white;">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-uppercase mb-2" style="font-size: 12px; opacity: 0.8; letter-spacing: 1px;">Total Koleksi</h6>
                        <h1 class="display-5 fw-bold mb-0"><?= $total_buku ?></h1>
                    </div>
                    <div class="bg-white bg-opacity-25 rounded-circle p-3">
                        <i class="bi bi-book fs-1"></i>
                    </div>
                </div>
                <p class="mt-3 mb-0" style="font-size: 13px; opacity: 0.9;">Judul buku terdaftar</p>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card border-0 shadow-sm" style="border-radius: 20px; background: white; border: 1px solid #e2e8f0 !important;">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-uppercase mb-2" style="font-size: 12px; color: #64748b; letter-spacing: 1px;">Sedang Dipinjam</h6>
                        <h1 class="display-5 fw-bold mb-0 text-dark"><?= $total_pinjam ?></h1>
                    </div>
                    <div class="bg-light rounded-circle p-3 text-primary">
                        <i class="bi bi-arrow-repeat fs-1"></i>
                    </div>
                </div>
                <p class="mt-3 mb-0 text-muted" style="font-size: 13px;">Transaksi aktif saat ini</p>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card border-0 shadow-sm" style="border-radius: 20px; background: white; border: 1px solid #e2e8f0 !important;">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-uppercase mb-2" style="font-size: 12px; color: #64748b; letter-spacing: 1px;">Status Sistem</h6>
                        <h1 class="display-5 fw-bold mb-0 text-success">Online</h1>
                    </div>
                    <div class="bg-light rounded-circle p-3 text-success">
                        <i class="bi bi-shield-check fs-1"></i>
                    </div>
                </div>
                <p class="mt-3 mb-0 text-muted" style="font-size: 13px;">Sistem berjalan normal</p>
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
                    <h5 class="fw-bold mb-0"><i class="bi bi-exclamation-circle text-danger me-2"></i>Perlu Perhatian</h5>
                    <span class="badge" style="background: #fee2e2; color: #ef4444; padding: 8px 15px; border-radius: 10px; font-size: 12px;">
                        <?= count($deadline_hari_ini ?? []) ?> Buku Jatuh Tempo
                    </span>
                </div>
            </div>
            <div class="card-body p-4">
                <?php if (!empty($deadline_hari_ini)) : ?>
                    <div class="table-responsive">
                        <table class="table table-borderless align-middle">
                            <thead>
                                <tr class="text-muted" style="font-size: 12px;">
                                    <th>Peminjam</th>
                                    <th>Buku</th>
                                    <th>Batas</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($deadline_hari_ini as $d) : ?>
                                    <tr>
                                        <td>
                                            <div class="fw-bold text-dark"><?= $d['nama'] ?></div>
                                            <small class="text-muted">#<?= $d['id_peminjaman'] ?></small>
                                        </td>
                                        <td><?= $d['judul'] ?></td>
                                        <td><span class="text-danger fw-bold"><?= $d['batas_kembali'] ?></span></td>
                                        <td><a href="<?= base_url('peminjaman') ?>" class="btn btn-sm btn-light border">Detail</a></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else : ?>
                    <div class="text-center py-5">
                        <i class="bi bi-check2-circle text-success" style="font-size: 3rem;"></i>
                        <p class="text-muted mt-2">Semua aman! Tidak ada buku yang jatuh tempo hari ini.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

   
                
                <hr class="my-4" style="opacity: 0.1;">
                
                <div class="p-3 rounded-4 bg-light">
                    <small class="text-muted d-block mb-2 fw-bold">Bantuan Cepat</small>
                    <div class="d-flex align-items-center">
                        <i class="bi bi-question-circle fs-5 me-2 text-info"></i>
                        <small class="text-dark">Ada kendala? Hubungi tim IT support kami.</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalTambahBuku" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius: 20px; border: none;">
            <div class="modal-header border-0 p-4 pb-0">
                <h5 class="fw-bold"><i class="bi bi-plus-circle text-teal me-2"></i>Tambah Koleksi Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="<?= base_url('buku/simpan') ?>" method="post" enctype="multipart/form-data">
                <?= csrf_field() ?>
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label small fw-bold text-muted">Judul Buku</label>
                        <input type="text" name="judul" class="form-control" placeholder="Contoh: Laskar Pelangi" required style="border-radius: 10px;">
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold text-muted">Penulis</label>
                        <input type="text" name="penulis" class="form-control" placeholder="Contoh: Andrea Hirata" required style="border-radius: 10px;">
                    </div>
                    <div class="row">
                        <div class="col-6 mb-3">
                            <label class="form-label small fw-bold text-muted">Stok</label>
                            <input type="number" name="stok" class="form-control" value="1" min="1" required style="border-radius: 10px;">
                        </div>
                        <div class="col-6 mb-3">
                            <label class="form-label small fw-bold text-muted">Denda (Rp)</label>
                            <input type="number" name="denda_per_hari" class="form-control" value="5000" style="border-radius: 10px;">
                        </div>
                    </div>
                    <div class="mb-0">
                        <label class="form-label small fw-bold text-muted">Cover Buku</label>
                        <input type="file" name="cover" class="form-control" style="border-radius: 10px;">
                        <small class="text-muted" style="font-size: 11px;">*Format: jpg/png (Opsional)</small>
                    </div>
                </div>
                <div class="modal-footer border-0 p-4 pt-0">
                    <button type="button" class="btn btn-light px-4" data-bs-dismiss="modal" style="border-radius: 10px;">Batal</button>
                    <button type="submit" class="btn btn-teal px-4" style="border-radius: 10px;">Simpan Buku</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?= $this->endSection() ?>