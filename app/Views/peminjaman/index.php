<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="mb-4 d-flex justify-content-between align-items-center">
    <div>
        <h2 class="fw-bold">Data Peminjaman</h2>
        <p class="text-muted">Pantau status peminjaman, batas pengembalian, dan denda anggota.</p>
    </div>
</div>

<div class="card border-0 shadow-sm mb-4" style="border-radius: 15px;">
    <div class="card-body p-3">
        <form action="" method="get" class="row g-2">
            <div class="col-md-10">
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0" style="border-radius: 10px 0 0 10px;">
                        <i class="bi bi-search text-muted"></i>
                    </span>
                    <input type="text" name="cari" class="form-control border-start-0" placeholder="Cari nama peminjam atau judul buku..." value="<?= isset($_GET['cari']) ? $_GET['cari'] : '' ?>" style="border-radius: 0 10px 10px 0;">
                </div>
            </div>
            <div class="col-md-2 d-grid">
                <button type="submit" class="btn btn-dark" style="border-radius: 10px;">Cari</button>
            </div>
        </form>
    </div>
</div>

<div class="card border-0 shadow-sm" style="border-radius: 20px;">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4 py-3 text-muted" style="font-size: 13px;">PEMINJAM & BUKU</th>
                        <th class="py-3 text-muted text-center" style="font-size: 13px;">TGL PINJAM</th>
                        <th class="py-3 text-muted text-center" style="font-size: 13px;">BATAS KEMBALI</th>
                        <th class="py-3 text-muted text-center" style="font-size: 13px;">TGL KEMBALI</th>
                        <th class="py-3 text-muted" style="font-size: 13px;">DENDA</th>
                        <th class="py-3 text-muted text-center" style="font-size: 13px;">STATUS</th>
                        <th class="py-3 text-center text-muted" style="font-size: 13px;">AKSI</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(empty($transaksi)): ?>
                        <tr>
                            <td colspan="7" class="text-center py-5">
                                <i class="bi bi-inboxes text-muted d-block mb-3" style="font-size: 3rem;"></i>
                                <p class="text-muted">Tidak ada data peminjaman yang ditemukan.</p>
                            </td>
                        </tr>
                    <?php endif; ?>

                    <?php foreach ($transaksi as $t) : ?>
                        <tr>
                            <td class="ps-4">
                                <div class="fw-bold text-dark"><?= $t['nama'] ?></div>
                                <div class="text-muted small"><i class="bi bi-book me-1"></i><?= $t['judul'] ?></div>
                            </td>
                            <td class="text-center">
                                <span class="badge bg-light text-dark fw-normal"><?= date('d M Y', strtotime($t['tgl_pinjam'])) ?></span>
                            </td>
                            <td class="text-center">
                                <span class="text-danger fw-bold" style="font-size: 14px;">
                                    <?= date('d M Y', strtotime($t['tgl_kembali'])) ?>
                                </span>
                            </td>
                            <td class="text-center">
                                <?php if ($t['tgl_dikembalikan'] && $t['tgl_dikembalikan'] != '0000-00-00') : ?>
                                    <span class="text-success fw-bold"><?= date('d M Y', strtotime($t['tgl_dikembalikan'])) ?></span>
                                <?php else : ?>
                                    <span class="text-muted opacity-50">- belum -</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if ($t['denda'] > 0) : ?>
                                    <span class="badge bg-danger-soft text-danger p-2" style="background: #fee2e2; border-radius: 8px;">
                                        Rp <?= number_format($t['denda'], 0, ',', '.') ?>
                                    </span>
                                <?php else : ?>
                                    <span class="text-muted small">Rp 0</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-center">
                                <?php if ($t['status'] == 'dipinjam') : ?>
                                    <span class="badge" style="background: #cfe2ff; color: #084298; border-radius: 8px; padding: 6px 12px;">Sedang Dipinjam</span>
                                <?php elseif ($t['status'] == 'diajukan') : ?>
                                    <span class="badge" style="background: #fff3cd; color: #856404; border-radius: 8px; padding: 6px 12px;">Menunggu Konfirmasi</span>
                                <?php else : ?>
                                    <span class="badge" style="background: #d1e7dd; color: #0f5132; border-radius: 8px; padding: 6px 12px;">Sudah Kembali</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-center pe-4">
                                <?php if (session('role') == 'anggota' && $t['status'] == 'dipinjam') : ?>
                                    <a href="<?= base_url('peminjaman/ajukan_kembali/' . $t['id_pinjam']) ?>" 
                                       onclick="return confirm('Ajukan pengembalian buku ini?')" 
                                       class="btn btn-sm btn-orange text-white" style="background: #fd7e14; border-radius: 8px; font-size: 12px;">
                                       <i class="bi bi-arrow-left-right me-1"></i> Kembalikan
                                    </a>
                                
                                <?php elseif (session('role') == 'admin' && $t['status'] == 'diajukan') : ?>
                                    <a href="<?= base_url('peminjaman/konfirmasi_kembali/' . $t['id_pinjam']) ?>" 
                                       class="btn btn-sm btn-success" style="border-radius: 8px; font-size: 12px;">
                                       Konfirmasi Terima
                                    </a>
                                
                                <?php elseif ($t['status'] == 'kembali') : ?>
                                    <span class="text-success small fw-bold"><i class="bi bi-check-circle-fill"></i> Selesai</span>
                                <?php else : ?>
                                    <span class="text-muted small">-</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?= $this->endSection() ?>