<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="container mt-4">
    <h2>Data Peminjaman</h2>

    <div style="display: flex; justify-content: space-between; margin-bottom: 15px;">
        <form action="" method="get">
            <input type="text" name="cari" placeholder="Cari nama/buku..." value="<?= isset($_GET['cari']) ? $_GET['cari'] : '' ?>" style="padding: 6px; border: 1px solid #ccc; border-radius: 4px;">
            <button type="submit" style="padding: 6px 12px; background: #6c757d; color: white; border: none; border-radius: 4px; cursor: pointer;">Cari</button>
            <?php if(isset($_GET['cari'])): ?>
                <a href="<?= base_url('peminjaman') ?>" style="font-size: 12px; margin-left: 5px; text-decoration: none; color: #007bff;">Reset</a>
            <?php endif; ?>
        </form>
    </div>

    <table border="1" style="width: 100%; border-collapse: collapse; text-align: left; background-color: white;">
        <thead style="background-color: #f8f9fa;">
            <tr>
                <th style="padding: 12px; border: 1px solid #dee2e6;">Peminjam</th>
                <th style="padding: 12px; border: 1px solid #dee2e6;">Judul Buku</th>
                <th style="padding: 12px; border: 1px solid #dee2e6; text-align: center;">Tgl Pinjam</th>
                <th style="padding: 12px; border: 1px solid #dee2e6; text-align: center;">Batas Kembali</th> <th style="padding: 12px; border: 1px solid #dee2e6; text-align: center;">Tgl Dikembalikan</th> <th style="padding: 12px; border: 1px solid #dee2e6;">Denda</th>
                <th style="padding: 12px; border: 1px solid #dee2e6; text-align: center;">Status</th>
                <th style="padding: 12px; border: 1px solid #dee2e6; text-align: center;">Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php if(empty($transaksi)): ?>
                <tr>
                    <td colspan="8" style="text-align: center; padding: 20px;">Tidak ada data peminjaman.</td>
                </tr>
            <?php endif; ?>

            <?php foreach ($transaksi as $t) : ?>
                <tr>
                    <td style="padding: 10px; border: 1px solid #dee2e6;"><?= $t['nama'] ?></td>
                    <td style="padding: 10px; border: 1px solid #dee2e6;"><?= $t['judul'] ?></td>
                    <td style="padding: 10px; border: 1px solid #dee2e6; text-align: center;"><?= $t['tgl_pinjam'] ?></td>
                    
                    <td style="padding: 10px; border: 1px solid #dee2e6; text-align: center; color: #d9534f; font-weight: bold;">
                        <?= $t['tgl_kembali'] ?>
                    </td>

                    <td style="padding: 10px; border: 1px solid #dee2e6; text-align: center;">
                        <?php if ($t['tgl_dikembalikan'] && $t['tgl_dikembalikan'] != '0000-00-00') : ?>
                            <span style="color: #198754;"><?= $t['tgl_dikembalikan'] ?></span>
                        <?php else : ?>
                            <span style="color: #ccc;">-</span>
                        <?php endif; ?>
                    </td>

                    <td style="padding: 10px; border: 1px solid #dee2e6; color: red; font-weight: bold;">
                        Rp <?= number_format($t['denda'], 0, ',', '.') ?>
                    </td>

                    <td style="padding: 10px; border: 1px solid #dee2e6; text-align: center;">
                        <?php if ($t['status'] == 'dipinjam') : ?>
                            <span style="background: #cfe2ff; color: #084298; padding: 4px 10px; border-radius: 12px; font-size: 11px; font-weight: bold;">Sedang Dipinjam</span>
                        <?php elseif ($t['status'] == 'diajukan') : ?>
                            <span style="background: #fff3cd; color: #856404; padding: 4px 10px; border-radius: 12px; font-size: 11px; font-weight: bold;">Menunggu Konfirmasi</span>
                        <?php else : ?>
                            <span style="background: #d1e7dd; color: #0f5132; padding: 4px 10px; border-radius: 12px; font-size: 11px; font-weight: bold;">Sudah Kembali</span>
                        <?php endif; ?>
                    </td>

                    <td style="padding: 10px; border: 1px solid #dee2e6; text-align: center;">
                        <?php if (session('role') == 'anggota' && $t['status'] == 'dipinjam') : ?>
                            <a href="<?= base_url('peminjaman/ajukan_kembali/' . $t['id_pinjam']) ?>" 
                               onclick="return confirm('Ajukan pengembalian buku ini?')" 
                               style="color: #fd7e14; font-weight: bold; text-decoration: none; font-size: 13px;">
                               ↩ Ajukan Balik
                            </a>
                        
                        <?php elseif (session('role') == 'admin' && $t['status'] == 'diajukan') : ?>
                            <a href="<?= base_url('peminjaman/konfirmasi_kembali/' . $t['id_pinjam']) ?>" 
                               style="background: #198754; color: white; padding: 6px 12px; text-decoration: none; border-radius: 4px; font-size: 11px; display: inline-block;">
                               Konfirmasi Terima
                            </a>
                        
                        <?php elseif ($t['status'] == 'kembali') : ?>
                            <span style="color: #198754; font-weight: bold;">✔️ Selesai</span>
                        <?php else : ?>
                            <span style="color: #6c757d;">-</span>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?= $this->endSection() ?>