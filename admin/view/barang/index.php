<?php
require_once 'connect.php';
require_once '../../config/header.php';

if (isset($_GET['hapus'])) {
    $id = mysqli_real_escape_string($conn, $_GET['hapus']);
    mysqli_query($conn, "DELETE FROM barang WHERE id_barang = '$id'");
    header("Location: index.php?pesan=hapus");
    exit;
}

$result = mysqli_query($conn, "SELECT * FROM barang ORDER BY id_barang ASC");
?>

<div class="main-content">
    <div class="topbar">
        <h5><i class="bi bi-box-seam me-2"></i>Data Barang</h5>
        <span class="text-muted" style="font-size:.85rem;">Admin &rsaquo; Data Barang</span>
    </div>

    <?php if (isset($_GET['pesan'])): ?>
        <div class="alert alert-success alert-dismissible fade show">
            <?php
                if ($_GET['pesan'] === 'tambah') echo 'Barang berhasil ditambahkan.';
                elseif ($_GET['pesan'] === 'edit') echo 'Barang berhasil diperbarui.';
                elseif ($_GET['pesan'] === 'hapus') echo 'Barang berhasil dihapus.';
            ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center py-3">
            <span><i class="bi bi-list-ul me-1"></i> Daftar Barang</span>
            <a href="tambah.php" class="btn btn-accent btn-sm">
                <i class="bi bi-plus-lg me-1"></i> Tambah Barang
            </a>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>ID</th>
                            <th>Nama Barang</th>
                            <th>Jenis</th>
                            <th>Harga Sewa/Bulan</th>
                            <th>Stok</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (mysqli_num_rows($result) === 0): ?>
                            <tr>
                                <td colspan="8" class="text-center text-muted py-4">
                                    <i class="bi bi-inbox fs-4 d-block mb-1"></i>
                                    Belum ada data barang.
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php $no = 1; while ($row = mysqli_fetch_assoc($result)): ?>
                            <tr>
                                <td class="text-muted"><?= $no++ ?></td>
                                <td><code><?= $row['id_barang'] ?></code></td>
                                <td class="fw-semibold"><?= htmlspecialchars($row['nama_barang']) ?></td>
                                <td><?= htmlspecialchars($row['jenis']) ?></td>
                                <td>Rp <?= number_format($row['harga_sewa'], 0, ',', '.') ?></td>
                                <td><?= $row['stok'] ?> unit</td>
                                <td>
                                    <?php
                                        $badgeClass = match($row['status']) {
                                            'tersedia' => 'badge-tersedia',
                                            'disewa'   => 'badge-disewa',
                                            'rusak'    => 'badge-rusak',
                                            default    => 'bg-secondary text-white'
                                        };
                                    ?>
                                    <span class="badge <?= $badgeClass ?> px-3 py-1 rounded-pill">
                                        <?= ucfirst($row['status']) ?>
                                    </span>
                                </td>
                                <td>
                                    <a href="edit.php?id=<?= $row['id_barang'] ?>" class="btn btn-sm btn-outline-primary me-1">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <a href="?hapus=<?= $row['id_barang'] ?>"
                                       class="btn btn-sm btn-outline-danger"
                                       onclick="return confirm('Yakin hapus barang ini?')">
                                        <i class="bi bi-trash"></i>
                                    </a>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php require_once '../../config/footer.php'; ?>
