<?php
$host = "localhost";
$user = "root";
$pass = "";
$dbname = "eskafolding";
try
{
    $conn = mysqli_connect($host, $user, $pass, $dbname);
    
    }
catch (mysqli_sql_exception $e)
{
    die("GAGAL KONEKSI KE SERVER DATABASE: " . $e->getMessage());
}
?>