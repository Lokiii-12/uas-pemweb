<?php
require "connect.php";
$sql = "CREATE TABLE IF NOT EXISTS barang
(id_barang VARCHAR(20) NOT NULL,
nama_barang VARCHAR(20) NOT NULL,
jenis VARCHAR(20) NOT NULL,
stok INT NOT NULL,
status VARCHAR(20) NULL,
PRIMARY KEY(id_barang)
)";
$hasil = mysqli_query($conn, $sql);

$sql = "CREATE TABLE IF NOT EXISTS customer
(id_customer VARCHAR(20) NOT NULL,
nama_lengkap VARCHAR(20) NOT NULL,
username VARCHAR(20) NULL,
alamat VARCHAR(20) NOT NULL,
password VARCHAR(10) NOT NULL,
no_telp VARCHAR(20) NOT NULL,
PRIMARY KEY(id_customer)
)";
$hasil = mysqli_query($conn, $sql);
$sql = "CREATE TABLE IF NOT EXISTS kontrak
(id_kontrak VARCHAR(20) NOT NULL,
id_order VARCHAR(20) NOT NULL,
id_customer VARCHAR(20) NOT NULL,
tgl_proyek DATE NOT NULL,
tgl_jatuh_tempo DATETIME NOT NULL,
PRIMARY KEY(id_kontrak)
)";
$hasil = mysqli_query($conn, $sql);

$sql = "CREATE TABLE IF NOT EXISTS admin
(id_admin VARCHAR(20) NOT NULL,
nama_lengkap VARCHAR(20) NOT NULL,
username VARCHAR(20) NULL,
password VARCHAR(20) NOT NULL,
alamat VARCHAR(20) NOT NULL,
no_telepon VARCHAR(20) NOT NULL,
PRIMARY KEY(id_admin)
)";
$hasil = mysqli_query($conn, $sql);
$sql = "CREATE TABLE IF NOT EXISTS order
(id_order VARCHAR(20) NOT NULL,
id_customer VARCHAR(20) NOT NULL,
tgl_order DATETIME NOT NULL,
lokasi_proyek VARCHAR(20) NOT NULL,
status VARCHAR(20) NOT NULL,
PRIMARY KEY(id_order)
)";
$hasil = mysqli_query($conn, $sql);
$sql = "CREATE TABLE IF NOT EXISTS keranjang
(id_keranjang VARCHAR(20) NOT NULL,
id_barang VARCHAR(20) NOT NULL,
id_customer VARCHAR(20) NOT NULL,
jumlah_barang INT UNSIGNED NOT NULL,
PRIMARY KEY(id_keranjang)
)";
$hasil = mysqli_query($conn, $sql);
$sql = "CREATE TABLE IF NOT EXISTS detail_order
(id_detail_order VARCHAR(10) NOT NULL,
id_order VARCHAR(10) NOT NULL,
id_barang VARCHAR(10) NOT NULL,
jumlah_unit INT NOT NULL,
PRIMARY KEY(id_detail_order)
)";
$hasil = mysqli_query($conn, $sql);
$sql = "CREATE TABLE IF NOT EXISTS pembayaran
(id_pembayaran VARCHAR(20) NOT NULL,
id_order VARCHAR(20) NOT NULL,
jumlah_bayar DECIMAL(10,2) NOT NULL,
metode_bayar VARCHAR(20) NOT NULL,
status VARCHAR(20) NOT NULL,
tanggal_bayar DATE NOT NULL,
PRIMARY KEY(id_pembayaran)
)";
?>