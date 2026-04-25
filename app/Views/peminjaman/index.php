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
                    <?php 
                        $opts = ['dipinjam' => 'Dipinjam', 'diajukan' => 'Diajukan', 'kembali' => 'Kembali'];
                        foreach($opts as $val => $label): 
                    ?>
                        <option value="<?= $val ?>" <?= request()->getGet('status') == $val ? 'selected' : '' ?>><?= $label ?></option>
                    <?php endforeach; ?>
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
                        <th class="py-3 text-muted text-center" style="font-size: 13px;">TENGGAT & KEMBALI</th>
                        <th class="py-3 text-muted text-center" style="font-size: 13px;">DENDA</th>
                        <th class="py-3 text-muted text-center" style="font-size: 13px;">STATUS</th>
                        <th class="py-3 text-center text-muted" style="font-size: 13px;">AKSI</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(empty($transaksi)): ?>
                        <tr>
                            <td colspan="6" class="text-center py-5">
                                <i class="bi bi-inboxes text-muted d-block mb-3" style="font-size: 3rem;"></i>
                                <p class="text-muted">Tidak ada data peminjaman yang ditemukan.</p>
                            </td>
                        </tr>
                    <?php endif; ?>

                    <?php 
                    $no = 1; 
                    foreach ($transaksi as $t) : 
                        // Logic Denda Realtime
                        $denda_per_hari = 5000;
                        $tgl_deadline = new DateTime($t['tgl_kembali']);
                        $tgl_sekarang = new DateTime(date('Y-m-d'));
                        $denda_realtime = 0;
                        
                        if ($tgl_sekarang > $tgl_deadline && $t['status'] != 'kembali') {
                            $selisih = $tgl_sekarang->diff($tgl_deadline);
                            $denda_realtime = $selisih->days * $denda_per_hari;
                        }
                        $total_denda = ($t['denda'] > 0) ? $t['denda'] : $denda_realtime;
                    ?>
                        <tr>
                            <td class="ps-4 text-muted small"><?= $no++ ?></td>
                            <td>
                                <div class="fw-bold text-dark" style="font-size: 13px;"><?= $t['nama'] ?></div>
                                <div class="text-muted small" style="font-size: 11px;"><i class="bi bi-book me-1"></i><?= $t['judul'] ?></div>
                            </td>
                            
                            <td class="text-center">
                                <div style="font-size: 11px; line-height: 1.9;">
                                    <span class="text-muted" style="font-size: 10px;">Pinjam: <?= date('d M Y', strtotime($t['tgl_pinjam'])) ?></span><br>
                                    <span class="text-danger fw-bold">Batas: <?= date('d M Y', strtotime($t['tgl_kembali'])) ?></span><br>
                                    <span class="text-success fw-semibold">
                                        Balik: <?= (!empty($t['tgl_dikembalikan']) && $t['tgl_dikembalikan'] != '0000-00-00') ? date('d M Y', strtotime($t['tgl_dikembalikan'])) : '-' ?>
                                    </span>
                                </div>
                            </td>

                            <td class="text-center">
                                <div class="d-flex flex-column align-items-center">
                                    <?php if ($total_denda > 0) : ?>
                                    <span class="badge bg-light text-danger border border-danger mb-1" 
                                            style="border-radius: 8px; padding: 5px 8px; cursor: pointer; font-size: 11px;" 
                                            onclick="showDetailDenda('<?= $t['tgl_kembali'] ?>', '<?= ($t['status'] == 'kembali') ? $t['tgl_dikembalikan'] : date('Y-m-d') ?>', <?= $total_denda ?>)"
                                            title="Klik untuk detail denda">
                                            Rp <?= number_format($total_denda, 0, ',', '.') ?>
                                    </span>

                                    <?php 
                                    $st_color = ($t['status_bayar'] == 'lunas') ? 'bg-success' : (($t['status_bayar'] == 'proses') ? 'bg-info' : 'bg-secondary');
                                    $st_label = ($t['status_bayar'] == 'lunas') ? 'Lunas' : (($t['status_bayar'] == 'proses') ? 'Proses' : 'Belum Bayar');
                                    ?>
                                    <span class="badge <?= $st_color ?>" style="font-size: 9px; border-radius: 4px; padding: 2px 6px;">
                                        <?= $st_label ?>
                                    </span>

                                    <?php else : ?>
                                        <span class="text-muted fw-bold">-</span>
                                    <?php endif; ?>
                                </div>
                            </td>

                            <td class="text-center">
                                <?php if ($t['status'] == 'dipinjam') : ?>
                                    <span class="badge" style="background: #cfe2ff; color: #084298; border-radius: 8px; padding: 6px 12px; font-size: 11px;">Sedang Dipinjam</span>
                                <?php elseif ($t['status'] == 'diajukan') : ?>
                                    <span class="badge" style="background: #fff3cd; color: #856404; border-radius: 8px; padding: 6px 12px; font-size: 11px;">Menunggu...</span>
                                <?php else : ?>
                                    <span class="badge" style="background: #d1e7dd; color: #0f5132; border-radius: 8px; padding: 6px 12px; font-size: 11px;">Sudah Kembali</span>
                                <?php endif; ?>
                            </td>

                            <td class="text-center pe-4">
                                <div class="d-flex justify-content-center gap-1">
                                    <?php if (session('role') == 'anggota') : ?>
    <?php if ($t['status'] == 'dipinjam') : ?>
        <?php if ($total_denda > 0) : ?>
            <button onclick="modalBayar(<?= $t['id_pinjam'] ?>, <?= $total_denda ?>)" class="btn btn-sm btn-primary" style="border-radius: 8px; font-size: 11px;">Ajukan</button>
        <?php else : ?>
            <a href="javascript:void(0)" 
               onclick="konfirmasiKembali('<?= base_url('peminjaman/bayar_dan_ajukan/' . $t['id_pinjam']) ?>')" 
               class="btn btn-sm btn-success" 
               style="border-radius: 8px; font-size: 11px;">
               Kembalikan
            </a>
        <?php endif; ?>
    <?php endif; ?>


                                    <?php elseif (session('role') == 'admin') : ?>
                                        <?php if ($t['status'] == 'diajukan') : ?>
                                            <?php if(!empty($t['bukti_bayar'])): ?>
                                                <a href="<?= base_url('uploads/bukti_bayar/'.$t['bukti_bayar']) ?>" target="_blank" class="btn btn-sm btn-info text-white" style="border-radius: 8px;"><i class="bi bi-eye"></i></a>
                                            <?php endif; ?>
                                            <a href="<?= base_url('peminjaman/konfirmasi_kembali/' . $t['id_pinjam']) ?>" class="btn btn-sm btn-success btn-konfirmasi" style="border-radius: 8px; font-size: 11px;">Konfirmasi</a>
                                        <?php elseif ($t['status'] == 'kembali') : ?>
                                            <span class="badge bg-light text-success border border-success me-1" style="border-radius: 8px; padding: 6px 10px;"><i class="bi bi-check-circle-fill"></i></span>
                                            <a href="<?= base_url('peminjaman/hapus/' . $t['id_pinjam']) ?>" class="btn btn-sm btn-outline-danger btn-hapus" style="border-radius: 8px;"><i class="bi bi-trash"></i></a>
                                        <?php endif; ?>
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

<div class="modal fade" id="modalBayar" tabindex="-1" style="z-index: 9999;">
    <div class="modal-dialog modal-dialog-centered">
        <form action="" id="formBayar" method="post" enctype="multipart/form-data" class="w-100">
            <div class="modal-content" style="border-radius: 24px; border: none; box-shadow: 0 10px 40px rgba(0,0,0,0.1);">
                <div class="modal-header border-0 pt-4 px-4 d-flex flex-column align-items-center">
                    <h5 class="fw-bold mb-0" style="color: #2d3436;">Pengembalian & Denda</h5>
                    <button type="button" class="btn-close position-absolute" data-bs-dismiss="modal" style="right: 25px; top: 25px;"></button>
                </div>

                <div class="modal-body px-4 pb-4">
                    <div class="p-4 mb-4 rounded-4 text-center" style="background: #fff5f5; border: 1.5px solid #feb2b2;">
                        <p class="mb-1 small text-muted text-uppercase fw-bold" style="letter-spacing: 1px;">Tagihan Denda</p>
                        <h2 id="infoDenda" class="mb-0 text-danger fw-bold" style="font-size: 2rem;">Rp 0</h2>
                    </div>

                    <div class="mb-4">
                        <label class="form-label small fw-bold text-dark mb-3 d-block text-center">Pilih Metode Pembayaran:</label>
                        <div class="d-flex justify-content-center gap-4 mb-2">
                            <div class="form-check custom-radio">
                                <input class="form-check-input" type="radio" name="metode_bayar" id="bayarCash" value="cash" checked onclick="toggleUpload(false)">
                                <label class="form-check-label fw-semibold" for="bayarCash" style="cursor:pointer;">Cash</label>
                            </div>
                            <div class="form-check custom-radio">
                                <input class="form-check-input" type="radio" name="metode_bayar" id="bayarTF" value="tf" onclick="toggleUpload(true)">
                                <label class="form-check-label fw-semibold" for="bayarTF" style="cursor:pointer;">Transfer/DANA</label>
                            </div>
                        </div>
                    </div>

                    <div id="sectionUpload" style="display: none;">
                        <div class="row g-3 mb-4">
                            <div class="col-6">
                                <div class="p-3 border rounded-4 bg-light text-center h-100 d-flex flex-column justify-content-center align-items-center" 
                                     style="cursor: pointer; transition: 0.3s; border-color: #e2e8f0 !important;" 
                                     onclick="copyRekening('12345678', 'BCA')">
                                    <img src="https://upload.wikimedia.org/wikipedia/commons/5/5c/Bank_Central_Asia.svg" height="18" class="mb-2">
                                    <span class="fw-bold text-dark d-block" style="font-size: 14px;">12345678</span>
                                    <small class="text-muted" style="font-size: 10px;">A/N PERPUSKU</small>
                                    <span class="badge bg-primary-subtle text-primary mt-2" style="font-size: 9px; border-radius: 6px;">SALIN NOREK</span>
                                </div>
                            </div>
                            <div class="col-6">
                                <a href="https://link.dana.id/send-money/081234567890" target="_blank" class="text-decoration-none">
                                    <div class="p-3 border rounded-4 bg-light text-center h-100 d-flex flex-column justify-content-center align-items-center" 
                                         style="transition: 0.3s; border-color: #e2e8f0 !important;">
                                        <img src="https://upload.wikimedia.org/wikipedia/commons/7/72/Logo_dana_blue.svg" height="18" class="mb-2">
                                        <span class="fw-bold text-dark d-block" style="font-size: 14px;">0812-3456-7890</span>
                                        <small class="text-muted" style="font-size: 10px;">A/N PERPUSKU</small>
                                        <span class="badge bg-info-subtle text-info mt-2" style="font-size: 9px; border-radius: 6px;">BUKA DANA</span>
                                    </div>
                                </a>
                            </div>
                        </div>
                        <div class="alert alert-info py-2 px-3 mb-4 d-flex align-items-center" style="font-size: 11px; border-radius: 12px;">
                            <i class="bi bi-info-circle-fill me-2 fs-6"></i> 
                            <span>Screenshot bukti bayar dan upload di bawah ini.</span>
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-bold text-dark ms-1">Bukti Pembayaran (JPG/PNG)</label>
                            <input type="file" name="bukti_pembayaran" id="inputBukti" class="form-control form-control-sm" style="border-radius: 10px; padding: 10px;">
                        </div>
                    </div>
                    
                    <p id="msgLangsung" class="small text-success fw-bold text-center mt-3">
                        <i class="bi bi-check2-circle me-1"></i> Anda bisa langsung ajukan pengembalian.
                    </p>
                </div>

                <div class="modal-footer border-0 px-4 pb-4 pt-0">
                    <button type="submit" class="btn btn-lg w-100 text-white fw-bold" style="border-radius: 12px; background: #008080; border: none; font-size: 15px; padding: 12px;">Kirim & Ajukan</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    // Inisialisasi Modal Pembayaran
    function modalBayar(id, denda) {
        const myModal = new bootstrap.Modal(document.getElementById('modalBayar'));
        document.getElementById('formBayar').action = "<?= base_url('peminjaman/bayar_dan_ajukan/') ?>/" + id;
        document.getElementById('infoDenda').innerText = "Rp " + denda.toLocaleString('id-ID');
        document.getElementById('bayarCash').checked = true;
        toggleUpload(false);
        myModal.show();
    }

    // Toggle Input File berdasarkan metode bayar
    function toggleUpload(show) {
        var section = document.getElementById('sectionUpload');
        var input = document.getElementById('inputBukti');
        var msg = document.getElementById('msgLangsung');
        section.style.display = show ? 'block' : 'none';
        input.required = show;
        msg.style.display = show ? 'none' : 'block';
    }

    // Handlers Alert & Konfirmasi
    document.addEventListener('DOMContentLoaded', function() {
        <?php if (session()->getFlashdata('msg')) : ?>
            Swal.fire({
                icon: 'success', title: 'Berhasil!', text: '<?= session()->getFlashdata('msg') ?>',
                showConfirmButton: false, timer: 2500, customClass: { popup: 'rounded-4' }
            });
        <?php endif; ?>
    });

    function konfirmasiKembali(url) {
    Swal.fire({
        title: 'Yakin ingin mengembalikan?',
        text: "Buku akan diajukan untuk pengembalian.",
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#198754', // Warna hijau sukses
        cancelButtonColor: '#d33',
        confirmButtonText: 'Ya, Kembalikan!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            // Setelah klik OK, baru dia pindah ke link tujuannya
            window.location.href = url;
        }
    })
}

    document.addEventListener('click', function(e) {
        const btnKonfirmasi = e.target.closest('.btn-konfirmasi');
        const btnHapus = e.target.closest('.btn-hapus');
        
        if (btnKonfirmasi) {
            e.preventDefault();
            Swal.fire({
                title: 'Konfirmasi Terima?', text: "Apakah buku sudah benar-benar dikembalikan?", icon: 'warning',
                showCancelButton: true, confirmButtonColor: '#198754', cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, Selesai!', customClass: { popup: 'rounded-4' }
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

    // Detail Perhitungan Denda
    function showDetailDenda(tglDeadline, tglKembali, totalDenda) {
        const sekarang = new Date();
        const d2 = tglKembali && tglKembali !== '0000-00-00' ? new Date(tglKembali) : sekarang;
        const date1 = new Date(tglDeadline).setHours(0,0,0,0);
        const date2 = new Date(d2).setHours(0,0,0,0);
        
        let diffDays = 0;
        if (date2 > date1) {
            diffDays = Math.floor((date2 - date1) / (1000 * 60 * 60 * 24));
        }
        
        Swal.fire({
            title: 'Rincian Keterlambatan',
            html: `<div class="text-start p-2" style="font-size: 14px;">
                    <div class="d-flex justify-content-between mb-2"><span class="text-muted">Batas Deadline:</span><span class="fw-bold text-dark">${tglDeadline}</span></div>
                    <hr style="border-top: 1px dashed #ddd;">
                    <div class="d-flex justify-content-between mb-1"><span>Lama Terlambat:</span><span class="text-danger fw-bold">${diffDays} Hari</span></div>
                    <div class="d-flex justify-content-between mb-1"><span>Tarif Denda:</span><span>Rp 5.000 / hari</span></div>
                    <div class="mt-3 p-3 text-center rounded-3" style="background-color: #fff5f5; border: 1px solid #feb2b2;">
                        <p class="mb-1 small text-muted text-uppercase fw-bold">Total Denda</p>
                        <h2 class="mb-0 text-danger fw-bold">Rp ${totalDenda.toLocaleString('id-ID')}</h2>
                    </div>
                   </div>`,
            confirmButtonColor: '#2d3436', confirmButtonText: 'Tutup'
        });
    }

    // Salin No Rekening
    function copyRekening(nomor, bank) {
        navigator.clipboard.writeText(nomor).then(() => {
            Swal.fire({
                icon: 'success', title: 'Berhasil Salin!',
                text: `Nomor Rekening ${bank} (${nomor}) sudah disalin.`,
                showConfirmButton: false, timer: 1500, position: 'center', backdrop: false, 
                didOpen: () => { Swal.getContainer().style.zIndex = '999999'; }
            });
        });
    }
</script>

<style>
    .pagination-wrapper ul { 
        margin: 0; padding: 0; display: flex; list-style: none; gap: 5px; 
    }
    .pagination-wrapper li a, .pagination-wrapper li span {
        display: inline-block;
        padding: 8px 16px; 
        border-radius: 50px; 
        color: #008080; 
        text-decoration: none; 
        font-weight: bold;
        transition: all 0.3s;
    }
    .pagination-wrapper li.active span { 
        background-color: #008080; 
        color: white !important; 
    }
    .pagination-wrapper li a:hover {
        background-color: #e6f2f2;
    }
</style>

<div class="mt-5 d-flex justify-content-center">
    <div class="pagination-wrapper shadow-sm p-2 bg-white" style="border-radius: 50px;">
        <?= $pager->links('transaksi', 'default_full') ?>
    </div>
</div>

<?= $this->endSection() ?>