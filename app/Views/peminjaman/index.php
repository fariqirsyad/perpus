<div class="container mt-4">
    <h2>Data Peminjaman Buku</h2>
    <hr>

    <div style="display: flex; justify-content: space-between; margin-bottom: 15px;">
        <div>
            <?php if (session('role') == 'admin') : ?>
                <a href="<?= base_url('peminjaman/tambah_view') ?>" class="btn-tambah" style="background: #007bff; color: white; padding: 8px 15px; text-decoration: none; border-radius: 4px;">
                    <i class="bi bi-plus"></i> Tambah Peminjaman
                </a>
            <?php endif; ?>
        </div>
        
        <form action="" method="get">
            <input type="text" name="cari" placeholder="Cari nama/buku..." value="<?= isset($_GET['cari']) ? $_GET['cari'] : '' ?>" style="padding: 6px; border: 1px solid #ccc; border-radius: 4px;">
            <button type="submit" style="padding: 6px 12px; background: #6c757d; color: white; border: none; border-radius: 4px; cursor: pointer;">Cari</button>
            <?php if(isset($_GET['cari'])): ?>
                <a href="<?= base_url('peminjaman') ?>" style="font-size: 12px; margin-left: 5px;">Reset</a>
            <?php endif; ?>
        </form>
    </div>

    <table border="1" style="width: 100%; border-collapse: collapse; text-align: left;">
        <thead style="background-color: #f8f9fa;">
            <tr>
                <th style="padding: 12px; border: 1px solid #dee2e6;">Peminjam</th>
                <th style="padding: 12px; border: 1px solid #dee2e6;">Judul Buku</th>
                <th style="padding: 12px; border: 1px solid #dee2e6;">Tgl Pinjam</th>
                <th style="padding: 12px; border: 1px solid #dee2e6;">Denda</th>
                <th style="padding: 12px; border: 1px solid #dee2e6;">Status</th>
                <th style="padding: 12px; border: 1px solid #dee2e6;">Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php if(empty($transaksi)): ?>
                <tr>
                    <td colspan="6" style="text-align: center; padding: 20px;">Tidak ada data peminjaman.</td>
                </tr>
            <?php endif; ?>

            <?php foreach ($transaksi as $t) : ?>
                <tr>
                    <td style="padding: 10px; border: 1px solid #dee2e6;"><?= $t['nama'] ?></td>
                    <td style="padding: 10px; border: 1px solid #dee2e6;"><?= $t['judul'] ?></td>
                    <td style="padding: 10px; border: 1px solid #dee2e6;"><?= $t['tgl_pinjam'] ?></td>
                    <td style="padding: 10px; border: 1px solid #dee2e6; color: red;">Rp <?= number_format($t['denda'], 0, ',', '.') ?></td>
                    <td style="padding: 10px; border: 1px solid #dee2e6;">
                        <?php if ($t['status'] == 'dipinjam') : ?>
                            <span style="background: #cfe2ff; color: #084298; padding: 2px 8px; border-radius: 10px; font-size: 12px;">Sedang Dipinjam</span>
                        <?php elseif ($t['status'] == 'diajukan') : ?>
                            <span style="background: #fff3cd; color: #856404; padding: 2px 8px; border-radius: 10px; font-size: 12px;">Menunggu Konfirmasi</span>
                        <?php else : ?>
                            <span style="background: #d1e7dd; color: #0f5132; padding: 2px 8px; border-radius: 10px; font-size: 12px;">Sudah Kembali</span>
                        <?php endif; ?>
                    </td>
                    <td style="padding: 10px; border: 1px solid #dee2e6;">
                        <?php if (session('role') == 'anggota' && $t['status'] == 'dipinjam') : ?>
                            <a href="<?= base_url('peminjaman/ajukan_kembali/' . $t['id_pinjam']) ?>" 
                               onclick="return confirm('Ajukan pengembalian buku ini?')" 
                               style="color: orange; font-weight: bold; text-decoration: none;">
                               <i class="bi bi-arrow-return-left"></i> Ajukan Pengembalian
                            </a>
                        
                        <?php elseif (session('role') == 'admin' && $t['status'] == 'diajukan') : ?>
                            <a href="<?= base_url('peminjaman/konfirmasi_kembali/' . $t['id_pinjam']) ?>" 
                               style="background: #198754; color: white; padding: 5px 10px; text-decoration: none; border-radius: 4px; font-size: 12px;">
                               Konfirmasi Terima Buku
                            </a>
                        
                        <?php elseif ($t['status'] == 'kembali') : ?>
                            <span style="color: #6c757d;">Selesai</span>
                        <?php else : ?>
                            <span style="color: #6c757d;">-</span>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>