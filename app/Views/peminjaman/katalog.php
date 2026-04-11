<h2>Katalog Buku</h2>
<p>Silahkan pilih buku yang ingin Anda pinjam.</p>
<hr>

<div style="display: flex; flex-wrap: wrap; gap: 20px;">
    <?php foreach($buku as $b) : ?>
    <div style="border: 1px solid #ccc; border-radius: 8px; width: 200px; padding: 15px; text-align: center; background: #fff;">
        <div style="height: 150px; background: #eee; margin-bottom: 10px; display: flex; align-items: center; justify-content: center;">
            <i class="bi bi-book" style="font-size: 3rem; color: #aaa;"></i>
        </div>
        
        <h3 style="font-size: 1.1rem; margin: 10px 0;"><?= $b['judul']; ?></h3>
        <p style="font-size: 0.9rem; color: #666;">Tersedia: <b><?= $b['stok']; ?></b></p>
        
        <form action="<?= base_url('peminjaman/pinjam_mandiri') ?>" method="post">
            <input type="hidden" name="id_buku" value="<?= $b['id_buku']; ?>">
            <button type="submit" onclick="return confirm('Pinjam buku ini?')" 
                style="background-color: #007bff; color: white; border: none; padding: 8px 15px; border-radius: 4px; cursor: pointer;">
                Pinjam Sekarang
            </button>
        </form>
    </div>
    <?php endforeach; ?>

        <?php if (session()->getFlashdata('error')) : ?>
    <div style="color: white; background: red; padding: 10px; margin-bottom: 20px;">
        <?= session()->getFlashdata('error') ?>
    </div>
<?php endif; ?>

<?php if (session()->getFlashdata('msg')) : ?>
    <div style="color: white; background: green; padding: 10px; margin-bottom: 20px;">
        <?= session()->getFlashdata('msg') ?>
    </div>
<?php endif; ?>

</div>