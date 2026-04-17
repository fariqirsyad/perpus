<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="mb-4 d-flex justify-content-between align-items-center">
    <div>
        <h2 class="fw-bold">Kelola Koleksi Buku</h2>
        <p class="text-muted">Manajemen data buku, stok, dan pengaturan denda perpustakaan.</p>
    </div>
    <button class="btn btn-teal px-4 py-2" style="border-radius: 12px;" onclick="showTambah()">
        <i class="bi bi-plus-lg me-2"></i>Tambah Buku Baru
    </button>
</div>

<div class="card border-0 shadow-sm mb-4" style="border-radius: 15px;">
    <div class="card-body p-3">
        <form action="<?= base_url('buku') ?>" method="get" class="row g-2">
            <div class="col-md-10">
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0" style="border-radius: 10px 0 0 10px;">
                        <i class="bi bi-search text-muted"></i>
                    </span>
                    <input type="text" name="cari" class="form-control border-start-0" placeholder="Cari judul buku atau nama penulis..." value="<?= request()->getGet('cari') ?>" style="border-radius: 0 10px 10px 0;">
                </div>
            </div>
            <div class="col-md-2 d-grid">
                <button type="submit" class="btn btn-dark" style="border-radius: 10px;">Cari</button>
            </div>
            <?php if(request()->getGet('cari')): ?>
                <div class="col-12 text-start">
                    <small>Menampilkan hasil pencarian: <b>"<?= request()->getGet('cari') ?>"</b> <a href="<?= base_url('buku') ?>" class="text-danger ms-2 text-decoration-none">Reset</a></small>
                </div>
            <?php endif; ?>
        </form>
    </div>
</div>

<div class="card border-0 shadow-sm" style="border-radius: 20px;">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4 py-3 text-muted" style="font-size: 13px;">COVER</th>
                        <th class="py-3 text-muted" style="font-size: 13px;">DETAIL BUKU</th>
                        <th class="py-3 text-muted" style="font-size: 13px;">PENULIS</th>
                        <th class="py-3 text-muted text-center" style="font-size: 13px;">STOK</th>
                        <th class="py-3 text-muted" style="font-size: 13px;">DENDA/HARI</th>
                        <th class="py-3 text-center text-muted" style="font-size: 13px;">AKSI</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(empty($buku)): ?>
                        <tr>
                            <td colspan="6" class="text-center py-5">
                                <i class="bi bi-book text-muted d-block mb-3" style="font-size: 3rem;"></i>
                                <p class="text-muted">Data buku tidak ditemukan.</p>
                            </td>
                        </tr>
                    <?php endif; ?>
                    
                    <?php foreach($buku as $b) : ?>
                    <tr>
                        <td class="ps-4">
                            <?php if ($b['cover']) : ?>
                                <img src="<?= base_url('uploads/cover/' . $b['cover']) ?>" class="shadow-sm" style="width: 50px; height: 70px; object-fit: cover; border-radius: 8px;">
                            <?php else : ?>
                                <div class="bg-light d-flex align-items-center justify-content-center shadow-sm" style="width: 50px; height: 70px; border-radius: 8px;">
                                    <i class="bi bi-image text-muted"></i>
                                </div>
                            <?php endif; ?>
                        </td>
                        <td>
                            <div class="fw-bold text-dark"><?= $b['judul'] ?></div>
                            <small class="text-muted">ID: #B-<?= $b['id_buku'] ?></small>
                        </td>
                        <td><span class="badge bg-light text-dark fw-normal p-2 px-3" style="border-radius: 8px;"><?= $b['penulis'] ?></span></td>
                        <td class="text-center">
                            <span class="fw-bold <?= ($b['stok'] < 3) ? 'text-danger' : 'text-dark' ?>"><?= $b['stok'] ?></span>
                        </td>
                        <td><span class="text-success fw-bold">Rp <?= number_format($b['denda_per_hari'], 0, ',', '.') ?></span></td>
                        <td class="text-center pe-4">
                            <div class="btn-group">
                                <button onclick="showEdit('<?= $b['id_buku'] ?>', '<?= addslashes($b['judul']) ?>', '<?= addslashes($b['penulis']) ?>', '<?= $b['stok'] ?>', '<?= $b['denda_per_hari'] ?>')" class="btn btn-sm btn-outline-primary" style="border-radius: 8px 0 0 8px;">
                                    <i class="bi bi-pencil-square"></i>
                                </button>
                                <a href="<?= base_url('buku/hapus/'.$b['id_buku']) ?>" onclick="return confirm('Hapus buku ini?')" class="btn btn-sm btn-outline-danger" style="border-radius: 0 8px 8px 0;">
                                    <i class="bi bi-trash"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div id="modalTambah" class="modal-backdrop" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.5); z-index: 1050;">
    <div style="position:absolute; top:50%; left:50%; transform: translate(-50%, -50%); background:white; padding:30px; border-radius: 20px; width: 450px; border: none; box-shadow: 0 10px 25px rgba(0,0,0,0.2);">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="fw-bold mb-0">Tambah Buku</h4>
            <button class="btn-close" onclick="hideTambah()"></button>
        </div>
        <form action="<?= base_url('buku/simpan') ?>" method="post" enctype="multipart/form-data">
            <div class="mb-3">
                <label class="form-label small fw-bold text-muted text-uppercase">Judul Buku</label>
                <input type="text" name="judul" class="form-control" placeholder="Masukan judul buku" required style="border-radius: 10px;">
            </div>
            <div class="mb-3">
                <label class="form-label small fw-bold text-muted text-uppercase">Penulis</label>
                <input type="text" name="penulis" class="form-control" placeholder="Nama penulis" required style="border-radius: 10px;">
            </div>
            <div class="row mb-3">
                <div class="col-6">
                    <label class="form-label small fw-bold text-muted text-uppercase">Stok</label>
                    <input type="number" name="stok" class="form-control" value="1" required style="border-radius: 10px;">
                </div>
                <div class="col-6">
                    <label class="form-label small fw-bold text-muted text-uppercase">Denda/Hari</label>
                    <input type="number" name="denda_per_hari" class="form-control" value="5000" required style="border-radius: 10px;">
                </div>
            </div>
            <div class="mb-4">
                <label class="form-label small fw-bold text-muted text-uppercase">Upload Cover</label>
                <input type="file" name="cover" class="form-control" accept="image/*" style="border-radius: 10px;">
            </div>
            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                <button type="button" class="btn btn-light px-4" onclick="hideTambah()" style="border-radius: 10px;">Batal</button>
                <button type="submit" class="btn btn-teal px-4" style="border-radius: 10px;">Simpan</button>
            </div>
        </form>
    </div>
</div>

<div id="modalEdit" class="modal-backdrop" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.5); z-index: 1050;">
    <div style="position:absolute; top:50%; left:50%; transform: translate(-50%, -50%); background:white; padding:30px; border-radius: 20px; width: 450px; border: none; box-shadow: 0 10px 25px rgba(0,0,0,0.2);">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="fw-bold mb-0">Edit Data Buku</h4>
            <button class="btn-close" onclick="hideEdit()"></button>
        </div>
        <form id="formEdit" action="" method="post" enctype="multipart/form-data">
            <div class="mb-3">
                <label class="form-label small fw-bold text-muted text-uppercase">Judul Buku</label>
                <input type="text" id="edit_judul" name="judul" class="form-control" required style="border-radius: 10px; border: 1px solid #0d6efd;">
            </div>
            <div class="mb-3">
                <label class="form-label small fw-bold text-muted text-uppercase">Penulis</label>
                <input type="text" id="edit_penulis" name="penulis" class="form-control" required style="border-radius: 10px; border: 1px solid #0d6efd;">
            </div>
            <div class="row mb-3">
                <div class="col-6">
                    <label class="form-label small fw-bold text-muted text-uppercase">Stok Tersedia</label>
                    <input type="number" id="edit_stok" name="stok" class="form-control" required style="border-radius: 10px;">
                </div>
                <div class="col-6">
                    <label class="form-label small fw-bold text-muted text-uppercase">Denda (Rp)</label>
                    <input type="number" id="edit_denda" name="denda_per_hari" class="form-control" required style="border-radius: 10px;">
                </div>
            </div>
            <div class="mb-4">
                <label class="form-label small fw-bold text-muted text-uppercase">Ganti Cover (Opsional)</label>
                <input type="file" name="cover" class="form-control" accept="image/*" style="border-radius: 10px;">
            </div>
            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                <button type="button" class="btn btn-light px-4" onclick="hideEdit()" style="border-radius: 10px;">Batal</button>
                <button type="submit" class="btn btn-primary px-4" style="border-radius: 10px;">Update Data</button>
            </div>
        </form>
    </div>
</div>

<script>
    function showTambah() { document.getElementById('modalTambah').style.display = 'block'; }
    function hideTambah() { document.getElementById('modalTambah').style.display = 'none'; }

    function showEdit(id, judul, penulis, stok, denda) {
        document.getElementById('modalEdit').style.display = 'block';
        document.getElementById('formEdit').action = "<?= base_url('buku/update') ?>/" + id;
        document.getElementById('edit_judul').value = judul;
        document.getElementById('edit_penulis').value = penulis;
        document.getElementById('edit_stok').value = stok;
        document.getElementById('edit_denda').value = denda;
    }
    function hideEdit() { document.getElementById('modalEdit').style.display = 'none'; }
</script>

<?= $this->endSection() ?>