<?php session_start(); ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login Kasir</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(to right, #4a75d7, #2c5ba8); /* Gradien biru */
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .login-card {
            background: #fff; /* Pastikan kartu berwarna putih */
            border: none;
            border-radius: 1rem;
            box-shadow: 0 8px 32px rgba(0,0,0,0.15); /* Bayangan lebih menonjol */
            overflow: hidden;
            padding: 2.5rem; /* Menambahkan padding ke card */
        }
        .login-header { /* Hapus latar belakang dan border-radius dari login-header */
            background: none; 
            color: #333; /* Warna teks disesuaikan */
            padding: 0; /* Hapus padding */
            border-radius: 0; /* Hapus border-radius */
            margin-bottom: 1.5rem; /* Tambahkan margin bawah untuk judul */
        }
        .login-header h4 {
            margin: 0;
            font-weight: bold;
            letter-spacing: 0.5px;
            color: #333;
        }
        .login-body {
            padding: 0; /* Hapus padding karena sudah ada di .login-card */
        }
        .brand {
            font-size: 2rem;
            font-weight: bold;
            color: #007bff;
            letter-spacing: 2px;
            margin-bottom: 1.5rem;
            text-align: center;
        }
        .login-logo { 
            margin-bottom: 1.5rem;
            text-align: center;
            font-size: 3.5rem; /* Ukuran ikon buku */
            color: #007bff; /* Warna ikon buku */
        }
        .login-logo img {
            display: none; /* Sembunyikan gambar placeholder */
        }
        /* Tambahan gaya untuk input group agar sesuai desain referensi */
        .input-group > .form-control, .input-group > .form-floating {
            border-top-left-radius: 0 !important;
            border-bottom-left-radius: 0 !important;
        }
        .input-group-text {
            background-color: #f8f9fa; /* Warna background ikon */
            border-right: none;
            border-color: #dee2e6;
            border-top-left-radius: 0.375rem !important;
            border-bottom-left-radius: 0.375rem !important;
        }
        .form-control:focus, .form-floating > .form-control:focus ~ label::after {
            box-shadow: none; /* Hapus shadow fokus default Bootstrap */
        }
        .form-floating > .form-control:focus,
        .form-floating > .form-control:not(:placeholder-shown) {
            padding-top: 2.25rem; /* Sesuaikan padding agar label mengambang pas */
            padding-bottom: 0.75rem;
        }
        .form-floating > label {
            padding: 1.25rem 0.75rem;
            font-size: 0.9rem;
            color: #6c757d;
        }
        .form-floating > .form-control,
        .form-floating > .form-control-plaintext {
            height: calc(3.5rem + 2px); /* Sesuaikan tinggi input */
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-5 col-lg-4">
                <div class="card login-card">
                    <div class="login-logo">
                        <i class="bi bi-book"></i> <!-- Ikon buku -->
                    </div>
                    <div class="text-center mb-4">
                        <h4 class="fw-bold">Login Kasir</h4>
                        <p class="text-muted">Silakan masuk untuk melanjutkan</p>
                    </div>
                    <div class="login-body">
                        <?php if (isset($_SESSION['error'])): ?>
                            <div class="alert alert-danger"> 
                                <?= $_SESSION['error']; unset($_SESSION['error']); ?>
                            </div>
                        <?php endif; ?>
                        <form action="../backend/proses_login.php" method="POST">
                            <div class="input-group mb-3">
                                <span class="input-group-text"><i class="bi bi-person"></i></span>
                                <input type="text" name="username" class="form-control" placeholder="Nama Pengguna" required autofocus autocomplete="off">
                            </div>
                            <div class="input-group mb-3">
                                <span class="input-group-text"><i class="bi bi-lock"></i></span>
                                <input type="password" name="password" class="form-control" placeholder="Kata Sandi" required autocomplete="current-password">
                                <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                    <i class="bi bi-eye"></i>
                                </button>
                            </div>
                            <button type="submit" class="btn btn-primary w-100 py-2">
                                Login <i class="bi bi-arrow-right"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        const togglePassword = document.querySelector('#togglePassword');
        const password = document.querySelector('input[name="password"]');

        if (togglePassword && password) {
            togglePassword.addEventListener('click', function (e) {
                // toggle the type attribute
                const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
                password.setAttribute('type', type);
                // toggle the eye icon
                this.querySelector('i').classList.toggle('bi-eye');
                this.querySelector('i').classList.toggle('bi-eye-slash');
            });
        }
    </script>
</body>
</html>
