<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<div class="mb-4">
    <h2 class="fw-bold text-dark">Katalog Koleksi Buku</h2>
    <p class="text-muted">Jelajahi koleksi literatur kami dan pinjam buku favoritmu hari ini.</p>
</div>

<div class="mb-5 overflow-auto pb-3" style="white-space: nowrap; -webkit-overflow-scrolling: touch;">
    <div class="d-inline-flex gap-2">
        <?php 
    $list_kategori = [
    'Semua', 
    'Umum', 
    'Agama', 
    'Sains & Teknologi',  
    'Sosial & Sejarah',   
    'Bahasa & Sastra',    
    'Seni & Rekreasi'     
];
        
        // Ambil kategori aktif dari URL, default 'Semua'
        $kat_aktif = request()->getVar('filter_kategori') ?? 'Semua';

        foreach ($list_kategori as $kat) : 
            $is_active = ($kat_aktif == $kat);
        ?>
            <a href="<?= base_url('peminjaman/katalog?filter_kategori=' . urlencode($kat)) ?>" 
               class="btn rounded-pill px-4 py-2 fw-bold transition-all filter-btn"
               style="<?= $is_active 
                        ? 'background-color: #008080; color: white; border: 2px solid #008080;' 
                        : 'background-color: transparent; color: #008080; border: 2px solid #008080;' ?>">
                <?= $kat ?>
            </a>
        <?php endforeach; ?>
    </div>
</div>

<div class="row g-4 m-0"> 
    <?php if (empty($buku)) : ?>
        <div class="col-12 text-center py-5">
            <i class="bi bi-search" style="font-size: 3rem; opacity: 0.2;"></i>
            <p class="mt-3 text-muted">Maaf, belum ada buku untuk kategori ini.</p>
        </div>
    <?php endif; ?>

    <?php foreach($buku as $b) : ?>
    <?php $stok = $b['stok']; ?>
    
    <div class="col-12 col-md-6 col-lg-4 mb-3"> 
        <div class="card h-100 border-0 shadow-sm card-buku <?= ($stok <= 0) ? 'buku-habis' : '' ?>" 
             style="border-radius: 25px; transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275); background: #fff; overflow: hidden;">
            
            <div class="p-3">
                <div class="img-container shadow-sm" style="height: 350px; border-radius: 20px; overflow: hidden; background: #f8f9fa;">
                    <?php if (!empty($b['cover'])) : ?>
                        <img src="<?= base_url('uploads/cover/' . $b['cover']) ?>" 
                             alt="<?= $b['judul']; ?>" 
                             class="w-100 h-100 img-buku" 
                             style="object-fit: cover; transition: transform 0.5s ease;">
                    <?php else : ?>
                        <div class="d-flex flex-column align-items-center justify-content-center h-100 text-muted">
                            <i class="bi bi-book" style="font-size: 5rem; opacity: 0.1;"></i>
                            <span class="small fw-bold">No Cover</span>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <div class="card-body pt-0 text-center d-flex flex-column">
                <div class="mb-1">
                    <span class="badge" style="font-size: 10px; background-color: #e6f2f2; color: #008080; border-radius: 8px; padding: 5px 10px; text-uppercase: uppercase;">
                        <?= (!empty($b['kategori'])) ? $b['kategori'] : 'Umum'; ?>
                    </span>
                </div>

                <h5 class="fw-bold mb-1 text-dark" style="display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; min-height: 3rem;">
                    <?= $b['judul']; ?>
                </h5>

                <p class="text-muted small mb-3" style="font-size: 11px;">
                    <i class="bi bi-qr-code"></i> <?= $b['isbn'] ?? '-'; ?> • <i class="bi bi-calendar-event"></i> <?= $b['tahun_terbit'] ?? '-'; ?>
                </p>
                
                <div class="d-flex justify-content-center align-items-center mb-3">
                    <?php 
                        if ($stok <= 0) {
                            $badge_class = "bg-danger text-white"; 
                            $status_text = "Stok Habis";
                        } elseif ($stok <= 3) {
                            $badge_class = "bg-warning text-dark"; 
                            $status_text = "Sisa: $stok unit";
                        } else {
    
                            $badge_class = "bg-success text-white"; 
                            $status_text = "Stok: $stok unit";
                        }
                    ?>
                    <span class="badge <?= $badge_class ?> p-2 px-3" style="border-radius: 10px; font-size: 12px;">
                        <i class="bi bi-box-seam me-2"></i> <?= $status_text ?>
                    </span>
                </div>

                <div class="mt-auto d-grid gap-2">
                    <form action="<?= base_url('peminjaman/pinjam_mandiri') ?>" method="post" class="form-pinjam">
                        <?= csrf_field() ?>
                        <input type="hidden" name="id_buku" value="<?= $b['id_buku']; ?>">
                        
                        <?php if ($stok <= 0) : ?>
                            <button type="button" class="btn btn-secondary w-100 py-2 fw-bold disabled" 
                                    style="border-radius: 12px; opacity: 0.6; cursor: not-allowed;">
                                <i class="bi bi-x-circle me-2"></i> Tidak Tersedia
                            </button>
                        <?php else : ?>
                            <button type="button" class="btn btn-teal w-100 py-2 fw-bold btn-submit-pinjam" 
                                    style="border-radius: 12px; background-color: #008080; color: white; border: none;">
                                <i class="bi bi-journal-plus me-2"></i> Pinjam Sekarang
                            </button>
                        <?php endif; ?>
                    </form>

                    <button type="button" class="btn btn-link btn-sm text-decoration-none text-muted fw-bold" 
                            onclick="alertDeskripsi('<?= addslashes($b['judul']) ?>', '<?= addslashes($b['deskripsi'] ?? 'Tidak ada deskripsi untuk buku ini.') ?>')">
                        <i class="bi bi-info-circle me-1"></i> Detail Buku
                    </button>
                </div>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
</div>

<style>
    /* Styling Filter Kategori */
    .filter-btn:hover {
        background-color: #008080 !important;
        color: white !important;
        transform: translateY(-3px);
    }
    .overflow-auto::-webkit-scrollbar {
        height: 5px;
    }
    .overflow-auto::-webkit-scrollbar-thumb {
        background: #008080;
        border-radius: 10px;
    }

    /* Styling Card */
    .buku-habis .img-buku { filter: grayscale(1); opacity: 0.7; }
    .card-buku:hover:not(.buku-habis) { transform: translateY(-12px); box-shadow: 0 20px 40px rgba(0,0,0,0.15) !important; }
    .card-buku:hover .img-buku { transform: scale(1.1); }
    .img-container { position: relative; }
    .img-container::after {
        content: ''; position: absolute; top: 0; left: 0; width: 100%; height: 100%;
        background: rgba(0,0,0,0); transition: background 0.3s ease;
    }
    .card-buku:hover .img-container::after { background: rgba(0,0,0,0.05); }
    .btn-teal { transition: all 0.3s ease; }
    .btn-teal:hover:not(:disabled) {
        background-color: #006666 !important;
        transform: scale(1.02);
        box-shadow: 0 5px 15px rgba(0, 128, 128, 0.3);
    }
    .transition-all { transition: all 0.3s ease; }
    @media (max-width: 576px) { .col-12 { max-width: 100% !important; } }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // 1. CEK PESAN BERHASIL (Flashdata 'msg')
        <?php if (session()->getFlashdata('msg')) : ?>
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: '<?= session()->getFlashdata('msg') ?>',
                showConfirmButton: false,
                timer: 2500,
                customClass: { popup: 'rounded-4' }
            });
        <?php endif; ?>

        // 2. CEK PESAN ERROR (Flashdata 'error')
        <?php if (session()->getFlashdata('error')) : ?>
            Swal.fire({
                icon: 'error',
                title: 'Waduh...',
                text: '<?= session()->getFlashdata('error') ?>',
                confirmButtonColor: '#008080',
                customClass: { popup: 'rounded-4' }
            });
        <?php endif; ?>

        // 3. KONFIRMASI PINJAM (Tombol Submit)
        document.querySelectorAll('.btn-submit-pinjam').forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault(); 
                const form = this.closest('.form-pinjam');
                
                Swal.fire({
                    title: 'Konfirmasi Pinjam',
                    text: "Yakin ingin meminjam buku ini?",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#008080',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, Pinjam!',
                    cancelButtonText: 'Batal',
                    customClass: { popup: 'rounded-4' }
                }).then((result) => {
                    if (result.isConfirmed) { form.submit(); }
                });
            });
        });
    });

    // 4. DESKRIPSI (Tetap bisa dipanggil dari luar)
    function alertDeskripsi(judul, deskripsi) {
        Swal.fire({
            title: judul,
            text: deskripsi,
            icon: 'info',
            confirmButtonColor: '#008080',
            confirmButtonText: 'Tutup',
            customClass: { popup: 'rounded-4' }
        });
    }
</script>



<?= $this->endSection() ?>