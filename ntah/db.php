<?php
require "connect.php";

$sql = "CREATE TABLE IF NOT EXISTS barang (
    id_barang VARCHAR(20) NOT NULL,
    nama_barang VARCHAR(20) NOT NULL,
    jenis VARCHAR(20) NOT NULL,
    stok INT NOT NULL,
    harga_sewa DECIMAL(10,2) NOT NULL,
    status VARCHAR(20) NULL,
    PRIMARY KEY(id_barang)
)";
mysqli_query($conn, $sql);

$sql = "CREATE TABLE IF NOT EXISTS customer (
    id_customer VARCHAR(20) NOT NULL,
    nama_lengkap VARCHAR(50) NOT NULL,
    username VARCHAR(20) NULL,
    alamat VARCHAR(100) NOT NULL,
    password VARCHAR(255) NOT NULL,
    no_telp VARCHAR(20) NOT NULL,
    PRIMARY KEY(id_customer)
)";
mysqli_query($conn, $sql);

$sql = "CREATE TABLE IF NOT EXISTS admin (
    id_admin VARCHAR(20) NOT NULL,
    nama_lengkap VARCHAR(50) NOT NULL,
    username VARCHAR(20) NULL,
    password VARCHAR(255) NOT NULL,
    alamat VARCHAR(100) NOT NULL,
    no_telp VARCHAR(20) NOT NULL,
    PRIMARY KEY(id_admin)
)";
mysqli_query($conn, $sql);

$sql = "CREATE TABLE IF NOT EXISTS orders (
    id_order VARCHAR(20) NOT NULL,
    id_customer VARCHAR(20) NOT NULL,
    tgl_order DATETIME NOT NULL,
    lokasi_proyek VARCHAR(100) NOT NULL,
    status VARCHAR(20) NOT NULL,
    PRIMARY KEY(id_order)
)";
mysqli_query($conn, $sql);

$sql = "CREATE TABLE IF NOT EXISTS kontrak (
    id_kontrak VARCHAR(20) NOT NULL,
    id_order VARCHAR(20) NOT NULL,
    id_customer VARCHAR(20) NOT NULL,
    id_admin VARCHAR(20) NOT NULL,
    tgl_kontrak DATE NOT NULL,
    tgl_jatuh_tempo DATETIME NOT NULL,
    PRIMARY KEY(id_kontrak)
)";
mysqli_query($conn, $sql);

$sql = "CREATE TABLE IF NOT EXISTS keranjang (
    id_keranjang VARCHAR(20) NOT NULL,
    id_barang VARCHAR(20) NOT NULL,
    id_customer VARCHAR(20) NOT NULL,
    jml_barang INT UNSIGNED NOT NULL,
    PRIMARY KEY(id_keranjang)
)";
mysqli_query($conn, $sql);

$sql = "CREATE TABLE IF NOT EXISTS detail_order (
    id_detail_order VARCHAR(20) NOT NULL,
    id_order VARCHAR(20) NOT NULL,
    id_barang VARCHAR(20) NOT NULL,
    jumlah_unit INT NOT NULL,
    PRIMARY KEY(id_detail_order)
)";
mysqli_query($conn, $sql);

$sql = "CREATE TABLE IF NOT EXISTS pembayaran (
    id_pembayaran VARCHAR(20) NOT NULL,
    id_order VARCHAR(20) NOT NULL,
    jumlah_bayar DECIMAL(10,2) NOT NULL,
    metode_bayar VARCHAR(20) NOT NULL,
    status VARCHAR(20) NOT NULL,
    tgl_bayar DATE NOT NULL,
    PRIMARY KEY(id_pembayaran)
)";
mysqli_query($conn, $sql);


// orders -> customer
$sql = "ALTER TABLE orders 
    ADD FOREIGN KEY (id_customer) REFERENCES customer(id_customer)";
mysqli_query($conn, $sql);

// kontrak -> orders, customer, admin
$sql = "ALTER TABLE kontrak 
    ADD FOREIGN KEY (id_order) REFERENCES orders(id_order)";
mysqli_query($conn, $sql);

$sql = "ALTER TABLE kontrak 
    ADD FOREIGN KEY (id_customer) REFERENCES customer(id_customer)";
mysqli_query($conn, $sql);

$sql = "ALTER TABLE kontrak 
    ADD FOREIGN KEY (id_admin) REFERENCES admin(id_admin)";
mysqli_query($conn, $sql);

// keranjang -> customer, barang
$sql = "ALTER TABLE keranjang 
    ADD FOREIGN KEY (id_customer) REFERENCES customer(id_customer)";
mysqli_query($conn, $sql);

$sql = "ALTER TABLE keranjang 
    ADD FOREIGN KEY (id_barang) REFERENCES barang(id_barang)";
mysqli_query($conn, $sql);

// detail_order -> orders, barang
$sql = "ALTER TABLE detail_order 
    ADD FOREIGN KEY (id_order) REFERENCES orders(id_order)";
mysqli_query($conn, $sql);

$sql = "ALTER TABLE detail_order 
    ADD FOREIGN KEY (id_barang) REFERENCES barang(id_barang)";
mysqli_query($conn, $sql);

// pembayaran -> orders
$sql = "ALTER TABLE pembayaran 
    ADD FOREIGN KEY (id_order) REFERENCES orders(id_order)";
mysqli_query($conn, $sql);

echo "Semua tabel dan relasi berhasil dibuat!";
?>