<?php
session_start();
require "functions.php";

if (!isset($_SESSION["login"])) {
    header("Location: login.php");
    exit;
}

include "db.php";

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

$message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama_produk = $_POST['nama_produk'];
    $merk = $_POST['merk'];
    $jumlah = $_POST['jumlah'];
    $tanggal = $_POST['tanggal']; // Menangkap input tanggal

    // Cek apakah produk sudah ada di tabel produk
    $sqlCheckProduk = "SELECT jumlah, produk_masuk, tanggal FROM produk WHERE nama_produk = '$nama_produk'";
    $resultCheckProduk = $conn->query($sqlCheckProduk);

    // Cek apakah produk sudah ada di tabel produk_masuk
    $sqlCheckMasuk = "SELECT jumlah FROM produk WHERE nama_produk = '$nama_produk'";
    $resultCheckMasuk = $conn->query($sqlCheckMasuk);

    if ($resultCheckMasuk->num_rows > 0) {
        // Produk sudah ada di tabel produk_masuk
        $rowMasuk = $resultCheckMasuk->fetch_assoc();
        $jumlahLama = $rowMasuk['jumlah'];

        // Hitung jumlah baru
        $jumlahBaru = $jumlahLama + $jumlah;

        // Update jumlah produk_masuk
        $sqlUpdateMasuk = "UPDATE produk SET jumlah = $jumlahBaru, tanggal = '$tanggal' WHERE nama_produk = '$nama_produk'";
        
        if ($conn->query($sqlUpdateMasuk) === TRUE) {
            $message = "Jumlah stok produk masuk berhasil diperbarui menjadi $jumlahBaru!";
        } else {
            $message = "Error: " . $sqlUpdateMasuk . "<br>" . $conn->error;
        }
    } else {
        // Produk belum ada di tabel produk_masuk, insert produk baru
        $sqlInsertMasuk = "INSERT INTO produk_masuk (nama_produk, merk, jumlah, tanggal) VALUES ('$nama_produk', '$merk', '$jumlah', '$tanggal')";

        if ($conn->query($sqlInsertMasuk) === TRUE) {
            $message = "Stok produk berhasil ditambahkan!";
        } else {
            $message = "Error: " . $sqlInsertMasuk . "<br>" . $conn->error;
        }
    }

    // Update atau insert ke tabel produk
    if ($resultCheckProduk->num_rows > 0) {
        // Produk sudah ada, update jumlah dan tanggal
        $rowProduk = $resultCheckProduk->fetch_assoc();
        $jumlahProdukLama = $rowProduk['jumlah'];
        $produkMasukLama = $rowProduk['produk_masuk']; // Ambil produk_masuk lama

        // Hitung produk_masuk baru
        $produkMasukBaru = $produkMasukLama + $jumlah;

        $sqlUpdateProduk = "UPDATE produk SET produk_masuk = $produkMasukBaru, tanggal = '$tanggal' WHERE nama_produk = '$nama_produk'";
        $conn->query($sqlUpdateProduk);
    } else {
        // Produk belum ada, insert produk baru
        $sqlInsertProduk = "INSERT INTO produk (nama_produk, merk, jumlah, produk_masuk, tanggal) VALUES ('$nama_produk', '$merk', '$jumlah', '$jumlah', '$tanggal')";
        $conn->query($sqlInsertProduk);
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Tambahkan Stok</title>
  <style>
  body {
    font-family: Arial, sans-serif;
    background-color: #f5f5f5;
    margin: 0;
    padding: 0;
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
  }

  .container {
    width: 500px;
    margin: 0 auto;
    background-color: white;
    padding: 20px;
    border-radius: 5px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    position: relative;
  }

  h2 {
    text-align: center;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 20px;
  }

  .plus-icon {
    font-size: 24px;
    margin-right: 10px;
  }

  .divider {
    width: 100%;
    height: 1px;
    background-color: #ccc;
    margin: 10px 0;
  }

  label {
    margin: 10px 0 5px;
    display: block;
  }

  input[type="text"],
  input[type="number"],
  input[type="date"] {
    width: calc(100% - 20px);
    padding: 10px;
    margin: 10px 0;
    border: 1px solid #ccc;
    border-radius: 5px;
  }

  input[type="submit"] {
    background-color: #15beed;
    color: white;
    border: none;
    padding: 10px;
    border-radius: 5px;
    cursor: pointer;
    width: 100%;
  }

  input[type="submit"]:hover {
    background-color: #8a8db0;
  }

  .message {
    text-align: center;
    margin: 10px 0;
    color: green;
  }

  .close-button {
    position: absolute;
    top: 10px;
    right: 15px;
    background: none;
    border: none;
    font-size: 24px;
    cursor: pointer;
    color: #777;
  }

  .close-button:hover {
    color: red;
  }
  </style>
</head>

<body>
  <div class="container">
    <button class="close-button" onclick="window.location.href='produk_masuk.php'">&times;</button>
    <h2>
      <span class="plus-icon">+</span> Tambahkan Stok
    </h2>
    <div class="divider"></div>

    <?php if ($message): ?>
    <div class="message"><?php echo $message; ?></div>
    <?php endif; ?>

    <form method="POST" action="" autocomplete="off">
      <label for="nama_produk">Nama Produk</label>
      <input type="text" name="nama_produk" id="nama_produk" placeholder="Tambahkan nama produk" required>

      <label for="merk">Merek</label>
      <input type="text" name="merk" id="merk" placeholder="Tambahkan merk" required>

      <label for="jumlah">Jumlah</label>
      <input type="number" name="jumlah" id="jumlah" placeholder="Tambahkan jumlah" required>

      <label for="tanggal">Tanggal</label>
      <input type="date" name="tanggal" id="tanggal" required>

      <input type="submit" value="Simpan">
    </form>
  </div>
</body>

</html>