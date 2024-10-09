<?php
session_start();
require "functions.php";
include 'db.php';

if (!isset($_SESSION["login"])) {
    header("Location: login.php");
    exit;
}

if (isset($_GET['id'])) {
    $id = mysqli_real_escape_string($conn, $_GET['id']);
    
    $sql = "DELETE FROM produk WHERE id='$id'";
    
    if (mysqli_query($conn, $sql)) {
        header("Location: produk_masuk.php?message=Produk berhasil dihapus");
        exit;
    } else {
        die("Hapus gagal: " . mysqli_error($conn));
    }
} else {
    die("ID produk tidak ditemukan.");
}

mysqli_close($conn);
?>