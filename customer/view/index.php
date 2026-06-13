<?php
session_start();
require_once __DIR__ . '/../../connect.php';

// Hitung total item di keranjang jika customer sudah login
$jumlah_keranjang = 0;
if (isset($_SESSION['id_customer'])) {
    $id_cust = $_SESSION['id_customer'];
    $q_cart = mysqli_query($conn, "SELECT SUM(jml_barang) as total FROM keranjang WHERE id_customer = '$id_cust'");
    $data_cart = mysqli_fetch_assoc($q_cart);
    $jumlah_keranjang = $data_cart['total'] ?? 0;
}

$result = mysqli_query($conn, "SELECT * FROM barang WHERE status = 'tersedia' ORDER BY id_barang ASC");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Katalog Penyewaan Scaffolding</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <link href="../css/style.css" rel="stylesheet">
</head>
<body>

<header class="ecommerce-header">
    <div class="container">
        <div class="row align-items-center g-3">
            <div class="col-md-3 text-center text-md-start">
                <a href="index.php" class="text-white text-decoration-none">
                    <h4 class="m-0 fw-bold"><i class="bi bi-building me-2"></i>SCAFFOLD<span style="color: var(--accent)">RENT</span></h4>
                </a>
            </div>
            <div class="col-md-6">
                <form method="GET" action="">
                    <div class="input-group search-box">
                        <input type="text" name="cari" class="form-control" placeholder="Cari main frame, cross brace, atau clamp..." value="<?= isset($_GET['cari']) ? htmlspecialchars($_GET['cari']) : '' ?>">
                        <button class="btn btn-link text-muted" type="submit"><i class="bi bi-search"></i></button>
                    </div>
                </form>
            </div>
            <div class="col-md-3 text-center text-md-end">
                <a href="keranjang.php" class="me-3 position-relative text-white text-decoration-none">
                    <i class="bi bi-cart3 fs-5"></i>
                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size: .65rem;"><?= $jumlah_keranjang ?></span>
                </a>
                <span class="text-white-50 me-2">|</span>
                
                <?php if (isset($_SESSION['username'])): ?>
                    <span class="fw-semibold text-white" style="font-size: .9rem;">
                        <i class="bi bi-person-check-fill text-success me-1"></i> <?= htmlspecialchars($_SESSION['username']) ?>
                    </span>
                    <a href="../../login/login.php?logout=true" class="text-warning text-decoration-none small ms-2">(Logout)</a>
                <?php else: ?>
                    <a href="../../login/login.php" class="text-white text-decoration-none fw-semibold" style="font-size: .9rem;">
                        <i class="bi bi-box-arrow-in-right me-1"></i> Sign In / Sign Up
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</header>

<main class="container mb-5">
    <div class="promo-banner">
        <div class="col-md-7">
            <span class="badge bg-warning text-dark mb-2 fw-bold text-uppercase">Gedung &amp; Konstruksi</span>
            <h2 class="fw-bold mb-2">Sewa Scaffolding Premium Aman &amp; Terpercaya</h2>
            <p class="text-white-50 mb-0">Semua item telah melewati uji kelayakan standar keselamatan kerja. Siap kirim langsung ke proyek Anda.</p>
        </div>
    </div>

    <?php if(isset($_GET['pesan']) && $_GET['pesan'] == 'sukses_keranjang'): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i> Berhasil ditambahkan ke keranjang belanja! <a href="keranjang.php" class="fw-bold text-success text-decoration-underline">Lihat Keranjang</a>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h5 class="fw-bold m-0 text-dark"><i class="bi bi-grid-fill text-primary me-2"></i>Semua Produk Tersedia</h5>
    </div>

    <div class="product-grid">
        <?php 
        if (isset($_GET['cari']) && $_GET['cari'] != '') {
            $cari = mysqli_real_escape_string($conn, $_GET['cari']);
            $result = mysqli_query($conn, "SELECT * FROM barang WHERE status = 'tersedia' AND nama_barang LIKE '%$cari%' ORDER BY id_barang ASC");
        }

        if (mysqli_num_rows($result) === 0): 
        ?>
            <div class="text-center text-muted py-5" style="grid-column: 1 / -1;">
                <i class="bi bi-inbox fs-1 d-block mb-2 text-secondary"></i>
                <p class="mb-0">Produk tidak ditemukan atau sedang habis.</p>
            </div>
        <?php 
        else: 
            while ($row = mysqli_fetch_assoc($result)): 
        ?>
            <div class="product-card">
                <div class="product-img-placeholder">
                    <i class="bi bi-box-seam"></i>
                    <span class="product-category"><?= htmlspecialchars($row['jenis']) ?></span>
                </div>
                
                <div class="product-body">
                    <div class="product-title" title="<?= htmlspecialchars($row['nama_barang']) ?>">
                        <?= htmlspecialchars($row['nama_barang']) ?>
                    </div>
                    <div class="product-price">
                        Rp <?= number_format($row['harga_sewa'], 0, ',', '.') ?><span class="text-muted fw-normal" style="font-size: .75rem;">/set/hari</span>
                    </div>
                    <div class="product-stock">
                        <small><i class="bi bi-check2-circle text-success me-1"></i>Stok: <strong><?= $row['stok'] ?></strong> unit</small>
                    </div>
                    
                    <div class="mt-auto">
                        <?php if (isset($_SESSION['id_customer'])): ?>
                            <form action="../action/keranjang.php" method="POST">
                                <input type="hidden" name="id_barang" value="<?= $row['id_barang'] ?>">
                                <button type="submit" name="tambah_keranjang" class="btn btn-accent">
                                    <i class="bi bi-cart-plus me-1"></i> Sewa Sekarang
                                </button>
                            </form>
                        <?php else: ?>
                            <a href="../../login/login.php?pesan=wajib_login" class="btn btn-accent text-center d-block">
                                <i class="bi bi-cart-plus me-1"></i> Sewa Sekarang
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php 
            endwhile; 
        endif; 
        ?>
    </div>
</main>

</body>
</html>