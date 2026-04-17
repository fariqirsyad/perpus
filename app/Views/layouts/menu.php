<div class="d-flex flex-column h-100">
    <ul class="nav flex-column flex-grow-1">
        
        <li class="px-4 mb-2 mt-4">
            <small class="text-uppercase" style="font-size: 10px; color: #94a3b8; letter-spacing: 1.5px; font-weight: 700;">Navigasi Utama</small>
        </li>

        <li class="nav-item">
            <a class="nav-link-teal <?= (url_is('/') ? 'active' : '') ?>" href="<?= base_url('/') ?>">
                <i class="bi bi-house-door"></i> <span>Dashboard</span>
            </a>
        </li>

        <?php if (session('role') == 'admin' || session('role') == 'petugas') : ?>
            <li class="nav-item">
                <a class="nav-link-teal <?= (url_is('buku*') ? 'active' : '') ?>" href="<?= base_url('buku') ?>">
                    <i class="bi bi-book"></i> <span>Koleksi Buku</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link-teal <?= (url_is('peminjaman*') && !url_is('peminjaman/katalog') ? 'active' : '') ?>" href="<?= base_url('peminjaman') ?>">
                    <i class="bi bi-arrow-left-right"></i> <span>Transaksi</span>
                </a>
            </li>
        <?php endif; ?>

        <?php if (session('role') == 'admin') : ?>
            <li class="nav-item">
                <a class="nav-link-teal <?= (url_is('users*') ? 'active' : '') ?>" href="<?= base_url('/users') ?>">
                    <i class="bi bi-people"></i> <span>Manajemen User</span>
                </a>
            </li>
        <?php endif; ?>

        <?php if (session('role') == 'anggota') : ?>
            <li class="nav-item">
                <a class="nav-link-teal <?= (url_is('peminjaman/katalog') ? 'active' : '') ?>" href="<?= base_url('/peminjaman/katalog') ?>">
                    <i class="bi bi-grid"></i> <span>Katalog Buku</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link-teal <?= (url_is('peminjaman') ? 'active' : '') ?>" href="<?= base_url('/peminjaman') ?>">
                    <i class="bi bi-journal-bookmark"></i> <span>Pinjaman Saya</span>
                </a>
            </li>
        <?php endif; ?>

        <li class="nav-item">
            <a class="nav-link-teal <?= (url_is('users/edit*') ? 'active' : '') ?>" href="<?= base_url('users/edit/' . session('id')) ?>">
                <i class="bi bi-gear"></i> <span>Setting Profil</span>
            </a>
        </li>
    </ul>

    <div class="mt-auto p-4" style="border-top: 1px solid #e2e8f0; background: #fafbfc;">
        <div class="d-flex align-items-center mb-3">
            <?php if (session('foto')) : ?>
                <img src="<?= base_url('uploads/users/' . session('foto')) ?>" 
                     class="rounded-circle" 
                     style="width: 42px; height: 42px; object-fit: cover; border: 2px solid var(--accent-teal);" />
            <?php else : ?>
                <div class="rounded-circle d-flex align-items-center justify-content-center fw-bold" 
                     style="width: 42px; height: 42px; background: linear-gradient(45deg, #06b6d4, #0891b2); color: white; font-size: 16px;">
                    <?= strtoupper(substr(session('nama'), 0, 1)) ?>
                </div>
            <?php endif; ?>
            <div class="ms-3">
                <p class="mb-0 fw-semibold" style="font-size: 13px; color: var(--text-dark); line-height: 1.2;"><?= session('nama') ?></p>
                <small class="text-capitalize" style="font-size: 11px; color: var(--text-muted);"><?= session('role') ?></small>
            </div>
        </div>
        
        <a href="<?= base_url('/logout') ?>" 
           class="btn btn-outline-danger btn-sm w-100" 
           style="border-radius: 8px;"
           onclick="return confirm('Yakin ingin keluar?')">
            <i class="bi bi-box-arrow-right"></i> Keluar Aplikasi
        </a>
    </div>
</div>

<style>
    .nav-link-teal {
        color: #64748b !important; /* Text Muted */
        font-weight: 500;
        padding: 12px 24px !important;
        border-radius: 8px;
        margin: 4px 15px;
        transition: all 0.2s;
        display: flex;
        align-items: center;
        gap: 12px;
        text-decoration: none;
        font-size: 13px;
    }

    .nav-link-teal:hover {
        color: var(--accent-teal) !important;
        background: rgba(6, 182, 212, 0.05); /* Latar teal transparan */
    }

    .nav-link-teal.active {
        color: var(--accent-teal) !important;
        background: rgba(6, 182, 212, 0.08) !important;
        font-weight: 600;
        border-left: 4px solid var(--accent-teal); /* Garis teal di kiri menu aktif */
    }
</style>