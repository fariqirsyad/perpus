<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Akses Restore | E-Perpus</title>

    <link href="<?= base_url('assets/css/bootstrap.min.css') ?>" rel="stylesheet">
    <link href="<?= base_url('assets/bootstrap-icons-1.13.1/bootstrap-icons.css') ?>" rel="stylesheet">
    
    <style>
        body {
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            font-family: 'Inter', sans-serif;
        }
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
        .form-control {
            border-radius: 12px;
            padding: 12px 15px;
            border: 1px solid #e0e0e0;
            background-color: #fcfcfc;
        }
        .form-control:focus {
            box-shadow: 0 0 0 4px rgba(0, 128, 128, 0.1);
            border-color: #008080;
        }
        /* Tombol disamain dengan style Login lu (Warna Hitam) */
        .btn-restore {
            background: #2d3436;
            color: white;
            border-radius: 12px;
            padding: 12px;
            font-weight: 600;
            transition: all 0.3s;
            border: none;
        }
        .btn-restore:hover {
            background: #000;
            transform: translateY(-2px);
            color: white;
        }
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
                    <i class="bi bi-shield-lock-fill"></i> </div>
                <h4>E-PERPUS PINTAR</h4>
                <p class="text-muted small">Akses Khusus Restore Database</p>
            </div>

            <div class="card-body px-4 pb-5">

                <?php if (session()->getFlashdata('error')) : ?>
                    <div class="alert alert-danger d-flex align-items-center border-0 small mb-4" style="border-radius: 12px;">
                        <i class="bi bi-exclamation-circle-fill me-2"></i>
                        <div><?= session()->getFlashdata('error') ?></div>
                    </div>
                <?php endif; ?>

                <form action="<?= base_url('restore/auth') ?>" method="post">
                    <?= csrf_field() ?>

                    <div class="mb-3">
                        <label class="form-label small fw-bold text-muted text-uppercase">Security Password</label>
                        <div class="input-group">
                            <span class="input-group-text bg-white border-end-0" style="border-radius: 12px 0 0 12px;">
                                <i class="bi bi-key text-muted"></i>
                            </span>
                            <input type="password" name="password" id="password" class="form-control border-start-0" placeholder="••••••••" required style="border-radius: 0 12px 12px 0;">
                        </div>
                    </div>

                    <div class="mb-4 d-flex align-items-center">
                        <input type="checkbox" class="form-check-input me-2" id="toggle" onclick="togglePassword()">
                        <label for="toggle" class="form-check-label small text-muted" style="cursor: pointer;">Tampilkan Password</label>
                    </div>

                    <button type="submit" class="btn btn-restore w-100 mb-3">
                        Verifikasi Keamanan <i class="bi bi-shield-check ms-2"></i>
                    </button>

                </form>

                <div class="text-center mt-3">
                    <hr class="my-3 opacity-25" style="border-top: 1px solid #dee2e6;">
                    <a href="<?= base_url('login') ?>" class="text-decoration-none small fw-bold" style="color: #008080;">
                        <i class="bi bi-arrow-left me-1"></i> Kembali ke Login
                    </a>
                </div>

            </div>
        </div>
    </div>

    <script>
        function togglePassword() {
            var x = document.getElementById("password");
            if (x.type === "password") {
                x.type = "text";
            } else {
                x.type = "password";
            }
        }
    </script>
    <script src="<?= base_url('assets/js/bootstrap.bundle.min.js') ?>"></script>
</body>

</html>