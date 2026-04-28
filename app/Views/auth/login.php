<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | E-Perpus</title>

    <link href="<?= base_url('assets/css/bootstrap.min.css') ?>" rel="stylesheet">
    <link href="<?= base_url('assets/bootstrap-icons-1.13.1/bootstrap-icons.css') ?>" rel="stylesheet">
    <link rel="icon" href="<?= base_url('uploads/users/eperpus1.png') ?>" >

    <style>
        /* Desain Background dengan gradasi warna biru-abu modern */
        body {
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            font-family: 'Inter', sans-serif;
        }
        /* Card Login dibuat melengkung halus dan tanpa border */
        .login-card {
            border: none;
            border-radius: 25px;
            overflow: hidden;
        }
        .login-header {
            background: #ffffff;
            padding: 40px 20px 20px 20px;
            text-align: center;
        }
        .login-header h4 {
            font-weight: 800;
            color: #2d3436;
            letter-spacing: -1px;
        }
        /* Custom input field agar lebih elegan */
        .form-control {
            border-radius: 12px;
            padding: 12px 15px;
            border: 1px solid #e0e0e0;
            background-color: #fcfcfc;
        }
        /* Efek fokus saat input diklik (Glow warna Teal) */
        .form-control:focus {
            box-shadow: 0 0 0 4px rgba(0, 128, 128, 0.1);
            border-color: #008080;
        }
        /* Tombol login dengan warna gelap dan efek hover melayang */
        .btn-login {
            background: #2d3436;
            color: white;
            border-radius: 12px;
            padding: 12px;
            font-weight: 600;
            transition: all 0.3s;
        }
        .btn-login:hover {
            background: #000;
            transform: translateY(-2px);
            color: white;
        }
        /* Desain Logo di atas tulisan login */
        .brand-logo {
            width: 60px;
            height: 60px;
            background: #008080;
            color: white;
            border-radius: 18px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 15px;
            font-size: 28px;
            box-shadow: 0 10px 20px rgba(0, 128, 128, 0.2);
        }
    </style>
</head>

<body>

    <div class="container d-flex justify-content-center align-items-center vh-100">
        <div class="card login-card shadow-lg" style="width: 400px;">
            
            <div class="login-header">
                <div class="brand-logo">
                    <i class="bi bi-book-half"></i>
                </div>
                <h4>E-PERPUS PINTAR</h4>
                <p class="text-muted small">Silakan masuk untuk mengelola perpustakaan</p>
            </div>

            <div class="card-body px-4 pb-5">

                <?php if (session()->getFlashdata('error') || session()->getFlashdata('salahpw')): ?>
                    <div class="alert alert-danger d-flex align-items-center border-0 small" style="border-radius: 12px;">
                        <i class="bi bi-exclamation-circle-fill me-2"></i>
                        <div><?= session()->getFlashdata('error') ?? session()->getFlashdata('salahpw') ?></div>
                    </div>
                <?php endif; ?>

                <form action="<?= base_url('/proses-login') ?>" method="post">
                    <?= csrf_field() ?> <div class="mb-3">
                        <label class="form-label small fw-bold text-muted">USERNAME</label>
                        <div class="input-group">
                            <span class="input-group-text bg-white border-end-0" style="border-radius: 12px 0 0 12px;">
                                <i class="bi bi-person text-muted"></i>
                            </span>
                            <input type="text" name="username" class="form-control border-start-0" placeholder="Username Anda" required style="border-radius: 0 12px 12px 0;">
                        </div>
                    </div>

                    <div class="mb-4">
                        <div class="d-flex justify-content-between">
                            <label class="form-label small fw-bold text-muted">PASSWORD</label>
                        </div>
                        <div class="input-group">
                            <span class="input-group-text bg-white border-end-0" style="border-radius: 12px 0 0 12px;">
                                <i class="bi bi-lock text-muted"></i>
                            </span>
                            <input type="password" name="password" class="form-control border-start-0" placeholder="••••••••" required style="border-radius: 0 12px 12px 0;">
                        </div>
                    </div>

                    <button type="submit" class="btn btn-login w-100 mb-3">
                        Masuk Ke Sistem <i class="bi bi-arrow-right ms-2"></i>
                    </button>

                </form>

                <div class="position-absolute top-0 end-0 p-3">
                    <a href="<?= base_url('restore') ?>" class="btn btn-white shadow-sm border-0 d-flex align-items-center gap-2" 
                       style="border-radius: 12px; font-size: 11px; font-weight: 600; color: #dc3545; background: white;">
                         <i class="bi bi-arrow-counterclockwise"></i> RESTORE DB
                    </a>
                </div>

                <div class="text-center mt-4">
                    <hr class="my-3 opacity-25" style="border-top: 1px solid #dee2e6;">
                    <p class="mb-0">
                        <span class="text-muted small">Belum punya akun?</span>
                        <a href="<?= base_url('users/create') ?>" class="text-decoration-none small fw-bold ms-1" style="color: #0891b2;">Daftar Sekarang</a>
                    </p>
                </div>
                 
            </div>
        </div>
    </div>

    <script src="<?= base_url('assets/js/bootstrap.bundle.min.js') ?>"></script>
</body>

</html>