<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>
<div class="container mt-2">
    <p>Ini adalah Halaman Dashboard
        <br>Selamat datang di <b>E-Perpus Pintar</b>App!
    </p>
</div>

<div class="card-body">
    <h1><?= $total_buku ?></h1>
    <p>Buku Tersedia</p>
</div>

<div class="card-body">
    <h1><?= $total_pinjam ?></h1>
    <p>Buku Sedang Dipinjam</p>
</div>

<?= $this->endSection() ?>

