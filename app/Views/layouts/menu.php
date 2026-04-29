<div class="d-flex flex-column h-100 shadow-sm" style="width: 280px; min-width: 280px; background: white; border-right: 1px solid #e2e8f0;">
    
    <ul class="nav flex-column flex-grow-1 overflow-y-auto">
        
        <li class="px-4 mb-2 mt-4">
            <small class="text-uppercase" style="font-size: 10px; color: #94a3b8; letter-spacing: 1.5px; font-weight: 700;">Navigasi Utama</small>
        </li>

        <li class="nav-item">
            <a class="nav-link-teal <?= (url_is('/') ? 'active' : '') ?>" href="<?= base_url('/') ?>">
                <i class="bi bi-house-door"></i> <span>Dashboard</span>
            </a>
        </li>

        <?php if (session('role') == 'admin') : ?>
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
    <div class="dropdown">
        <div class="d-flex align-items-center mb-0 dropdown-toggle" id="profileDropdown" data-bs-toggle="dropdown" aria-expanded="false" style="cursor: pointer;">
            <?php if (session('foto')) : ?>
                <img src="<?= base_url('uploads/users/' . session('foto')) ?>" 
                     class="rounded-circle shadow-sm" 
                     style="width: 40px; height: 40px; object-fit: cover; border: 2px solid #06b6d4;" />
            <?php else : ?>
                <div class="rounded-circle d-flex align-items-center justify-content-center fw-bold shadow-sm" 
                     style="width: 40px; height: 40px; background: linear-gradient(45deg, #06b6d4, #0891b2); color: white; font-size: 14px;">
                    <?= strtoupper(substr(session('nama'), 0, 1)) ?>
                </div>
            <?php endif; ?>
            
            <div class="ms-3 overflow-hidden">
                <p class="mb-0 fw-semibold text-truncate" style="font-size: 13px; color: #1e293b;"><?= session('nama') ?></p>
                <small class="text-capitalize d-block text-muted" style="font-size: 11px;"><?= session('role') ?></small>
            </div>
        </div>

        <ul class="dropdown-menu dropdown-menu-end shadow border-0 p-2 w-100" aria-labelledby="profileDropdown" style="border-radius: 12px; margin-bottom: 10px;">
            <?php if (session()->get('role') == 'admin') : ?>
                <li>
                    <a href="<?= base_url('/backup') ?>" class="dropdown-item d-flex align-items-center gap-2 py-2" 
                       style="color: #2e7d32; font-size: 13px; font-weight: 600; border-radius: 8px;">
                        <i class="bi bi-shield-check"></i> Backup System
                    </a>
                </li>
                <li><hr class="dropdown-divider"></li>
            <?php endif; ?>

            <li>
                <a href="<?= base_url('/logout') ?>" id="btn-logout" class="dropdown-item d-flex align-items-center gap-2 py-2 text-danger" 
       style="font-size: 13px; font-weight: 600; border-radius: 8px;">
        <i class="bi bi-box-arrow-right"></i> Keluar Aplikasi
    </a>
            </li>
        </ul>
    </div>
</div>
</div>


<style>
    .dropdown-toggle::after {
        display: none !important;
    }
    .dropdown-item:hover {
        background-color: #f8fafc;
    }
</style>


<style>
    :root {
        --accent-teal: #06b6d4;
    }

    .nav-link-teal {
        color: #64748b !important;
        font-weight: 500;
        padding: 12px 20px !important;
        border-radius: 10px;
        margin: 4px 15px;
        transition: all 0.2s ease;
        display: flex;
        align-items: center;
        gap: 12px;
        text-decoration: none;
        font-size: 13.5px;
    }

    .nav-link-teal i {
        font-size: 1.1rem;
    }

    .nav-link-teal:hover {
        color: var(--accent-teal) !important;
        background: rgba(6, 182, 212, 0.08);
        transform: translateX(4px);
    }

    .nav-link-teal.active {
        color: white !important;
        background: linear-gradient(45deg, #06b6d4, #0891b2) !important;
        font-weight: 600;
        box-shadow: 0 4px 12px rgba(6, 182, 212, 0.2);
    }

    /* Menghilangkan scrollbar tapi tetap bisa scroll */
    .overflow-y-auto::-webkit-scrollbar {
        width: 4px;
    }
    .overflow-y-auto::-webkit-scrollbar-thumb {
        background: #e2e8f0;
        border-radius: 10px;
    }
</style>

<script>
// Pakai document click listener supaya lebih responsif buat elemen dinamis/dropdown
document.addEventListener('click', function (e) {
    // Cek apakah yang diklik itu id btn-logout atau elemen di dalemnya
    const btn = e.target.closest('#btn-logout');
    
    if (btn) {
        e.preventDefault(); 
        const url = btn.getAttribute('href');

        Swal.fire({
            title: 'Yakin mau keluar?',
            text: "Sesi kamu akan berakhir di sini, Bro.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#2d3436',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, Keluar!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = url;
            }
        });
    }
});
</script>