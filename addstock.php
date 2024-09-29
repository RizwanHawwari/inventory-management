<?php
// Koneksi ke database
$servername = "localhost"; // Ganti dengan server Anda jika berbeda
$username = "root"; // Ganti dengan username database Anda
$password = ""; // Ganti dengan password database Anda
$dbname = "inventory_db"; // Nama database

// Buat koneksi
$conn = new mysqli($servername, $username, $password, $dbname);

// Cek koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Proses form ketika disubmit
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama_produk = $_POST['nama_produk'];
    $merk = $_POST['merk'];
    $jumlah = $_POST['jumlah'];

    // Query untuk menambahkan data ke tabel produk
    $sql = "INSERT INTO produk (nama_produk, merk, produk_masuk) VALUES ('$nama_produk', '$merk', '$jumlah')";

    if ($conn->query($sql) === TRUE) {
        echo "Stok berhasil ditambahkan!";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

// Tutup koneksi
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Tambahkan Stok</title>
  <style>
  /* CSS styles as per your design */
  body {
    font-family: Arial, sans-serif;
    background-color: #f5f5f5;
    margin: 0;
    padding: 0;
    display: flex;
    justify-content: center;
    /* Center horizontally */
    align-items: center;
    /* Center vertically */
    height: 100vh;
    /* Full height of the viewport */
  }

  .container {
    width: 500px;
    /* Lebar container yang lebih besar */
    margin: 0 auto;
    /* Center the container */
    background-color: white;
    padding: 20px;
    border-radius: 5px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
  }

  h2 {
    text-align: center;
  }

  input[type="text"],
  input[type="number"] {
    width: calc(100% - 20px);
    /* 100% lebar dikurangi padding */
    padding: 10px;
    margin: 10px 0;
    border: 1px solid #ccc;
    border-radius: 5px;
  }

  input[type="submit"] {
    background-color: #4CAF50;
    color: white;
    border: none;
    padding: 10px;
    border-radius: 5px;
    cursor: pointer;
    width: 100%;
  }

  input[type="submit"]:hover {
    background-color: #45a049;
  }
  </style>
</head>

<body>

  <div class="container">
    <h2>Tambahkan Stok</h2>
    <form method="POST" action="">
      <input type="text" name="nama_produk" placeholder="Tambahkan nama produk" required>
      <input type="text" name="merk" placeholder="Tambahkan merk" required>
      <input type="number" name="jumlah" placeholder="Tambahkan jumlah" required>
      <input type="submit" value="Simpan">
    </form>
  </div>

</body>

</html>