<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Restore Database | E-Perpus</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="<?= base_url('assets/css/bootstrap.min.css') ?>" rel="stylesheet">
    <link href="<?= base_url('assets/bootstrap-icons-1.13.1/bootstrap-icons.css') ?>" rel="stylesheet">

    <style>
        body {
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            font-family: 'Inter', sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
        }

        .restore-card {
            border: none;
            border-radius: 25px;
            overflow: hidden;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
        }

        .restore-header {
            background: #ffffff;
            padding: 30px;
            text-align: center;
            border-bottom: 1px solid #f1f5f9;
        }

        .brand-logo {
            width: 60px;
            height: 60px;
            background: #ef4444; /* Warna merah danger */
            color: white;
            border-radius: 18px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 15px;
            font-size: 28px;
            box-shadow: 0 10px 20px rgba(239, 68, 68, 0.2);
        }

        .form-control {
            border-radius: 12px;
            padding: 12px 15px;
            border: 1px solid #e0e0e0;
            background-color: #fcfcfc;
        }

        .form-control:focus {
            box-shadow: 0 0 0 4px rgba(239, 68, 68, 0.1);
            border-color: #ef4444;
        }

        .btn-restore {
            background: #2d3436;
            color: white;
            border-radius: 12px;
            padding: 12px 25px;
            font-weight: 600;
            transition: all 0.3s;
            border: none;
        }

        .btn-restore:hover {
            background: #ef4444; /* Berubah merah pas hover biar sangar */
            transform: translateY(-2px);
            color: white;
        }

        .btn-back {
            border-radius: 12px;
            padding: 12px 25px;
            font-weight: 600;
            color: #64748b;
            text-decoration: none;
            transition: 0.3s;
        }

        .btn-back:hover {
            color: #1e293b;
            background: #f1f5f9;
        }

        .warning-box {
            background-color: #fff1f2;
            border-left: 5px solid #e11d48;
            border-radius: 12px;
            color: #9f1239;
        }
    </style>
</head>

<body>

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card restore-card shadow-lg">
                    
                    <div class="restore-header">
                        <div class="brand-logo">
                            <i class="bi bi-arrow-counterclockwise"></i>
                        </div>
                        <h4 class="fw-bold text-dark mb-1">Restore Database</h4>
                        <p class="text-muted small mb-0">Pulihkan data sistem dari file backup (.sql)</p>
                    </div>

                    <div class="card-body p-4 p-md-5">

                        <?php if (session()->getFlashdata('error')) : ?>
                            <div class="alert alert-danger border-0 small d-flex align-items-center" style="border-radius: 12px;">
                                <i class="bi bi-x-circle-fill me-2"></i>
                                <?= session()->getFlashdata('error') ?>
                            </div>
                        <?php endif; ?>

                        <?php if (session()->getFlashdata('success')) : ?>
                            <div class="alert alert-success border-0 small d-flex align-items-center" style="border-radius: 12px;">
                                <i class="bi bi-check-circle-fill me-2"></i>
                                <?= session()->getFlashdata('success') ?>
                            </div>
                        <?php endif; ?>

                        <div class="warning-box p-3 mb-4 small">
                            <div class="d-flex">
                                <i class="bi bi-exclamation-triangle-fill me-2 fs-5"></i>
                                <div>
                                    <strong>PERINGATAN KERAS!</strong><br>
                                    Proses ini akan menghapus dan menimpa data yang ada saat ini. Pastikan file SQL yang diunggah sudah benar.
                                </div>
                            </div>
                        </div>

                        <form action="<?= base_url('restore/process') ?>" method="post" enctype="multipart/form-data"
                            onsubmit="return confirm('Yakin ingin restore database? Semua data saat ini akan hilang!')">
                            <?= csrf_field() ?>

                            <div class="mb-4">
                                <label class="form-label small fw-bold text-muted">PILIH FILE SQL</label>
                                <div class="input-group">
                                    <input type="file" name="file_sql" class="form-control" accept=".sql" required>
                                </div>
                                <div class="form-text small mt-2">Format file harus .sql (Hasil Export MySQL)</div>
                            </div>

                            <div class="d-flex align-items-center justify-content-between pt-2">
                                <a href="<?= base_url('/') ?>" class="btn-back">
                                    <i class="bi bi-arrow-left me-1"></i> Kembali
                                </a>
                                <button type="submit" class="btn btn-restore">
                                    Mulai Restore <i class="bi bi-lightning-charge-fill ms-1"></i>
                                </button>
                            </div>
                        </form>

                    </div>
                </div>
                
                <p class="text-center mt-4 text-muted small">
                    &copy; <?= date('Y') ?> E-Perpus Pintar - Security System
                </p>
            </div>
        </div>
    </div>

    <script src="<?= base_url('assets/js/bootstrap.bundle.min.js') ?>"></script>
</body>

</html>