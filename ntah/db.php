<?php
require "connect.php";
$sql = "CREATE TABLE IF NOT EXISTS barang
(id_barang VARCHAR(20) NOT NULL,
nama_barang VARCHAR(20) NOT NULL,
jenis VARCHAR(20) NOT NULL,
stok INT(1) NOT NULL,
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

?>