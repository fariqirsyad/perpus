<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Restore Database</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Optional: Bootstrap biar rapi -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>

    <div class="container mt-5">
        <div class="card shadow">
            <div class="card-header bg-danger text-white">
                <h4>⚠️ Restore Database</h4>
            </div>
            <div class="card-body">

                <!-- Pesan error -->
                <?php if (session()->getFlashdata('error')) : ?>
                    <div class="alert alert-danger">
                        <?= session()->getFlashdata('error') ?>
                    </div>
                <?php endif; ?>

                <!-- Pesan sukses -->
                <?php if (session()->getFlashdata('success')) : ?>
                    <div class="alert alert-success">
                        <?= session()->getFlashdata('success') ?>
                    </div>
                <?php endif; ?>

                <div class="alert alert-warning">
                    <strong>Peringatan!</strong><br>
                    Proses restore akan <b>menimpa seluruh data database</b>.<br>
                    Pastikan Anda sudah melakukan backup terlebih dahulu.
                </div>

                <form action="<?= base_url('restore/process') ?>" method="post" enctype="multipart/form-data"
                    onsubmit="return confirm('Yakin ingin restore database? Semua data akan ditimpa!')">

                    <div class="mb-3">
                        <label class="form-label">Upload File SQL</label>
                        <input type="file" name="file_sql" class="form-control" accept=".sql" required>
                    </div>

                    <button type="submit" class="btn btn-danger">
                        🔄 Restore Database
                    </button>

                    <a href="<?= base_url('/') ?>" class="btn btn-secondary">
                        ← Kembali
                    </a>
                </form>

            </div>
        </div>
    </div>

</body>

</html>