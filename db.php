<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "inventory_db";

// Membuat koneksi ke database
$conn = mysqli_connect($servername, $username, $password, $database);

// Cek koneksi
if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}
?>