<?php
require_once 'connect.php';
require_once '../../config/header.php';

$error = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama_lengkap = trim(mysqli_real_escape_string($conn, $_POST['nama_lengkap']));
    $username     = trim(mysqli_real_escape_string($conn, $_POST['username']));
    $password     = $_POST['password'];
    $no_telp      = trim(mysqli_real_escape_string($conn, $_POST['no_telp']));
    $alamat       = trim(mysqli_real_escape_string($conn, $_POST['alamat']));

    if (empty($nama_lengkap))  $error[] = 'Nama lengkap wajib diisi.';
    if (empty($username))      $error[] = 'Username wajib diisi.';
    if (empty($password))      $error[] = 'Password wajib diisi.';
    if (strlen($password) < 6) $error[] = 'Password minimal 6 karakter.';
    if (empty($no_telp))       $error[] = 'Nomor telepon wajib diisi.';

    if (empty($error)) {
        $cek = mysqli_query($conn, "SELECT id_customer FROM customer WHERE username = '$username'");
        if (mysqli_num_rows($cek) > 0) $error[] = 'Username sudah digunakan.';
    }

    if (empty($error)) {
        // Generate ID: CST001, CST002, dst
        $last = mysqli_fetch_assoc(mysqli_query($conn, "SELECT id_customer FROM customer ORDER BY id_customer DESC LIMIT 1"));
        $num  = $last ? (int) substr($last['id_customer'], 3) + 1 : 1;
        $id_customer = 'CST' . str_pad($num, 3, '0', STR_PAD_LEFT);

        $pass = password_hash($password, PASSWORD_DEFAULT);
        mysqli_query($conn, "INSERT INTO customer (id_customer, nama_lengkap, username, password, no_telp, alamat)
            VALUES ('$id_customer', '$nama_lengkap', '$username', '$pass', '$no_telp', '$alamat')");
        header("Location: index.php?pesan=tambah");
        exit;
    }
}
?>

<div class="main-content">
    <div class="topbar">
        <h5><i class="bi bi-person-plus me-2"></i>Tambah Pelanggan</h5>
        <span class="text-muted" style="font-size:.85rem;">Admin &rsaquo; Data Pelanggan &rsaquo; Tambah</span>
    </div>

    <div class="card" style="max-width:600px">
        <div class="card-header py-3"><i class="bi bi-people me-1"></i> Form Tambah Pelanggan</div>
        <div class="card-body p-4">

            <?php if (!empty($error)): ?>
                <div class="alert alert-danger">
                    <ul class="mb-0"><?php foreach ($error as $e) echo "<li>$e</li>"; ?></ul>
                </div>
            <?php endif; ?>

            <form method="POST">
                <div class="mb-3">
                    <label class="form-label fw-semibold">Nama Lengkap <span class="text-danger">*</span></label>
                    <input type="text" name="nama_lengkap" class="form-control"
                           value="<?= htmlspecialchars($_POST['nama_lengkap'] ?? '') ?>">
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Username <span class="text-danger">*</span></label>
                    <input type="text" name="username" class="form-control"
                           value="<?= htmlspecialchars($_POST['username'] ?? '') ?>">
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Password <span class="text-danger">*</span></label>
                    <input type="password" name="password" class="form-control" placeholder="Minimal 6 karakter">
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">No. Telepon <span class="text-danger">*</span></label>
                    <input type="text" name="no_telp" class="form-control"
                           value="<?= htmlspecialchars($_POST['no_telp'] ?? '') ?>">
                </div>
                <div class="mb-4">
                    <label class="form-label fw-semibold">Alamat</label>
                    <textarea name="alamat" class="form-control" rows="3"><?= htmlspecialchars($_POST['alamat'] ?? '') ?></textarea>
                </div>
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-accent"><i class="bi bi-save me-1"></i> Simpan</button>
                    <a href="index.php" class="btn btn-outline-secondary">Batal</a>
                </div>
            </form>
        </div>
    </div>
</div>

<?php require_once '../../config/footer.php'; ?>
