<?php
require "connect.php";
$sql = "CREATE TABLE IF NOT EXISTS barang
(id_barang VARCHAR(20) NOT NULL,
nama_barang VARCHAR(20) NOT NULL,
jenis VARCHAR(20) NOT NULL,
stok INT(1) NOT NULL,
status VARCHAR(20) NULL
)";


?>