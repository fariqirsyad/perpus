<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="mb-4 d-flex justify-content-between align-items-center">
    <div>
        <h2 class="fw-bold">Manajemen Users</h2>
        <p class="text-muted">Kelola hak akses pengguna, verifikasi anggota, dan monitoring akun.</p>
    </div>
    <div class="d-flex gap-2">
        <a href="<?= base_url('users/print?' . http_build_query($_GET)) ?>" target="_blank" class="btn btn-outline-dark px-4" style="border-radius: 12px;">
            <i class="bi bi-printer me-2"></i>Cetak Laporan
        </a>
    </div>
</div>

<div class="card border-0 shadow-sm mb-4" style="border-radius: 15px;">
    <div class="card-body p-3">
        <form method="get" action="" class="row g-2 align-items-center">
            <div class="col-md-5">
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0" style="border-radius: 10px 0 0 10px;">
                        <i class="bi bi-search text-muted"></i>
                    </span>
                    <input type="text" name="keyword" class="form-control border-start-0" placeholder="Cari nama, email, atau username..." value="<?= $_GET['keyword'] ?? '' ?>" style="border-radius: 0 10px 10px 0;">
                </div>
            </div>
            <div class="col-md-3">
                <select name="role" class="form-select" style="border-radius: 10px;">
                    <option value="">-- Semua Role --</option>
                    <option value="admin" <?= (($_GET['role'] ?? '') == 'admin') ? 'selected' : '' ?>>Admin</option>
                    <option value="petugas" <?= (($_GET['role'] ?? '') == 'petugas') ? 'selected' : '' ?>>Petugas</option>
                    <option value="anggota" <?= (($_GET['role'] ?? '') == 'anggota') ? 'selected' : '' ?>>Anggota</option>
                </select>
            </div>
            <div class="col-md-4 d-flex gap-2">
                <button type="submit" class="btn btn-dark px-4" style="border-radius: 10px;">
                    <i class="bi bi-funnel me-1"></i> Cari
                </button>
                <a href="<?= base_url('users') ?>" class="btn btn-outline-secondary px-3" style="border-radius: 10px;">
                    Reset
                </a>
            </div>
        </form>
    </div>
</div>

<?php if (session()->getFlashdata('success')): ?>
    <div class="alert alert-success border-0 shadow-sm mb-4" style="border-radius: 12px;">
        <i class="bi bi-check-circle-fill me-2"></i><?= session()->getFlashdata('success') ?>
    </div>
<?php endif; ?>

<div class="card border-0 shadow-sm" style="border-radius: 20px;">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4 py-3 text-muted" style="font-size: 13px; width: 50px;">NO</th>
                        <th class="py-3 text-muted" style="font-size: 13px;">PROFIL USER</th>
                        <th class="py-3 text-muted" style="font-size: 13px;">USERNAME</th>
                        <th class="py-3 text-muted" style="font-size: 13px;">ROLE</th>
                        <?php if (session()->get('role') == 'admin') : ?>
                            <th class="py-3 text-center text-muted" style="font-size: 13px;">AKSI</th>
                        <?php endif; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($users)): ?>
                        <?php $no = 1 + (10 * ($pager->getCurrentPage() - 1)); ?>
                        <?php foreach ($users as $u): ?>
                            <tr>
                                <td class="ps-4 text-muted"><?= $no++ ?></td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="me-3">
                                            <?php if ($u['foto']): ?>
                                                <img src="<?= base_url('uploads/users/' . $u['foto']) ?>" class="rounded-circle shadow-sm" width="45" height="45" style="object-fit: cover; border: 2px solid #fff;">
                                            <?php else: ?>
                                                <div class="rounded-circle bg-teal text-white d-flex align-items-center justify-content-center shadow-sm" width="45" height="45" style="width: 45px; height: 45px; font-weight: bold;">
                                                    <?= strtoupper(substr($u['nama'], 0, 1)) ?>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                        <div>
                                            <div class="fw-bold text-dark"><?= $u['nama'] ?></div>
                                            <div class="text-muted small"><?= $u['email'] ?></div>
                                        </div>
                                    </div>
                                </td>
                                <td><code class="text-primary" style="font-size: 13px;">@<?= $u['username'] ?></code></td>
                                <td>
                                    <?php 
                                        $badgeClass = 'bg-light text-dark';
                                        if($u['role'] == 'admin') $badgeClass = 'bg-danger text-white';
                                        if($u['role'] == 'petugas') $badgeClass = 'bg-info text-white';
                                        if($u['role'] == 'anggota') $badgeClass = 'bg-success text-white';
                                    ?>
                                    <span class="badge <?= $badgeClass ?>" style="border-radius: 8px; padding: 6px 12px; font-weight: 500;">
                                        <?= ucfirst($u['role']) ?>
                                    </span>
                                </td>

                                <?php if (session()->get('role') == 'admin') : ?>
                                    <td class="text-center pe-4">
                                        <div class="dropdown">
                                            <button class="btn btn-light btn-sm rounded-pill" type="button" data-bs-toggle="dropdown">
                                                <i class="bi bi-three-dots-vertical"></i>
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-end border-0 shadow-sm" style="border-radius: 12px;">
                                                <li><a class="dropdown-item" href="<?= base_url('users/detail/' . $u['id']) ?>"><i class="bi bi-eye me-2"></i>Detail</a></li>
                                                <li><a class="dropdown-item" href="<?= base_url('users/edit/' . $u['id']) ?>"><i class="bi bi-pencil me-2"></i>Edit User</a></li>
                                                <li><a class="dropdown-item text-success" href="<?= base_url('users/wa/' . $u['id']) ?>" target="_blank"><i class="bi bi-whatsapp me-2"></i>Kirim WA</a></li>
                                                <li><hr class="dropdown-divider"></li>
                                                <li><a class="dropdown-item text-danger" href="<?= base_url('users/delete/' . $u['id']) ?>" onclick="return confirm('Hapus user ini?')"><i class="bi bi-trash me-2"></i>Hapus</a></li>
                                            </ul>
                                        </div>
                                    </td>
                                <?php endif; ?>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" class="text-center py-5">
                                <i class="bi bi-people text-muted d-block mb-3" style="font-size: 3rem;"></i>
                                <p class="text-muted">Belut ada data user yang terdaftar.</p>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="mt-4 d-flex justify-content-center">
    <div class="pagination-wrapper">
        <?= $pager->links() ?>
    </div>
</div>


<?= $this->endSection() ?>