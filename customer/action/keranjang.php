<?php
session_start();
require_once __DIR__ . '/../../connect.php';

// Proteksi global: Siapapun yang mengakses file ini wajib login customer
if (!isset($_SESSION['id_customer'])) {
    header("Location: ../../login/login.php?pesan=wajib_login");
    exit;
}

$id_customer = $_SESSION['id_customer'];

// ── AKSES 1: JIKA MENERIMA REQUES TAMBAH BARANG (POST) ──
if (isset($_POST['tambah_keranjang'])) {
    $id_barang = mysqli_real_escape_string($conn, $_POST['id_barang']);
    
    $cek = mysqli_query($conn, "SELECT * FROM keranjang WHERE id_customer = '$id_customer' AND id_barang = '$id_barang'");
    
    if (mysqli_num_rows($cek) > 0) {
        mysqli_query($conn, "UPDATE keranjang SET jml_barang = jml_barang + 1 WHERE id_customer = '$id_customer' AND id_barang = '$id_barang'");
    } else {
        $id_keranjang = "KRJ" . rand(1000, 9999);
        mysqli_query($conn, "INSERT INTO keranjang (id_keranjang, id_barang, id_customer, jml_barang) VALUES ('$id_keranjang', '$id_barang', '$id_customer', 1)");
    }
    
    header("Location: ../view/index.php?pesan=sukses_keranjang");
    exit;
} 

// ── AKSES 2: JIKA MENERIMA REQUEST HAPUS BARANG (GET) ──
if (isset($_GET['hapus'])) {
    $id_krj = mysqli_real_escape_string($conn, $_GET['hapus']);
    
    // Eksekusi hapus data dari tabel keranjang
    mysqli_query($conn, "DELETE FROM keranjang WHERE id_keranjang = '$id_krj' AND id_customer = '$id_customer'");
    
    // Redirect kembali ke halaman keranjang belanjaan (bukan index) beserta alert sukses hapus
    header("Location: ../view/keranjang.php?pesan=terhapus");
    exit;
}

header("Location: ../view/index.php");
exit;
?>