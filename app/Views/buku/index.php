<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<div class="mb-4 d-flex justify-content-between align-items-center">
    <div>
        <h2 class="fw-bold">Kelola Koleksi Buku</h2>
        <p class="text-muted">Manajemen data buku, stok, dan pengaturan denda perpustakaan.</p>
    </div>
    <button class="btn btn-tambah-custom px-4 py-2 shadow-sm d-flex align-items-center" onclick="showTambah()" style="background-color: #06b6d4; color: white; border: none; border-radius: 10px; font-weight: 600;">
        <i class="bi bi-plus-lg me-2"></i> Tambah Buku Baru
    </button>
</div>

<div class="card border-0 shadow-sm mb-4" style="border-radius: 15px;">
    <div class="card-body p-3">
        <form action="<?= site_url('buku') ?>" method="get" class="row g-2">
            <div class="col-md-6"> 
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0" style="border-radius: 10px 0 0 10px;">
                        <i class="bi bi-search text-muted"></i>
                    </span>
                    <input type="text" name="cari" class="form-control border-start-0" 
                           placeholder="Cari judul, penulis..." 
                           value="<?= htmlspecialchars(request()->getGet('cari') ?? '') ?>" 
                           style="border-radius: 0 10px 10px 0;">
                </div>
            </div>

            <div class="col-md-2">
                <select name="kategori" class="form-select" style="border-radius: 10px;">
                    <?php foreach($list_kategori as $k): ?>
                        <option value="<?= $k == 'Semua' ? '' : $k ?>" <?= request()->getGet('kategori') == $k ? 'selected' : '' ?>>
                            <?= $k ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <div class="col-md-2 d-grid">
                <button type="submit" class="btn btn-dark" style="border-radius: 10px;">
                    <i class="bi bi-search me-1"></i> Cari
                </button>
            </div>

            <div class="col-md-2 d-grid">
                <a href="<?= site_url('buku') ?>" class="btn btn-light border" style="border-radius: 10px; color: #666;">
                    <i class="bi bi-arrow-clockwise me-1"></i> Reset
                </a>
            </div>
        </form>
    </div>
</div>

<div class="card border-0 shadow-sm" style="border-radius: 20px;">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0" style="font-size: 13px;">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4 py-3 text-muted">NO</th>
                        <th class="py-3 text-muted">COVER</th>
                        <th class="py-3 text-muted">ID BUKU</th>
                        <th class="py-3 text-muted">JUDUL</th>
                        <th class="py-3 text-muted text-center">STOK</th>
                        <th class="py-3 text-muted text-center">AKSI</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($buku)) : ?>
                        <?php $no = 1; foreach($buku as $b) : ?>
                        <tr>
                            <td class="ps-4 text-muted small"><?= $no++ ?></td>
                            <td>
                                <?php if ($b['cover']) : ?>
                                    <img src="<?= base_url('uploads/cover/' . $b['cover']) ?>" style="width: 45px; height: 60px; object-fit: cover; border-radius: 6px;">
                                <?php else : ?>
                                    <div class="bg-light d-flex align-items-center justify-content-center" style="width: 45px; height: 60px; border-radius: 6px;">
                                        <i class="bi bi-book text-muted"></i>
                                    </div>
                                <?php endif; ?>
                            </td>
                            <td><code class="text-primary fw-bold">#B-<?= $b['id_buku'] ?></code></td>
                            <td>
                                <div class="fw-bold text-dark" style="max-width: 250px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;" title="<?= $b['judul'] ?>">
                                    <?= $b['judul'] ?>
                                </div>
                                <div class="small text-muted"><?= $b['kategori'] ?></div>
                            </td>
                            <td class="text-center">
                                <?php 
                                    if ($b['stok'] <= 0) {
                                        $warna_stok = 'bg-danger';
                                    } elseif ($b['stok'] <= 3) {
                                        $warna_stok = 'bg-warning text-dark';
                                    } else {
                                        $warna_stok = 'bg-success';
                                    }
                                ?>
                                <span class="badge <?= $warna_stok ?>" style="border-radius: 8px; padding: 6px 12px;">
                                    <?= $b['stok'] ?> Eks
                                </span>
                            </td>
                            <td class="text-center pe-4">
                                <div class="d-flex justify-content-center gap-1">
                                    <button type="button" class="btn btn-sm btn-info text-white" 
                                            onclick='showDetail(<?= htmlspecialchars(json_encode($b), ENT_QUOTES, "UTF-8") ?>)' 
                                            style="border-radius: 8px; background: #22d3ee; border: none;">
                                        <i class="bi bi-eye"></i>
                                    </button>

                                    <button type="button" class="btn btn-sm btn-outline-primary" 
                                            onclick='showEdit(<?= htmlspecialchars(json_encode($b), ENT_QUOTES, "UTF-8") ?>)' 
                                            style="border-radius: 8px;">
                                        <i class="bi bi-pencil-square"></i>
                                    </button>

                                    <a href="<?= base_url('buku/hapus/'.$b['id_buku']) ?>" 
                                       class="btn btn-sm btn-outline-danger btn-hapus" 
                                       style="border-radius: 8px;">
                                        <i class="bi bi-trash"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <tr>
                            <td colspan="6" class="text-center py-5">
                                <div class="mb-3">
                                    <i class="bi bi-search text-muted" style="font-size: 3rem; opacity: 0.3;"></i>
                                </div>
                                <h6 class="fw-bold text-muted">Buku "<?= htmlspecialchars(request()->getGet('cari') ?? '') ?>" tidak ditemukan</h6>
                                <p class="text-muted small">Coba cari dengan kata kunci lain atau kata kunci yang lebih umum.</p>
                                <a href="<?= site_url('buku') ?>" class="btn btn-sm btn-secondary mt-2" style="border-radius: 8px;">Lihat Semua</a>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div id="modalTambah" class="modal-custom-overlay" style="display:none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.6); z-index: 2000; align-items: center; justify-content: center; backdrop-filter: blur(2px);">
    <div class="modal-custom-card" style="background: white; padding: 30px; border-radius: 15px; width: 100%; max-width: 650px; max-height: 90vh; overflow-y: auto;">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="fw-bold mb-0">Tambah Buku Baru</h4>
            <button class="btn-close" onclick="hideTambah()"></button>
        </div>
        <form action="<?= base_url('buku/simpan') ?>" method="post" enctype="multipart/form-data">
            <?= csrf_field() ?>
            <div class="row g-3">
                <div class="col-12">
                    <label class="small fw-bold">JUDUL BUKU</label>
                    <input type="text" name="judul" class="form-control" required style="border-radius: 8px;" placeholder="Contoh: Laskar Pelangi">
                </div>
                <div class="col-md-6">
                    <label class="small fw-bold">PENULIS</label>
                    <input type="text" name="penulis" class="form-control" required style="border-radius: 8px;" placeholder="Nama penulis">
                </div>
                <div class="col-md-6">
                    <label class="small fw-bold">ISBN</label>
                    <input type="text" name="isbn" class="form-control" style="border-radius: 8px;" placeholder="978-xxx-xxx">
                </div>
                <div class="col-md-6">
                    <label class="small fw-bold">KATEGORI</label>
                    <select name="kategori" class="form-select" style="border-radius: 8px;">
                        <?php foreach($list_kategori as $k): ?>
                            <?php if($k != 'Semua'): ?>
                                <option value="<?= $k ?>"><?= $k ?></option>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="small fw-bold">PENERBIT</label>
                    <input type="text" name="penerbit" class="form-control" style="border-radius: 8px;" placeholder="Nama penerbit">
                </div>
                <div class="col-12">
                    <label class="small fw-bold">DESKRIPSI BUKU</label>
                    <textarea name="deskripsi" class="form-control" rows="3" style="border-radius: 8px;" placeholder="Masukkan ringkasan atau sinopsis buku..."></textarea>
                </div>
                <div class="col-md-4">
                    <label class="small fw-bold">TAHUN TERBIT</label>
                    <input type="number" name="tahun_terbit" class="form-control" style="border-radius: 8px;" placeholder="2024">
                </div>
                <div class="col-md-4">
                    <label class="small fw-bold">STOK</label>
                    <input type="number" name="stok" class="form-control" value="1" style="border-radius: 8px;">
                </div>
                <div class="col-md-4">
                    <label class="small fw-bold">DENDA/HARI</label>
                    <input type="number" name="denda_per_hari" class="form-control" value="5000" style="border-radius: 8px;">
                </div>
                <div class="col-12">
                    <label class="small fw-bold">COVER BUKU</label>
                    <input type="file" name="cover" class="form-control" style="border-radius: 8px;">
                </div>
            </div>
            <div class="mt-4 text-end">
                <button type="button" class="btn btn-light me-2" onclick="hideTambah()">Batal</button>
                <button type="submit" class="btn btn-primary" style="background-color: #06b6d4; border: none; border-radius: 8px; font-weight: bold;">Simpan Data</button>
            </div>
        </form>
    </div>
</div>

<div id="modalEdit" class="modal-custom-overlay" style="display:none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.6); z-index: 2000; align-items: center; justify-content: center; backdrop-filter: blur(2px);">
    <div class="modal-custom-card" style="background: white; padding: 30px; border-radius: 15px; width: 100%; max-width: 650px; max-height: 90vh; overflow-y: auto;">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="fw-bold mb-0">Edit Data Buku</h4>
            <button type="button" class="btn-close" onclick="hideEdit()"></button>
        </div>
        <form id="formEdit" action="" method="post" enctype="multipart/form-data">
            <?= csrf_field() ?>
            <div class="row g-3">
                <div class="col-12">
                    <label class="small fw-bold">JUDUL BUKU</label>
                    <input type="text" id="edit_judul" name="judul" class="form-control" required style="border-radius: 8px;">
                </div>
                <div class="col-md-6">
                    <label class="small fw-bold">PENULIS</label>
                    <input type="text" id="edit_penulis" name="penulis" class="form-control" required style="border-radius: 8px;">
                </div>
                <div class="col-md-6">
                    <label class="small fw-bold">ISBN</label>
                    <input type="text" id="edit_isbn" name="isbn" class="form-control" style="border-radius: 8px;">
                </div>
                <div class="col-md-6">
                    <label class="small fw-bold">KATEGORI</label>
                    <select name="kategori" id="edit_kategori" class="form-select" style="border-radius: 8px;">
                        <?php foreach($list_kategori as $k): ?>
                            <?php if($k != 'Semua'): ?>
                                <option value="<?= $k ?>"><?= $k ?></option>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="small fw-bold">PENERBIT</label>
                    <input type="text" id="edit_penerbit" name="penerbit" class="form-control" style="border-radius: 8px;">
                </div>
                <div class="col-12">
                    <label class="small fw-bold">DESKRIPSI BUKU</label>
                    <textarea id="edit_deskripsi" name="deskripsi" class="form-control" rows="3" style="border-radius: 8px;"></textarea>
                </div>
                <div class="col-md-4">
                    <label class="small fw-bold">TAHUN TERBIT</label>
                    <input type="number" id="edit_tahun" name="tahun_terbit" class="form-control" style="border-radius: 8px;">
                </div>
                <div class="col-md-4">
                    <label class="small fw-bold">STOK</label>
                    <input type="number" id="edit_stok" name="stok" class="form-control" style="border-radius: 8px;">
                </div>
                <div class="col-md-4">
                    <label class="small fw-bold">DENDA/HARI</label>
                    <input type="number" id="edit_denda" name="denda_per_hari" class="form-control" style="border-radius: 8px;">
                </div>
                <div class="col-12">
                    <label class="small fw-bold">COVER BUKU (Kosongkan jika tidak diubah)</label>
                    <input type="file" name="cover" class="form-control" style="border-radius: 8px;">
                </div>
            </div>
            <div class="mt-4 text-end">
                <button type="button" class="btn btn-light me-2" onclick="hideEdit()">Batal</button>
                <button type="submit" class="btn btn-primary" style="background-color: #06b6d4; border: none; border-radius: 8px; font-weight: bold;">Update Data</button>
            </div>
        </form>
    </div>
</div>

<div id="modalDetail" class="modal-custom-overlay" style="display:none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.7); z-index: 3000; align-items: center; justify-content: center; backdrop-filter: blur(4px);">
    <div class="modal-custom-card shadow-lg" style="background: white; padding: 30px; border-radius: 20px; width: 95%; max-width: 650px; position: relative;">
        <button class="btn-close position-absolute" style="top: 25px; right: 25px;" onclick="hideDetail()"></button>
        <h4 class="fw-bold mb-4 text-dark"><i class="bi bi-info-circle me-2 text-primary"></i>Informasi Lengkap Buku</h4>
        <div class="row g-4 align-items-start">
            <div class="col-md-5 text-center">
                <div class="bg-light rounded-3 shadow-sm border p-2" style="width: 100%; height: 320px;">
                    <img id="det_cover" src="" class="img-fluid rounded-2" style="width: 100%; height: 100%; object-fit: cover;">
                </div>
            </div>
            <div class="col-md-7">
                <div class="table-responsive">
                    <table class="table table-sm table-borderless mb-0">
                        <tr class="border-bottom"><td class="py-2 text-muted" width="100">ID BUKU</td><td class="py-2 fw-bold text-primary">: <span id="det_id"></span></td></tr>
                        <tr class="border-bottom"><td class="py-2 text-muted">JUDUL</td><td class="py-2 fw-bold text-dark">: <span id="det_judul"></span></td></tr>
                        <tr class="border-bottom"><td class="py-2 text-muted">ISBN</td><td class="py-2">: <span id="det_isbn"></span></td></tr>
                        <tr class="border-bottom"><td class="py-2 text-muted">PENULIS</td><td class="py-2">: <span id="det_penulis"></span></td></tr>
                        <tr class="border-bottom"><td class="py-2 text-muted">PENERBIT</td><td class="py-2">: <span id="det_penerbit"></span></td></tr>
                        <tr class="border-bottom"><td class="py-2 text-muted">KATEGORI</td><td class="py-2 text-uppercase small"><span id="det_kategori" class="badge bg-light text-dark border"></span></td></tr>
                        <tr class="border-bottom"><td class="py-2 text-muted">TAHUN</td><td class="py-2">: <span id="det_tahun"></span></td></tr>
                        <tr><td class="py-2 text-muted">DENDA</td><td class="py-2 fw-bold text-danger">: <span id="det_denda"></span> <small class="text-muted fw-normal">/ hari</small></td></tr>
                    </table>
                </div>
            </div>
        </div>
        <div class="mt-4 pt-3 border-top text-end">
            <button class="btn btn-dark px-4" onclick="hideDetail()" style="border-radius: 10px;">Tutup</button>
        </div>
    </div>
</div>

<script>
    // --- MODAL TAMBAH ---
    function showTambah() { document.getElementById('modalTambah').style.display = 'flex'; }
    function hideTambah() { document.getElementById('modalTambah').style.display = 'none'; }

    // --- MODAL DETAIL ---
    function showDetail(data) {
        document.getElementById('modalDetail').style.display = 'flex';
        document.getElementById('det_id').innerText = '#B-' + data.id_buku;
        document.getElementById('det_judul').innerText = data.judul;
        document.getElementById('det_isbn').innerText = data.isbn || '-';
        document.getElementById('det_penulis').innerText = data.penulis;
        document.getElementById('det_penerbit').innerText = data.penerbit || '-';
        document.getElementById('det_kategori').innerText = data.kategori || '-';
        document.getElementById('det_tahun').innerText = data.tahun_terbit || '-';
        const denda = data.denda_per_hari ? parseInt(data.denda_per_hari).toLocaleString() : '0';
        document.getElementById('det_denda').innerText = 'Rp ' + denda;
        const coverPath = "<?= base_url('uploads/cover/') ?>/" + (data.cover || 'default.jpg');
        document.getElementById('det_cover').src = coverPath;
    }
    function hideDetail() { document.getElementById('modalDetail').style.display = 'none'; }

    // --- MODAL EDIT ---
    function showEdit(data) {
        const form = document.getElementById('formEdit');
        form.action = "<?= base_url('buku/update') ?>/" + data.id_buku;
        document.getElementById('edit_judul').value = data.judul;
        document.getElementById('edit_penulis').value = data.penulis;
        document.getElementById('edit_isbn').value = data.isbn || '';
        document.getElementById('edit_kategori').value = data.kategori;
        document.getElementById('edit_penerbit').value = data.penerbit || '';
        document.getElementById('edit_deskripsi').value = data.deskripsi || '';
        document.getElementById('edit_tahun').value = data.tahun_terbit;
        document.getElementById('edit_stok').value = data.stok;
        document.getElementById('edit_denda').value = data.denda_per_hari;
        document.getElementById('modalEdit').style.display = 'flex';
    }
    function hideEdit() { document.getElementById('modalEdit').style.display = 'none'; }

    window.onclick = function(event) {
        const mTambah = document.getElementById('modalTambah');
        const mEdit = document.getElementById('modalEdit');
        const mDetail = document.getElementById('modalDetail');
        if (event.target == mTambah) hideTambah();
        if (event.target == mEdit) hideEdit();
        if (event.target == mDetail) hideDetail();
    }

    // --- NOTIFIKASI SUKSES ---
    <?php if (session()->getFlashdata('msg')) : ?>
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: '<?= session()->getFlashdata('msg') ?>',
            showConfirmButton: false,
            timer: 2500
        });
    <?php endif; ?>

    // --- SWEETALERT HAPUS ---
    document.addEventListener('click', function (e) {
        if (e.target.closest('.btn-hapus')) {
            e.preventDefault();
            const url = e.target.closest('.btn-hapus').getAttribute('href');
            Swal.fire({
                title: 'Hapus Buku?',
                text: "Data yang dihapus nggak bisa dikembalikan!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = url;
                }
            });
        }
    });
</script>

<div class="mt-5 d-flex justify-content-center">
    <div class="pagination-wrapper shadow-sm p-2 bg-white" style="border-radius: 50px;">
        <?= $pager->links('buku_list', 'default_full') ?>
    </div>
</div>

<style>
    /* Wrapper luar biar tetep lonjong estetik */
    .pagination-wrapper {
        background-color: white;
        border-radius: 50px;
        padding: 5px 15px; /* Kasih ruang dikit di dalam area putih */
        display: inline-block;
    }

    .pagination-wrapper ul {
        display: flex !important;
        align-items: center !important;
        gap: 8px !important;
        margin: 0 !important;
        padding: 0 !important;
        list-style: none !important;
    }

    /* INI KUNCI BIAR BULAT SEMPURNA */
    .pagination-wrapper li a, 
    .pagination-wrapper li span {
        display: grid !important; /* Grid lebih stabil buat centering */
        place-items: center !important; /* Angka bener-bener di tengah */
        
        /* Kunci ukuran mati: Luas harus sama */
        width: 35px !important; 
        height: 35px !important;
        
        border-radius: 50% !important; /* Paksa jadi bulat */
        color: #008080 !important;
        text-decoration: none !important;
        font-weight: bold !important;
        font-size: 14px !important;
        
        /* Hapus semua padding/margin bawaan yang bikin lonjong */
        padding: 0 !important;
        margin: 0 !important;
        transition: all 0.2s ease;
    }

    /* Efek Pas Diklik (Mendem) */
    .pagination-wrapper li a:active {
        transform: scale(0.85); /* Agak kecil dikit pas diteken */
        background-color: #008080;
        color: white !important;
    }

    /* Warna pas Aktif */
    .pagination-wrapper li.active span {
        background-color: #008080 !important;
        color: white !important;
    }

    /* Efek Hover */
    .pagination-wrapper li a:hover {
        background-color: #e6f2f2 !important;
    }
</style>

<?= $this->endSection() ?>