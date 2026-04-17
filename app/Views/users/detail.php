<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="mb-4">
    <h2 class="fw-bold">Profil Detail Pengguna</h2>
    <p class="text-muted">Informasi lengkap mengenai akun dan hak akses pengguna.</p>
</div>

<div class="row">
    <div class="col-md-4">
        <div class="card border-0 shadow-sm text-center p-4" style="border-radius: 20px;">
            <div class="card-body">
                <div class="mb-3">
                    <?php if ($user['foto']): ?>
                        <img src="<?= base_url('uploads/users/' . $user['foto']) ?>" class="rounded-circle img-thumbnail shadow-sm" style="width: 150px; height: 150px; object-fit: cover; border: 4px solid #fff;">
                    <?php else: ?>
                        <div class="rounded-circle bg-teal text-white d-flex align-items-center justify-content-center shadow-sm mx-auto" style="width: 150px; height: 150px; font-size: 64px; font-weight: bold;">
                            <?= strtoupper(substr($user['nama'], 0, 1)) ?>
                        </div>
                    <?php endif; ?>
                </div>
                <h4 class="fw-bold mb-1"><?= $user['nama'] ?></h4>
                <p class="text-muted small mb-3">@<?= $user['username'] ?></p>
                
                <?php 
                    $badgeClass = 'bg-light text-dark';
                    if($user['role'] == 'admin') $badgeClass = 'bg-danger text-white';
                    if($user['role'] == 'petugas') $badgeClass = 'bg-info text-white';
                    if($user['role'] == 'anggota') $badgeClass = 'bg-success text-white';
                ?>
                <span class="badge <?= $badgeClass ?> px-3 py-2" style="border-radius: 10px; font-size: 14px;">
                    <?= ucfirst($user['role']) ?>
                </span>
            </div>
        </div>
    </div>

    <div class="col-md-8">
        <div class="card border-0 shadow-sm" style="border-radius: 20px;">
            <div class="card-header bg-white border-0 pt-4 ps-4">
                <h5 class="fw-bold"><i class="bi bi-person-lines-fill me-2"></i>Informasi Akun</h5>
            </div>
            <div class="card-body p-4">
                <div class="row mb-4">
                    <div class="col-sm-4 text-muted">Nama Lengkap</div>
                    <div class="col-sm-8 fw-bold"><?= $user['nama'] ?></div>
                </div>
                <div class="row mb-4">
                    <div class="col-sm-4 text-muted">Alamat Email</div>
                    <div class="col-sm-8 fw-bold"><?= $user['email'] ?></div>
                </div>
                <div class="row mb-4">
                    <div class="col-sm-4 text-muted">ID Username</div>
                    <div class="col-sm-8 fw-bold text-primary">@<?= $user['username'] ?></div>
                </div>
                <div class="row mb-4">
                    <div class="col-sm-4 text-muted">Password</div>
                    <div class="col-sm-8 text-muted italic">******** (Tersandi)</div>
                </div>
                <div class="row mb-0">
                    <div class="col-sm-4 text-muted">Status Role</div>
                    <div class="col-sm-8 fw-bold text-uppercase" style="letter-spacing: 1px; font-size: 13px;">
                        <?= $user['role'] ?>
                    </div>
                </div>
                
                <hr class="my-4" style="opacity: 0.1;">

                <div class="d-flex gap-2">
                    <a href="<?= base_url('users') ?>" class="btn btn-light px-4" style="border-radius: 12px;">
                        <i class="bi bi-arrow-left me-2"></i>Kembali
                    </a>
                    <?php if (session()->get('role') == 'admin') : ?>
                        <a href="<?= base_url('users/edit/' . $user['id']) ?>" class="btn btn-teal px-4" style="border-radius: 12px;">
                            <i class="bi bi-pencil-square me-2"></i>Edit Profil
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>