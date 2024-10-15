<?php
session_start();
require "functions.php";

if (!isset($_SESSION["login"])) {
    header("Location: login.php");
    exit;
}

include 'db.php';

if (isset($_GET['id'])) {
    $id = mysqli_real_escape_string($conn, $_GET['id']);
    
    // Query untuk menghapus data
    $sql = "DELETE FROM riwayat_perubahan WHERE id = '$id'";
    
    if (mysqli_query($conn, $sql)) {
        header("Location: riwayat.php?message=Data berhasil dihapus."); // Ganti dengan nama file halaman yang sesuai
        exit;
    } else {
        echo "Error deleting record: " . mysqli_error($conn);
    }
}

mysqli_close($conn);
?>