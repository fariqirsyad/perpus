<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="container mt-4">
    <h2>Kelola Koleksi Buku</h2>
    
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
        <button class="btn btn-primary" onclick="showTambah()">+ Tambah Buku Baru</button>
        
        <form action="<?= base_url('buku') ?>" method="get" style="display: flex; gap: 5px;">
            <input type="text" name="cari" placeholder="Cari judul atau penulis..." value="<?= request()->getGet('cari') ?>" style="padding: 5px; width: 250px;">
            <button type="submit" class="btn btn-secondary">Cari</button>
            <?php if(request()->getGet('cari')): ?>
                <a href="<?= base_url('buku') ?>" class="btn btn-sm" style="text-decoration: none; color: gray;">Reset</a>
            <?php endif; ?>
        </form>
    </div>

    <table border="1" style="width: 100%; border-collapse: collapse;">
        <thead style="background: #eee;">
            <tr>
                <th style="padding: 10px;">Judul</th>
                <th>Penulis</th>
                <th>Stok</th>
                <th>Denda/Hari</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php if(empty($buku)): ?>
                <tr><td colspan="5" style="text-align: center; padding: 20px;">Data tidak ditemukan.</td></tr>
            <?php endif; ?>
            
            <?php foreach($buku as $b) : ?>
            <tr>
                <td style="padding: 10px;"><?= $b['judul'] ?></td>
                <td><?= $b['penulis'] ?></td>
                <td style="text-align: center;"><?= $b['stok'] ?></td>
                <td>Rp <?= number_format($b['denda_per_hari'], 0, ',', '.') ?></td>
                <td style="text-align: center;">
                    <button onclick="showEdit('<?= $b['id_buku'] ?>', '<?= addslashes($b['judul']) ?>', '<?= addslashes($b['penulis']) ?>', '<?= $b['stok'] ?>', '<?= $b['denda_per_hari'] ?>')" style="color: blue; border: none; background: none; cursor: pointer;">Edit</button> | 
                    <a href="<?= base_url('buku/hapus/'.$b['id_buku']) ?>" onclick="return confirm('Hapus buku ini?')" style="color: red; text-decoration: none;">Hapus</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<div id="modalTambah" style="display:none; position:fixed; top:15%; left:35%; background:white; padding:20px; border:1px solid #ccc; box-shadow: 0px 0px 15px rgba(0,0,0,0.5); z-index: 1000; width: 400px;">
    <h3>Tambah Buku</h3>
    <form action="<?= base_url('buku/simpan') ?>" method="post">
        <input type="text" name="judul" placeholder="Judul Buku" required style="display:block; margin-bottom:10px; width:100%; padding: 8px;">
        <input type="text" name="penulis" placeholder="Penulis" required style="display:block; margin-bottom:10px; width:100%; padding: 8px;">
        <input type="number" name="stok" placeholder="Jumlah Stok" required style="display:block; margin-bottom:10px; width:100%; padding: 8px;">
        <input type="number" name="denda_per_hari" placeholder="Denda Per Hari" value="5000" required style="display:block; margin-bottom:15px; width:100%; padding: 8px;">
        <button type="submit" style="background: green; color: white; padding: 8px 15px; border: none;">Simpan</button>
        <button type="button" onclick="hideTambah()">Batal</button>
    </form>
</div>

<div id="modalEdit" style="display:none; position:fixed; top:15%; left:35%; background:white; padding:20px; border:1px solid #ccc; box-shadow: 0px 0px 15px rgba(0,0,0,0.5); z-index: 1000; width: 400px;">
    <h3>Edit Buku</h3>
    <form id="formEdit" action="" method="post">
        <input type="text" id="edit_judul" name="judul" required style="display:block; margin-bottom:10px; width:100%; padding: 8px;">
        <input type="text" id="edit_penulis" name="penulis" required style="display:block; margin-bottom:10px; width:100%; padding: 8px;">
        <input type="number" id="edit_stok" name="stok" required style="display:block; margin-bottom:10px; width:100%; padding: 8px;">
        <input type="number" id="edit_denda" name="denda_per_hari" required style="display:block; margin-bottom:15px; width:100%; padding: 8px;">
        <button type="submit" style="background: blue; color: white; padding: 8px 15px; border: none;">Update Data</button>
        <button type="button" onclick="hideEdit()">Batal</button>
    </form>
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