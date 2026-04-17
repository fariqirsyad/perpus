<ul class="nav flex-column mt-3">
    <li class="nav-item">
        <a class="nav-link" href="#">
            <b>E-PERPUS PINTAR</b><i class="bi bi-yelp"></i>
        </a>
    </li>

    <li class="nav-item">
        <a class="nav-link" href="<?= base_url('/') ?>">
            <i class="bi bi-house"></i> <span>Dashboard</span>
        </a>
    </li>

    <?php if (session('role') == 'admin') : ?>
    <li class="nav-item">
        <a class="nav-link" href="<?= base_url('buku') ?>">
            <i class="bi bi-book"></i> <span>Kelola Buku</span>
        </a>
    </li>
<?php endif; ?>

     <?php if (session()->get('role') == 'admin' || session()->get('role') == 'petugas') : ?>
        <li class="nav-item">
            <a class="nav-link" href="<?= base_url('/peminjaman') ?>">
                <i class="bi bi-book"></i> Kelola Peminjaman
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="<?= base_url('/users') ?>">
                <i class="bi bi-people"></i> Kelola Users (Anggota)
            </a>
        </li>
    <?php endif; ?>

        <?php if (session()->get('role') == 'anggota') : ?>
    <li class="nav-item">
        <a class="nav-link" href="<?= base_url('/peminjaman/katalog') ?>">
            <i class="bi bi-grid"></i> Katalog Buku
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="<?= base_url('/peminjaman') ?>">
            <i class="bi bi-journal-bookmark"></i> Pinjaman Saya
        </a>
    </li>
<?php endif; ?>

 <li class="nav-item">
        <?php $idu = session('id'); ?>
        <a class="nav-link" href="<?= base_url('users/edit/' . $idu) ?>">
            <i class="bi bi-gear"></i> Setting Profil
        </a>
    </li>

            <li class="nav-item">
        <a class="nav-link" href="<?= base_url('/logout') ?>" onclick="return confirm('Yakin ingin keluar?')">
            <i class="bi bi-box-arrow-right"></i> Log Out
        </a>
    </li>

</ul>

<div class="mt-4 p-3">
    <small>Masuk sebagai:</small><br>
    <b><?= session('nama'); ?> (<?= session('role'); ?>)</b>
    <br><br>
    <?php if (session()->get('foto')) : ?>
        <img src="<?= base_url('uploads/users/' . session()->get('foto')) ?>" class="img-thumbnail" style="height: 80px; width: 80px; object-fit: cover;" />
    <?php else : ?>
        <div style="width: 80px; height: 80px; background: #ddd; display: flex; align-items: center; justify-content: center;">No Foto</div>
    <?php endif; ?>
</div>