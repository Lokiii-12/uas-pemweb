<?php
require_once 'connect.php';
require_once '../../config/header.php';

$error = [];

if (!isset($_GET['id'])) { header("Location: index.php"); exit; }
$id  = mysqli_real_escape_string($conn, $_GET['id']);
$res = mysqli_query($conn, "SELECT * FROM barang WHERE id_barang = '$id'");
$row = mysqli_fetch_assoc($res);
if (!$row) { header("Location: index.php"); exit; }

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama_barang = trim(mysqli_real_escape_string($conn, $_POST['nama_barang']));
    $jenis       = trim(mysqli_real_escape_string($conn, $_POST['jenis']));
    $harga_sewa  = (float) $_POST['harga_sewa'];
    $stok        = (int) $_POST['stok'];
    $status      = mysqli_real_escape_string($conn, $_POST['status']);

    if (empty($nama_barang)) $error[] = 'Nama barang wajib diisi.';
    if (empty($jenis))       $error[] = 'Jenis wajib diisi.';
    if ($harga_sewa <= 0)    $error[] = 'Harga sewa harus lebih dari 0.';
    if ($stok < 0)           $error[] = 'Stok tidak boleh negatif.';

    if (empty($error)) {
        mysqli_query($conn, "UPDATE barang SET
            nama_barang = '$nama_barang',
            jenis       = '$jenis',
            harga_sewa  = '$harga_sewa',
            stok        = '$stok',
            status      = '$status'
            WHERE id_barang = '$id'");
        header("Location: index.php?pesan=edit");
        exit;
    }
    $row = array_merge($row, $_POST);
}
?>

<div class="main-content">
    <div class="topbar">
        <h5><i class="bi bi-pencil-square me-2"></i>Edit Barang</h5>
        <span class="text-muted" style="font-size:.85rem;">Admin &rsaquo; Data Barang &rsaquo; Edit</span>
    </div>

    <div class="card" style="max-width:600px">
        <div class="card-header py-3"><i class="bi bi-box-seam me-1"></i> Form Edit Barang</div>
        <div class="card-body p-4">

            <?php if (!empty($error)): ?>
                <div class="alert alert-danger">
                    <ul class="mb-0"><?php foreach ($error as $e) echo "<li>$e</li>"; ?></ul>
                </div>
            <?php endif; ?>

            <div class="mb-3">
                <label class="form-label fw-semibold">ID Barang</label>
                <input type="text" class="form-control" value="<?= $row['id_barang'] ?>" disabled>
            </div>

            <form method="POST">
                <div class="mb-3">
                    <label class="form-label fw-semibold">Nama Barang <span class="text-danger">*</span></label>
                    <input type="text" name="nama_barang" class="form-control"
                           value="<?= htmlspecialchars($row['nama_barang']) ?>">
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Jenis <span class="text-danger">*</span></label>
                    <select name="jenis" class="form-select">
                        <?php foreach (['Scaffolding', 'Clamp', 'Komponen Pendukung'] as $j): ?>
                            <option value="<?= $j ?>" <?= ($row['jenis'] === $j) ? 'selected' : '' ?>><?= $j ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Harga Sewa/Bulan (Rp) <span class="text-danger">*</span></label>
                    <input type="number" name="harga_sewa" class="form-control" min="0"
                           value="<?= htmlspecialchars($row['harga_sewa']) ?>">
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Stok (unit)</label>
                    <input type="number" name="stok" class="form-control" min="0"
                           value="<?= htmlspecialchars($row['stok']) ?>">
                </div>
                <div class="mb-4">
                    <label class="form-label fw-semibold">Status</label>
                    <select name="status" class="form-select">
                        <?php foreach (['tersedia', 'disewa', 'rusak'] as $s): ?>
                            <option value="<?= $s ?>" <?= ($row['status'] === $s) ? 'selected' : '' ?>><?= ucfirst($s) ?></option>
                        <?php endforeach; ?>
                    </select>
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
