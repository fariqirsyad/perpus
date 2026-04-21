<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Akses Restore</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap 4 Local -->
    <link rel="stylesheet" href="<?= base_url('assets/css/bootstrap.min.css') ?>">

    <style>
        body {
            background: linear-gradient(135deg, #6a11cb, #2575fc);
            height: 100vh;
        }

        .login-container {
            height: 100vh;
        }

        .card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
        }

        .card-header {
            background: none;
            border-bottom: none;
            text-align: center;
        }

        .title {
            font-weight: bold;
            color: #333;
        }

        .form-control {
            border-radius: 10px;
        }

        .btn-custom {
            background: #2575fc;
            color: white;
            border-radius: 10px;
        }

        .btn-custom:hover {
            background: #1a5edb;
        }

        .icon {
            font-size: 45px;
        }
    </style>
</head>

<body>

    <div class="container login-container d-flex justify-content-center align-items-center">
        <div class="col-md-4">

            <div class="card p-4">

                <div class="card-header">
                    <div class="icon">🔐</div>
                    <h4 class="title mt-2">Akses Restore Database</h4>
                    <p class="text-muted small">Masukkan password untuk melanjutkan</p>
                </div>

                <div class="card-body">

                    <!-- Alert Error -->
                    <?php if (session()->getFlashdata('error')) : ?>
                        <div class="alert alert-danger text-center">
                            <?= session()->getFlashdata('error') ?>
                        </div>
                    <?php endif; ?>

                    <form action="<?= base_url('restore/auth') ?>" method="post">

                        <div class="form-group">
                            <label>Password</label>
                            <input type="password" name="password" id="password" class="form-control" placeholder="Masukkan password" required>
                        </div>

                        <div class="form-group form-check">
                            <input type="checkbox" class="form-check-input" onclick="togglePassword()">
                            <label class="form-check-label">Tampilkan Password</label>
                        </div>

                        <button type="submit" class="btn btn-custom btn-block">
                            Masuk
                        </button>

                    </form>

                </div>
            </div>

        </div>
    </div>

    <!-- JS Local -->
    <script src="<?= base_url('assets/bootstrap/js/jquery.min.js') ?>"></script>
    <script src="<?= base_url('assets/bootstrap/js/bootstrap.min.js') ?>"></script>

    <script>
        function togglePassword() {
            var x = document.getElementById("password");
            x.type = x.type === "password" ? "text" : "password";
        }
    </script>

</body>

</html>