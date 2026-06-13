<?php
require_once 'connect.php';
require_once '../../config/header.php';

$error = [];

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
        // Generate ID otomatis: BRG001, BRG002, dst
        $last = mysqli_fetch_assoc(mysqli_query($conn, "SELECT id_barang FROM barang ORDER BY id_barang DESC LIMIT 1"));
        if ($last) {
            $num = (int) substr($last['id_barang'], 3) + 1;
        } else {
            $num = 1;
        }
        $id_barang = 'BRG' . str_pad($num, 3, '0', STR_PAD_LEFT);

        $sql = "INSERT INTO barang (id_barang, nama_barang, jenis, stok, harga_sewa, status)
                VALUES ('$id_barang', '$nama_barang', '$jenis', '$stok', '$harga_sewa', '$status')";
        mysqli_query($conn, $sql);
        header("Location: index.php?pesan=tambah");
        exit;
    }
}
?>

<div class="main-content">
    <div class="topbar">
        <h5><i class="bi bi-plus-circle me-2"></i>Tambah Barang</h5>
        <span class="text-muted" style="font-size:.85rem;">Admin &rsaquo; Data Barang &rsaquo; Tambah</span>
    </div>

    <div class="card" style="max-width:600px">
        <div class="card-header py-3"><i class="bi bi-box-seam me-1"></i> Form Tambah Barang</div>
        <div class="card-body p-4">

            <?php if (!empty($error)): ?>
                <div class="alert alert-danger">
                    <ul class="mb-0"><?php foreach ($error as $e) echo "<li>$e</li>"; ?></ul>
                </div>
            <?php endif; ?>

            <form method="POST">
                <div class="mb-3">
                    <label class="form-label fw-semibold">Nama Barang <span class="text-danger">*</span></label>
                    <input type="text" name="nama_barang" class="form-control"
                           value="<?= htmlspecialchars($_POST['nama_barang'] ?? '') ?>"
                           placeholder="cth. Main Frame MF 170">
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Jenis <span class="text-danger">*</span></label>
                    <select name="jenis" class="form-select">
                        <option value="">-- Pilih Jenis --</option>
                        <?php foreach (['Scaffolding', 'Clamp', 'Komponen Pendukung'] as $j): ?>
                            <option value="<?= $j ?>" <?= (($_POST['jenis'] ?? '') === $j) ? 'selected' : '' ?>><?= $j ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Harga Sewa/Bulan (Rp) <span class="text-danger">*</span></label>
                    <input type="number" name="harga_sewa" class="form-control" min="0"
                           value="<?= htmlspecialchars($_POST['harga_sewa'] ?? '') ?>"
                           placeholder="cth. 30000">
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Stok (unit) <span class="text-danger">*</span></label>
                    <input type="number" name="stok" class="form-control" min="0"
                           value="<?= htmlspecialchars($_POST['stok'] ?? '') ?>">
                </div>
                <div class="mb-4">
                    <label class="form-label fw-semibold">Status</label>
                    <select name="status" class="form-select">
                        <?php foreach (['tersedia', 'disewa', 'rusak'] as $s): ?>
                            <option value="<?= $s ?>" <?= (($_POST['status'] ?? 'tersedia') === $s) ? 'selected' : '' ?>><?= ucfirst($s) ?></option>
                        <?php endforeach; ?>
                    </select>
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
