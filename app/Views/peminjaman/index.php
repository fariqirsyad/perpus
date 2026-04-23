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
        <form action="<?= site_url('peminjaman') ?>" method="get" class="row g-2">
            <div class="col-md-6"> 
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0" style="border-radius: 10px 0 0 10px;">
                        <i class="bi bi-search text-muted"></i>
                    </span>
                    <input type="text" name="cari" class="form-control border-start-0" 
                           placeholder="Cari nama atau judul..." 
                           value="<?= htmlspecialchars(request()->getGet('cari') ?? '') ?>" 
                           style="border-radius: 0 10px 10px 0;">
                </div>
            </div>

            <div class="col-md-2">
                <select name="status" class="form-select" style="border-radius: 10px;">
                    <option value="">Semua Status</option>
                    <option value="Dipinjam" <?= request()->getGet('status') == 'Dipinjam' ? 'selected' : '' ?>>Dipinjam</option>
                    <option value="Kembali" <?= request()->getGet('status') == 'Kembali' ? 'selected' : '' ?>>Kembali</option>
                    <option value="Terlambat" <?= request()->getGet('status') == 'Terlambat' ? 'selected' : '' ?>>Terlambat</option>
                </select>
            </div>
            
            <div class="col-md-2 d-grid">
                <button type="submit" class="btn btn-dark" style="border-radius: 10px;">
                    <i class="bi bi-search me-2"></i> Cari
                </button>
            </div>

            <div class="col-md-2 d-grid">
                <a href="<?= base_url('peminjaman') ?>" class="btn btn-light border d-flex align-items-center justify-content-center" style="border-radius: 10px; color: #666;">
                    <i class="bi bi-arrow-clockwise me-2"></i> Reset
                </a>
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
                        <th class="ps-4 py-3 text-muted" style="font-size: 13px; width: 50px;">NO</th>
                        <th class="py-3 text-muted" style="font-size: 13px;">PEMINJAM & BUKU</th>
                        <th class="py-3 text-muted text-center" style="font-size: 13px;">TANGGAL PINJAM</th>
                        <th class="py-3 text-muted text-center" style="font-size: 13px;">BATAS KEMBALI</th>
                        <th class="py-3 text-muted text-center" style="font-size: 13px;">TANGGAL KEMBALI</th>
                        <th class="py-3 text-muted" style="font-size: 13px;">DENDA</th>
                        <th class="py-3 text-muted text-center" style="font-size: 13px;">STATUS</th>
                        <th class="py-3 text-center text-muted" style="font-size: 13px;">AKSI</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(empty($transaksi)): ?>
                        <tr>
                            <td colspan="8" class="text-center py-5">
                                <i class="bi bi-inboxes text-muted d-block mb-3" style="font-size: 3rem;"></i>
                                <p class="text-muted">Tidak ada data peminjaman yang ditemukan.</p>
                            </td>
                        </tr>
                    <?php endif; ?>

                    <?php 
                    $no = 1; 
                    foreach ($transaksi as $t) : 
                        // --- LOGIC HITUNG DENDA OTOMATIS ---
                        $denda_per_hari = 5000;
                        $total_denda = 0;

                        // Jika belum kembali, hitung denda berjalan (real-time)
                        if (empty($t['tgl_dikembalikan']) || $t['tgl_dikembalikan'] == '0000-00-00') {
                            $tgl_deadline = new DateTime($t['tgl_kembali']); // tgl_kembali di DB lu itu deadline kan?
                            $tgl_sekarang = new DateTime(date('Y-m-d'));

                            if ($tgl_sekarang > $tgl_deadline) {
                                $selisih = $tgl_sekarang->diff($tgl_deadline);
                                $total_denda = $selisih->days * $denda_per_hari;
                            }
                        } else {
                            // Jika sudah kembali, pakai nilai denda yang sudah tersimpan di DB
                            $total_denda = $t['denda'];
                        }
                    ?>
                        <tr>
                            <td class="ps-4 text-muted small"><?= $no++ ?></td>
                            <td>
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
                                <?php if (!empty($t['tgl_dikembalikan']) && $t['tgl_dikembalikan'] != '0000-00-00') : ?>
                                    <span class="text-success fw-bold"><?= date('d M Y', strtotime($t['tgl_dikembalikan'])) ?></span>
                                <?php else : ?>
                                    <span class="text-muted opacity-50">- belum -</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-center">
                                <?php if ($total_denda > 0) : ?>
                                    <a href="javascript:void(0)" 
                                       onclick="showDetailDenda('<?= $t['tgl_kembali'] ?>', '<?= $t['tgl_dikembalikan'] ?>', <?= $total_denda ?>)"
                                       class="badge bg-light text-danger border border-danger d-inline-block" 
                                       style="border-radius: 8px; padding: 6px 10px; cursor: pointer; text-decoration: none;">
                                        Rp <?= number_format($total_denda, 0, ',', '.') ?>
                                    </a>
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
                                <div class="d-flex justify-content-center gap-1">
                                    <?php if (session('role') == 'anggota' && $t['status'] == 'dipinjam') : ?>
                                        <a href="<?= base_url('peminjaman/ajukan_kembali/'.$t['id_pinjam']) ?>" 
                                           class="btn btn-sm btn-primary btn-kembali" 
                                           style="border-radius: 8px;">
                                            Ajukan Pengembalian
                                        </a>
                                    <?php elseif (session('role') == 'admin' && $t['status'] == 'diajukan') : ?>
                                        <a href="<?= base_url('peminjaman/konfirmasi_kembali/' . $t['id_pinjam']) ?>" 
                                           class="btn btn-sm btn-success btn-konfirmasi" 
                                           style="border-radius: 8px; font-size: 12px;">
                                            Konfirmasi Terima
                                        </a>
                                    <?php elseif ($t['status'] == 'kembali') : ?>
                                        <span class="badge bg-light text-success border border-success me-1" style="border-radius: 8px; padding: 6px 10px;">
                                            <i class="bi bi-check-circle-fill"></i> Selesai
                                        </span>
                                        <?php if (session('role') == 'admin') : ?>
                                            <a href="<?= base_url('peminjaman/hapus/' . $t['id_pinjam']) ?>" 
                                               class="btn btn-sm btn-outline-danger btn-hapus" 
                                               title="Hapus Riwayat"
                                               style="border-radius: 8px;">
                                               <i class="bi bi-trash"></i>
                                            </a>
                                        <?php endif; ?>
                                    <?php else : ?>
                                        <span class="text-muted small">-</span>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        <?php if (session()->getFlashdata('msg')) : ?>
            Swal.fire({
                icon: 'success', title: 'Berhasil!', text: '<?= session()->getFlashdata('msg') ?>',
                showConfirmButton: false, timer: 2500, customClass: { popup: 'rounded-4' }
            });
        <?php endif; ?>

        <?php if (session()->getFlashdata('error')) : ?>
            Swal.fire({
                icon: 'error', title: 'Waduh...', text: '<?= session()->getFlashdata('error') ?>',
                confirmButtonColor: '#008080', customClass: { popup: 'rounded-4' }
            });
        <?php endif; ?>
    });

    // Handle klik manual untuk semua tombol konfirmasi (event delegation biar aman)
    document.addEventListener('click', function(e) {
        const btnKembali = e.target.closest('.btn-kembali');
        const btnKonfirmasi = e.target.closest('.btn-konfirmasi');
        const btnHapus = e.target.closest('.btn-hapus');

        if (btnKembali) {
            e.preventDefault();
            Swal.fire({
                title: 'Ajukan Kembali?', text: "Buku akan diajukan untuk pengembalian.", icon: 'question',
                showCancelButton: true, confirmButtonColor: '#008080', cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, Ajukan!', customClass: { popup: 'rounded-4' }
            }).then((result) => { if (result.isConfirmed) window.location.href = btnKembali.href; });
        }

        if (btnKonfirmasi) {
            e.preventDefault();
            Swal.fire({
                title: 'Konfirmasi Terima?', text: "Apakah buku benar-benar sudah diterima?", icon: 'warning',
                showCancelButton: true, confirmButtonColor: '#198754', cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, Sudah Terima', customClass: { popup: 'rounded-4' }
            }).then((result) => { if (result.isConfirmed) window.location.href = btnKonfirmasi.href; });
        }

        if (btnHapus) {
            e.preventDefault();
            Swal.fire({
                title: 'Hapus Riwayat?', text: "Data yang dihapus nggak bisa dikembalikan lagi!", icon: 'warning',
                showCancelButton: true, confirmButtonColor: '#dc3545', cancelButtonColor: '#373d3f',
                confirmButtonText: 'Ya, Hapus Saja!', customClass: { popup: 'rounded-4' }
            }).then((result) => { if (result.isConfirmed) window.location.href = btnHapus.href; });
        }
    });

    // Fungsi Detail Denda


function showDetailDenda(tglDeadline, tglKembali, totalDenda) {
    // 1. Ambil tanggal hari ini (tanpa jam)
    const sekarang = new Date();
    const d2 = tglKembali && tglKembali !== '0000-00-00' ? new Date(tglKembali) : sekarang;
    
    // 2. Format ke YYYY-MM-DD biar murni tanggal
    const date1 = new Date(tglDeadline).setHours(0,0,0,0);
    const date2 = new Date(d2).setHours(0,0,0,0);

    let diffDays = 0;
    if (date2 > date1) {
        // Hitung selisih hari
        const timeDiff = date2 - date1;
        diffDays = Math.floor(timeDiff / (1000 * 60 * 60 * 24));
    }

    const tarif = 5000;
    // Hitung denda real-time di JS agar sinkron dengan hari yang tampil
    const dendaRealtime = diffDays * tarif;

    Swal.fire({
        title: 'Rincian Keterlambatan',
        html: `
            <div class="text-start p-2" style="font-size: 14px;">
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted">Batas Deadline:</span>
                    <span class="fw-bold text-dark">${tglDeadline}</span>
                </div>
                <hr style="border-top: 1px dashed #ddd;">
                <div class="d-flex justify-content-between mb-1">
                    <span>Lama Terlambat:</span>
                    <span class="text-danger fw-bold">${diffDays} Hari</span>
                </div>
                <div class="d-flex justify-content-between mb-1">
                    <span>Tarif Denda:</span>
                    <span>Rp ${tarif.toLocaleString('id-ID')} / hari</span>
                </div>
                <div class="mt-3 p-3 text-center rounded-3" style="background-color: #fff5f5; border: 1px solid #feb2b2;">
                    <p class="mb-1 small text-muted text-uppercase fw-bold">Total Denda</p>
                    <h2 class="mb-0 text-danger fw-bold">Rp ${dendaRealtime.toLocaleString('id-ID')}</h2>
                </div>
            </div>
        `,
        confirmButtonColor: '#2d3436',
        confirmButtonText: 'Tutup'
    });
}
</script>

<?= $this->endSection() ?>