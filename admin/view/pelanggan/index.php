<?php
require_once 'connect.php';
require_once '../../config/header.php';

if (isset($_GET['hapus'])) {
    $id = mysqli_real_escape_string($conn, $_GET['hapus']);
    mysqli_query($conn, "DELETE FROM customer WHERE id_customer = '$id'");
    header("Location: index.php?pesan=hapus");
    exit;
}

$result = mysqli_query($conn, "SELECT * FROM customer ORDER BY id_customer ASC");
?>

<div class="main-content">
    <div class="topbar">
        <h5><i class="bi bi-people me-2"></i>Data Pelanggan</h5>
        <span class="text-muted" style="font-size:.85rem;">Admin &rsaquo; Data Pelanggan</span>
    </div>

    <?php if (isset($_GET['pesan'])): ?>
        <div class="alert alert-success alert-dismissible fade show">
            <?php
                if ($_GET['pesan'] === 'tambah') echo 'Pelanggan berhasil ditambahkan.';
                elseif ($_GET['pesan'] === 'edit') echo 'Data pelanggan berhasil diperbarui.';
                elseif ($_GET['pesan'] === 'hapus') echo 'Pelanggan berhasil dihapus.';
            ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center py-3">
            <span><i class="bi bi-list-ul me-1"></i> Daftar Pelanggan</span>
            <a href="tambah.php" class="btn btn-accent btn-sm">
                <i class="bi bi-plus-lg me-1"></i> Tambah Pelanggan
            </a>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>ID</th>
                            <th>Nama Lengkap</th>
                            <th>Username</th>
                            <th>No. Telepon</th>
                            <th>Alamat</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (mysqli_num_rows($result) === 0): ?>
                            <tr>
                                <td colspan="7" class="text-center text-muted py-4">
                                    <i class="bi bi-inbox fs-4 d-block mb-1"></i>
                                    Belum ada data pelanggan.
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php $no = 1; while ($row = mysqli_fetch_assoc($result)): ?>
                            <tr>
                                <td class="text-muted"><?= $no++ ?></td>
                                <td><code><?= $row['id_customer'] ?></code></td>
                                <td class="fw-semibold"><?= htmlspecialchars($row['nama_lengkap']) ?></td>
                                <td><?= htmlspecialchars($row['username']) ?></td>
                                <td><?= htmlspecialchars($row['no_telp']) ?></td>
                                <td class="text-truncate" style="max-width:180px"><?= htmlspecialchars($row['alamat']) ?></td>
                                <td>
                                    <a href="edit.php?id=<?= $row['id_customer'] ?>" class="btn btn-sm btn-outline-primary me-1">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <a href="?hapus=<?= $row['id_customer'] ?>"
                                       class="btn btn-sm btn-outline-danger"
                                       onclick="return confirm('Yakin hapus pelanggan ini?')">
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
