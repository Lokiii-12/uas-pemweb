<?php
require "connect.php";

$sql = "INSERT IGNORE INTO customer
(id_customer, nama_lengkap, username, alamat, password, no_telp)
VALUES
('CST001', 'Briliana', 'racheliacella', 'Jl. Merdeka No. 10, Klaten', '123456', '081234567890')
";
mysqli_query($conn, $sql);

echo "Data berhasil dimasukkan!";
?>