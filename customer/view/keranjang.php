<?php
session_start();
require_once __DIR__ . '/../../connect.php';

// Proteksi halaman, wajib login terlebih dahulu
if (!isset($_SESSION['id_customer'])) {
    header("Location: login.php?pesan=wajib_login");
    exit;
}

$id_customer = $_SESSION['id_customer'];

// Query Inner Join untuk menarik detail barang yang ada di dalam keranjang
$query = "SELECT k.id_keranjang, k.jml_barang, b.nama_barang, b.jenis, b.harga_sewa 
          FROM keranjang k 
          JOIN barang b ON k.id_barang = b.id_barang 
          WHERE k.id_customer = '$id_customer'";
$result = mysqli_query($conn, $query);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Keranjang Sewa Saya</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <link href="../css/style.css" rel="stylesheet">
</head>
<body>

<header class="ecommerce-header">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center">
            <h4 class="m-0 fw-bold"><a href="index.php" class="text-white text-decoration-none"><i class="bi bi-arrow-left me-2"></i>Keranjang Belanja</a></h4>
            <span class="fw-semibold" style="font-size: .9rem;"><i class="bi bi-person-circle me-1"></i> <?= htmlspecialchars($_SESSION['username']) ?></span>
        </div>
    </div>
</header>

<main class="container mb-5">
    <?php if(isset($_GET['pesan']) && $_GET['pesan'] == 'terhapus'): ?>
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
            <i class="bi bi-trash-fill me-2"></i> Item berhasil dihapus dari keranjang.
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card p-3 shadow-sm border-0 bg-white">
                <h5 class="fw-bold text-dark mb-3"><i class="bi bi-cart-check text-primary me-2"></i>Daftar Scaffolding</h5>
                
                <?php 
                $total_bayar_per_hari = 0;
                if (mysqli_num_rows($result) === 0): 
                ?>
                    <div class="text-center py-5 text-muted">
                        <i class="bi bi-cart-x fs-1 d-block mb-2"></i>
                        Keranjang Anda masih kosong. <a href="index.php" class="text-primary">Mulai Sewa Alat</a>
                    </div>
                <?php 
                else: 
                    while($row = mysqli_fetch_assoc($result)): 
                        $subtotal = $row['harga_sewa'] * $row['jml_barang'];
                        $total_bayar_per_hari += $subtotal;
                ?>
                    <div class="d-flex align-items-center justify-content-between border-bottom py-3">
                        <div class="d-flex align-items-center">
                            <div class="bg-light rounded p-3 text-primary text-center me-3" style="width: 60px; height: 60px;">
                                <i class="bi bi-box-seam fs-4"></i>
                            </div>
                            <div>
                                <h6 class="fw-bold mb-1 text-dark"><?= htmlspecialchars($row['nama_barang']) ?></h6>
                                <span class="badge bg-secondary mb-1" style="font-size:0.7rem;"><?= htmlspecialchars($row['jenis']) ?></span>
                                <div class="text-muted small">Rp <?= number_format($row['harga_sewa'], 0, ',', '.') ?> &times; <?= $row['jml_barang'] ?> Unit</div>
                            </div>
                        </div>
                        <div class="text-end">
                            <span class="fw-bold text-primary d-block">Rp <?= number_format($subtotal, 0, ',', '.') ?></span>
                            <a href="../action/keranjang.php?hapus=<?= $row['id_keranjang'] ?>" class="btn btn-sm btn-outline-danger border-0 mt-1" onclick="return confirm('Hapus item ini dari keranjang?')">
                                <i class="bi bi-trash"></i>
                            </a>
                        </div>
                    </div>
                <?php 
                    endwhile; 
                endif; 
                ?>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card p-4 shadow-sm border-0 bg-white text-dark">
                <h5 class="fw-bold mb-3">Ringkasan Sewa</h5>
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted">Total Estimasi (Per Hari)</span>
                    <span class="fw-bold text-dark">Rp <?= number_format($total_bayar_per_hari, 0, ',', '.') ?></span>
                </div>
                <hr>
                
                <form action="proses_checkout.php" method="POST">
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Lokasi Proyek Pengiriman</label>
                        <textarea class="form-control" name="lokasi_proyek" rows="3" placeholder="Tuliskan alamat lengkap proyek lapangan..." required></textarea>
                    </div>
                    <button type="submit" class="btn btn-accent w-100 py-2 mt-2" <?= ($total_bayar_per_hari == 0) ? 'disabled' : '' ?>>
                        <i class="bi bi-credit-card me-2"></i> Buat Pesanan Sewa
                    </button>
                </form>
            </div>
        </div>
    </div>
</main>

</body>
</html>