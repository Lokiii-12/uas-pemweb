<?php
require_once '../../connect.php';
require_once '../../config/header.php';

if (isset($_GET['hapus'])) {
    $id = mysqli_real_escape_string($conn, $_GET['hapus']);
    mysqli_query($conn, "DELETE FROM pegawai WHERE id_pegawai = '$id'");
    header("Location: index.php?pesan=hapus");
    exit;
}

$result = mysqli_query($conn, "SELECT * FROM pegawai ORDER BY id_pegawai ASC");
?>

<div class="main-content">
    <div class="topbar">
        <h5><i class="bi bi-person-badge me-2"></i>Data Pegawai</h5>
        <span class="text-muted" style="font-size:.85rem;">Admin &rsaquo; Data Pegawai</span>
    </div>

    <?php if (isset($_GET['pesan'])): ?>
        <div class="alert alert-success alert-dismissible fade show">
            <?php
                if ($_GET['pesan'] === 'tambah') echo 'Pegawai berhasil ditambahkan.';
                elseif ($_GET['pesan'] === 'edit') echo 'Data pegawai berhasil diperbarui.';
                elseif ($_GET['pesan'] === 'hapus') echo 'Pegawai berhasil dihapus.';
            ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center py-3">
            <span><i class="bi bi-list-ul me-1"></i> Daftar Pegawai</span>
            <a href="tambah.php" class="btn btn-accent btn-sm">
                <i class="bi bi-plus-lg me-1"></i> Tambah Pegawai
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
                            <th>Peran</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (mysqli_num_rows($result) === 0): ?>
                            <tr>
                                <td colspan="8" class="text-center text-muted py-4">
                                    <i class="bi bi-inbox fs-4 d-block mb-1"></i>
                                    Belum ada data pegawai.
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php $no = 1; while ($row = mysqli_fetch_assoc($result)): ?>
                            <tr>
                                <td class="text-muted"><?= $no++ ?></td>
                                <td><code><?= $row['id_pegawai'] ?></code></td>
                                <td class="fw-semibold"><?= htmlspecialchars($row['nama_lengkap']) ?></td>
                                <td><?= htmlspecialchars($row['username']) ?></td>
                                <td><?= htmlspecialchars($row['no_telp']) ?></td>
                                <td class="text-truncate" style="max-width:150px"><?= htmlspecialchars($row['alamat']) ?></td>
                                <td>
                                    <?php if ($row['peran'] === 'admin'): ?>
                                        <span class="badge px-3 py-1 rounded-pill" style="background:#dbeafe;color:#1e40af">Admin</span>
                                    <?php else: ?>
                                        <span class="badge px-3 py-1 rounded-pill" style="background:#f3e8ff;color:#6b21a8">Petugas Lapangan</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <a href="edit.php?id=<?= $row['id_pegawai'] ?>" class="btn btn-sm btn-outline-primary me-1">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <a href="?hapus=<?= $row['id_pegawai'] ?>"
                                       class="btn btn-sm btn-outline-danger"
                                       onclick="return confirm('Yakin hapus pegawai ini?')">
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
