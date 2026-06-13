<?php
require_once '../../connect.php';
require_once '../../config/header.php';

$error = [];

if (!isset($_GET['id'])) { header("Location: index.php"); exit; }
$id  = mysqli_real_escape_string($conn, $_GET['id']);
$res = mysqli_query($conn, "SELECT * FROM customer WHERE id_customer = '$id'");
$row = mysqli_fetch_assoc($res);
if (!$row) { header("Location: index.php"); exit; }

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama_lengkap = trim(mysqli_real_escape_string($conn, $_POST['nama_lengkap']));
    $username     = trim(mysqli_real_escape_string($conn, $_POST['username']));
    $no_telp      = trim(mysqli_real_escape_string($conn, $_POST['no_telp']));
    $alamat       = trim(mysqli_real_escape_string($conn, $_POST['alamat']));
    $password_baru = $_POST['password_baru'];

    if (empty($nama_lengkap))  $error[] = 'Nama lengkap wajib diisi.';
    if (empty($username))      $error[] = 'Username wajib diisi.';
    if (empty($no_telp))       $error[] = 'Nomor telepon wajib diisi.';
    if (!empty($password_baru) && strlen($password_baru) < 6) $error[] = 'Password minimal 6 karakter.';

    if (empty($error)) {
        $cek = mysqli_query($conn, "SELECT id_customer FROM customer WHERE username = '$username' AND id_customer != '$id'");
        if (mysqli_num_rows($cek) > 0) $error[] = 'Username sudah digunakan pelanggan lain.';
    }

    if (empty($error)) {
        if (!empty($password_baru)) {
            $pass = password_hash($password_baru, PASSWORD_DEFAULT);
            mysqli_query($conn, "UPDATE customer SET nama_lengkap='$nama_lengkap', username='$username', password='$pass', no_telp='$no_telp', alamat='$alamat' WHERE id_customer='$id'");
        } else {
            mysqli_query($conn, "UPDATE customer SET nama_lengkap='$nama_lengkap', username='$username', no_telp='$no_telp', alamat='$alamat' WHERE id_customer='$id'");
        }
        header("Location: index.php?pesan=edit");
        exit;
    }
    $row = array_merge($row, $_POST);
}
?>

<div class="main-content">
    <div class="topbar">
        <h5><i class="bi bi-person-gear me-2"></i>Edit Pelanggan</h5>
        <span class="text-muted" style="font-size:.85rem;">Admin &rsaquo; Data Pelanggan &rsaquo; Edit</span>
    </div>

    <div class="card" style="max-width:600px">
        <div class="card-header py-3"><i class="bi bi-people me-1"></i> Form Edit Pelanggan</div>
        <div class="card-body p-4">

            <?php if (!empty($error)): ?>
                <div class="alert alert-danger">
                    <ul class="mb-0"><?php foreach ($error as $e) echo "<li>$e</li>"; ?></ul>
                </div>
            <?php endif; ?>

            <div class="mb-3">
                <label class="form-label fw-semibold">ID Pelanggan</label>
                <input type="text" class="form-control" value="<?= $row['id_customer'] ?>" disabled>
            </div>

            <form method="POST">
                <div class="mb-3">
                    <label class="form-label fw-semibold">Nama Lengkap <span class="text-danger">*</span></label>
                    <input type="text" name="nama_lengkap" class="form-control"
                           value="<?= htmlspecialchars($row['nama_lengkap']) ?>">
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Username <span class="text-danger">*</span></label>
                    <input type="text" name="username" class="form-control"
                           value="<?= htmlspecialchars($row['username']) ?>">
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Password Baru</label>
                    <input type="password" name="password_baru" class="form-control"
                           placeholder="Kosongkan jika tidak ingin mengubah">
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">No. Telepon <span class="text-danger">*</span></label>
                    <input type="text" name="no_telp" class="form-control"
                           value="<?= htmlspecialchars($row['no_telp']) ?>">
                </div>
                <div class="mb-4">
                    <label class="form-label fw-semibold">Alamat</label>
                    <textarea name="alamat" class="form-control" rows="3"><?= htmlspecialchars($row['alamat']) ?></textarea>
                </div>
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-accent"><i class="bi bi-save me-1"></i> Simpan Perubahan</button>
                    <a href="index.php" class="btn btn-outline-secondary">Batal</a>
                </div>
            </form>
        </div>
    </div>
</div>

<?php require_once '../../config/footer.php'; ?>
