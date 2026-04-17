<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<div class="mb-4">
    <h2 class="fw-bold text-dark">Katalog Koleksi Buku</h2>
    <p class="text-muted">Jelajahi koleksi literatur kami dan pinjam buku favoritmu hari ini.</p>
</div>

<div class="row g-4 m-0"> 
    <?php foreach($buku as $b) : ?>
    
    <div class="col-12 col-md-6 col-lg-4 mb-3"> 
        <div class="card h-100 border-0 shadow-sm card-buku" style="border-radius: 25px; transition: all 0.3s ease; background: #fff;">
            
            <div class="p-3">
                <div style="height: 350px; border-radius: 20px; overflow: hidden; background: #f8f9fa;" class="shadow-sm">
                    <?php if (!empty($b['cover'])) : ?>
                        <img src="<?= base_url('uploads/cover/' . $b['cover']) ?>" 
                             alt="<?= $b['judul']; ?>" 
                             class="w-100 h-100" 
                             style="object-fit: cover;">
                    <?php else : ?>
                        <div class="d-flex flex-column align-items-center justify-content-center h-100 text-muted">
                            <i class="bi bi-book" style="font-size: 5rem; opacity: 0.1;"></i>
                            <span class="small fw-bold">No Cover</span>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <div class="card-body pt-0 text-center d-flex flex-column">
                <h5 class="fw-bold mb-2 text-dark" style="display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; min-height: 3.5rem;">
                    <?= $b['judul']; ?>
                </h5>
                
                <div class="d-flex justify-content-center align-items-center mb-3">
                    <span class="badge bg-light text-muted border p-2 px-3" style="border-radius: 10px;">
                        <i class="bi bi-box-seam me-2 text-primary"></i> Stok: <?= $b['stok']; ?> unit
                    </span>
                </div>

                <div class="mt-auto">
                    <form action="<?= base_url('peminjaman/pinjam_mandiri') ?>" method="post" class="form-pinjam">
                        <?= csrf_field() ?>
                        <input type="hidden" name="id_buku" value="<?= $b['id_buku']; ?>">
                        <button type="button" class="btn btn-teal w-100 py-3 fw-bold btn-submit-pinjam" 
                                style="border-radius: 15px; background-color: #008080; color: white; border: none;">
                            <i class="bi bi-journal-plus me-2"></i> Pinjam Sekarang
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
</div>

<style>
    .card-buku:hover {
        transform: translateY(-10px);
        box-shadow: 0 15px 30px rgba(0,0,0,0.1) !important;
    }
    .btn-teal:hover {
        background-color: #006666 !important;
        box-shadow: 0 4px 12px rgba(0, 128, 128, 0.2);
    }
    /* Responsif HP */
    @media (max-width: 576px) {
        .col-12 { max-width: 100% !important; }
    }
</style>

<script>
    // 1. Script untuk Pop-up Konfirmasi saat klik tombol
    document.querySelectorAll('.btn-submit-pinjam').forEach(button => {
        button.addEventListener('click', function(e) {
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
                borderRadius: '20px'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });

    // 2. Notifikasi Berhasil (Muncul otomatis setelah redirect dari controller)
    <?php if (session()->getFlashdata('msg')) : ?>
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: '<?= session()->getFlashdata('msg') ?>',
            showConfirmButton: false,
            timer: 2500,
            borderRadius: '20px'
        });
    <?php endif; ?>

    // 3. Notifikasi Gagal
    <?php if (session()->getFlashdata('error')) : ?>
        Swal.fire({
            icon: 'error',
            title: 'Waduh...',
            text: '<?= session()->getFlashdata('error') ?>',
            confirmButtonColor: '#008080',
            borderRadius: '20px'
        });
    <?php endif; ?>
</script>

<?= $this->endSection() ?>