<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrasi Pengguna Baru</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f8f9fa;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0;
        }

        .card-register {
            border: none;
            border-radius: 25px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
            width: 100%;
            max-width: 500px;
            background: #ffffff;
        }

        .form-control, .form-select {
            background-color: #f1f4f9;
            border: none;
            padding: 12px 15px;
            border-radius: 12px;
        }

        .form-control:focus, .form-select:focus {
            background-color: #e8ecf3;
            box-shadow: none;
            outline: none;
        }

        .btn-teal {
            background-color: #008080;
            color: white;
            border: none;
            padding: 14px;
            border-radius: 15px;
            font-weight: 700;
            transition: all 0.3s ease;
        }

        .btn-teal:hover {
            background-color: #006666;
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 128, 128, 0.2);
        }

        .input-group-text {
            background-color: #f1f4f9;
            border: none;
            border-radius: 12px 0 0 12px;
            color: #6c757d;
        }

        .form-control {
            border-radius: 0 12px 12px 0;
        }
    </style>
</head>

<body>

    <div class="container p-3">
        <div class="card-register mx-auto p-4 p-md-5">
            <div class="text-center mb-4">
                <h3 class="fw-bold text-dark">Daftar User</h3>
                <p class="text-muted small">Silakan isi data lengkap pengguna baru</p>
            </div>

            <?php if (session()->getFlashdata('error')): ?>
                <div class="alert alert-danger border-0 small mb-4" style="border-radius: 12px;">
                    <i class="bi bi-exclamation-circle me-2"></i> <?= session()->getFlashdata('error') ?>
                </div>
            <?php endif; ?>

            <form action="<?= base_url('users/store') ?>" method="post" enctype="multipart/form-data">
                <?= csrf_field() ?>

                <div class="mb-3">
                    <label class="form-label small fw-bold text-secondary">Nama Lengkap</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-person"></i></span>
                        <input type="text" name="nama" class="form-control" placeholder="Nama Lengkap" required>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label small fw-bold text-secondary">Email</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                        <input type="email" name="email" class="form-control" placeholder="email@contoh.com" required>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label small fw-bold text-secondary">Username</label>
                        <input type="text" name="username" class="form-control" placeholder="username" required style="border-radius: 12px;">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label small fw-bold text-secondary">Role</label>
                        <select name="role" class="form-select" required>
                            <option value="" disabled selected>Pilih Role</option>
                            <option value="admin">Admin</option>
                            <option value="petugas">Petugas</option>
                            <option value="anggota">Anggota</option>
                        </select>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label small fw-bold text-secondary">Password</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-lock"></i></span>
                        <input type="password" name="password" class="form-control" placeholder="••••••••" required>
                    </div>
                </div>

                <div class="mb-4">
                    <label class="form-label small fw-bold text-secondary">Foto Profil</label>
                    <input type="file" name="foto" class="form-control" accept="image/*" style="border-radius: 12px; padding: 10px;">
                </div>

                <div class="d-grid">
                    <button type="submit" class="btn btn-teal">
                        Simpan Pengguna
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        <?php if (session()->getFlashdata('msg')) : ?>
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: '<?= session()->getFlashdata('msg') ?>',
                showConfirmButton: false,
                timer: 2000
            });
        <?php endif; ?>
    </script>

</body>

</html>