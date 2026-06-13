<?php
session_start();
require_once __DIR__ . '/../connect.php';

// Aksi Log Out
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: ../customer/view/index.php");
    exit;
}

$error = "";
$sukses = "";

// PROSES 1: AKSI SIGN IN / LOGIN
if (isset($_POST['login'])) {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = $_POST['password'];

    $query = mysqli_query($conn, "SELECT * FROM customer WHERE username='$username'");
    if (mysqli_num_rows($query) === 1) {
        $row = mysqli_fetch_assoc($query);
        // Memakai password_verify demi keamanan hash data
        if (password_verify($password, $row['password'])) {
            $_SESSION['id_customer'] = $row['id_customer'];
            $_SESSION['username']   = $row['username'];
            header("Location: ../customer/view/index.php");
            exit;
        }
    }
    $error = "Username atau password Anda salah!";
}

// PROSES 2: AKSI SIGN UP / DAFTAR BARU
if (isset($_POST['register'])) {
    $id_customer  = "CUST" . rand(1000, 9999);
    $nama_lengkap = mysqli_real_escape_string($conn, $_POST['nama_lengkap']);
    $username     = mysqli_real_escape_string($conn, $_POST['username']);
    $no_telp      = mysqli_real_escape_string($conn, $_POST['no_telp']);
    $alamat       = mysqli_real_escape_string($conn, $_POST['alamat']);
    $password     = password_hash($_POST['password'], PASSWORD_BCRYPT); // Hash password

    // Cek duplikasi username
    $cek_user = mysqli_query($conn, "SELECT username FROM customer WHERE username='$username'");
    if(mysqli_num_rows($cek_user) > 0) {
        $error = "Username sudah terdaftar! Pilih yang lain.";
    } else {
        $insert = "INSERT INTO customer (id_customer, nama_lengkap, username, alamat, password, no_telp) 
                   VALUES ('$id_customer', '$nama_lengkap', '$username', '$alamat', '$password', '$no_telp')";
        if(mysqli_query($conn, $insert)) {
            $sukses = "Akun berhasil dibuat! Silakan masuk menggunakan form Login.";
        } else {
            $error = "Gagal mendaftarkan akun.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk / Daftar Akses Customer</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        :root { --primary: #1a3a5c; --accent: #f59e0b; }
        body { background: #f0f4f8; font-family: 'Segoe UI', sans-serif; }
        .card-auth { border: none; border-radius: .75rem; box-shadow: 0 4px 15px rgba(0,0,0,.05); }
        .btn-primary-custom { background: var(--primary); color: #fff; border: none; }
        .btn-primary-custom:hover { background: #11273f; color: #fff; }
    </style>
</head>
<body>

<div class="container py-5">
    <div class="text-center mb-4">
        <h3 class="fw-bold" style="color: var(--primary);"><i class="bi bi-building"></i> SCAFFOLDRENT</h3>
        <p class="text-muted">Akses Portal Portal Katalog Digital Pelanggan</p>
    </div>

    <div class="row justify-content-center g-4">
        <div class="col-md-5">
            <div class="card card-auth p-4 bg-white text-dark">
                <h4 class="fw-bold mb-3" style="color: var(--primary);">Login Customer</h4>
                
                <?php if($error && isset($_POST['login'])): ?><div class="alert alert-danger p-2 small"><?= $error ?></div><?php endif; ?>
                <?php if($_GET['pesan'] ?? '' === 'wajib_login'): ?><div class="alert alert-warning p-2 small">Silakan masuk dahulu untuk dapat melakukan transaksi sewa.</div><?php endif; ?>
                <?php if($sukses): ?><div class="alert alert-success p-2 small"><?= $sukses ?></div><?php endif; ?>

                <form method="POST" action="">
                    <div class="mb-3">
                        <label class="form-label small">Username</label>
                        <input type="text" name="username" class="form-control" placeholder="Masukkan username" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small">Password</label>
                        <input type="password" name="password" class="form-control" placeholder="******" required>
                    </div>
                    <button type="submit" name="login" class="btn btn-primary-custom w-100 py-2">Masuk Sekarang</button>
                </form>
            </div>
        </div>

        <div class="col-md-5">
            <div class="card card-auth p-4 bg-white text-dark">
                <h4 class="fw-bold mb-3" style="color: var(--accent);">Daftar Akun Baru</h4>
                
                <?php if($error && isset($_POST['register'])): ?><div class="alert alert-danger p-2 small"><?= $error ?></div><?php endif; ?>

                <form method="POST" action="">
                    <div class="row g-2 mb-2">
                        <div class="col-6">
                            <label class="form-label small mb-1">Nama Lengkap</label>
                            <input type="text" name="nama_lengkap" class="form-control form-control-sm" required>
                        </div>
                        <div class="col-6">
                            <label class="form-label small mb-1">Username</label>
                            <input type="text" name="username" class="form-control form-control-sm" required>
                        </div>
                    </div>
                    <div class="mb-2">
                        <label class="form-label small mb-1">No. Telepon / WA</label>
                        <input type="text" name="no_telp" class="form-control form-control-sm" required>
                    </div>
                    <div class="mb-2">
                        <label class="form-label small mb-1">Alamat Sekarang</label>
                        <input type="text" name="alamat" class="form-control form-control-sm" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small mb-1">Password</label>
                        <input type="password" name="password" class="form-control form-control-sm" placeholder="Minimal 6 karakter" required>
                    </div>
                    <button type="submit" name="register" class="btn btn-warning w-100 py-2 text-white fw-bold" style="background: var(--accent); border:none;">Daftar Akun</button>
                </form>
            </div>
        </div>
    </div>
    
    <div class="text-center mt-4">
        <a href="../customer/view/index.php" class="text-muted text-decoration-none small"><i class="bi bi-house-door"></i> Kembali Lihat Katalog</a>
    </div>
</div>

</body>
</html>