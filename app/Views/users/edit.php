<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="mb-4">
    <h2 class="fw-bold">Edit Data Pengguna</h2>
    <p class="text-muted">Perbarui informasi akun, hak akses, atau foto profil user.</p>
</div>

<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card border-0 shadow-sm" style="border-radius: 20px;">
            <div class="card-body p-4 p-md-5">
                <form action="<?= base_url('users/update/' . $user['id']) ?>" method="post" enctype="multipart/form-data">
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label small fw-bold text-muted text-uppercase">Nama Lengkap</label>
                            <input type="text" name="nama" class="form-control" value="<?= $user['nama'] ?>" required style="border-radius: 10px; padding: 10px;">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label small fw-bold text-muted text-uppercase">Email</label>
                            <input type="email" name="email" class="form-control" value="<?= $user['email'] ?>" required style="border-radius: 10px; padding: 10px;">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label small fw-bold text-muted text-uppercase">Username</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0" style="border-radius: 10px 0 0 10px;">@</span>
                                <input type="text" name="username" class="form-control border-start-0" value="<?= $user['username'] ?>" required style="border-radius: 0 10px 10px 0; padding: 10px;">
                            </div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label small fw-bold text-muted text-uppercase">Hak Akses (Role)</label>
                            <?php 
                                // Cek apakah yang sedang diedit adalah akun diri sendiri
                                $isSelf = (session('id') == $user['id']); 
                            ?>
                            <select name="role" class="form-select" style="border-radius: 10px; padding: 10px;" <?= $isSelf ? 'disabled' : '' ?>>
                                <option value="admin" <?= $user['role'] == 'admin' ? 'selected' : '' ?>>Admin</option>
                                <option value="anggota" <?= $user['role'] == 'anggota' ? 'selected' : '' ?>>Anggota</option>
                            </select>
                            
                            <?php if ($isSelf) : ?>
                                <input type="hidden" name="role" value="<?= $user['role'] ?>">
                                <div class="form-text text-danger" style="font-size: 11px;">
                                    <i class="bi bi-info-circle me-1"></i> Anda tidak dapat mengubah role Anda sendiri.
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label small fw-bold text-muted text-uppercase">Password Baru</label>
                        <input type="password" name="password" class="form-control" placeholder="Biarkan kosong jika tidak ingin mengubah password" style="border-radius: 10px; padding: 10px;">
                        <div class="form-text text-warning" style="font-size: 11px;">
                            <i class="bi bi-exclamation-triangle-fill me-1"></i> Isi hanya jika ingin mengganti password lama.
                        </div>
                    </div>

                    <div class="card bg-light border-0 mb-4" style="border-radius: 15px;">
                        <div class="card-body d-flex align-items-center">
                            <div class="me-4 position-relative">
                                <?php if ($user['foto']): ?>
                                    <img src="<?= base_url('uploads/users/' . $user['foto']) ?>" class="rounded shadow-sm" width="80" height="80" style="object-fit: cover;">
                                <?php else: ?>
                                    <div class="bg-secondary text-white rounded d-flex align-items-center justify-content-center shadow-sm" style="width: 80px; height: 80px; font-size: 30px;">
                                        <i class="bi bi-person"></i>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <div class="flex-grow-1">
                                <label class="form-label small fw-bold text-muted text-uppercase">Ganti Foto Profil</label>
                                <input type="file" name="foto" class="form-control" style="border-radius: 8px;">
                                <small class="text-muted mt-1 d-block" style="font-size: 11px;">Format: JPG, PNG, atau JPEG (Maks. 2MB)</small>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex gap-2 justify-content-end">
                        <a href="<?= base_url('users') ?>" class="btn btn-light px-4" style="border-radius: 10px;">Batal</a>
                        <button type="submit" class="btn btn-teal px-4 fw-bold" style="border-radius: 10px;">
                            <i class="bi bi-save me-2"></i>Update Data User
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>