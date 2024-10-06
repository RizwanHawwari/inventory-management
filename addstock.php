<?php
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

    // Cek apakah produk sudah ada
    $sqlCheck = "SELECT jumlah FROM produk_masuk WHERE nama_produk = '$nama_produk'";
    $resultCheck = $conn->query($sqlCheck);

    if ($resultCheck->num_rows > 0) {
        // Produk sudah ada, ambil jumlah saat ini
        $row = $resultCheck->fetch_assoc();
        $jumlahLama = $row['jumlah'];

        // Hitung jumlah baru
        $jumlahBaru = $jumlahLama + $jumlah;

        // Update jumlah produk
        $sqlUpdate = "UPDATE produk_masuk SET jumlah = $jumlahBaru, tanggal = '$tanggal' WHERE nama_produk = '$nama_produk'";
        
        if ($conn->query($sqlUpdate) === TRUE) {
            $message = "Jumlah stok berhasil diperbarui menjadi $jumlahBaru!";
        } else {
            $message = "Error: " . $sqlUpdate . "<br>" . $conn->error;
        }
    } else {
        // Produk belum ada, insert produk baru
        $sqlInsert = "INSERT INTO produk_masuk (nama_produk, merk, jumlah, tanggal) VALUES ('$nama_produk', '$merk', '$jumlah', '$tanggal')";

        if ($conn->query($sqlInsert) === TRUE) {
            $message = "Stok berhasil ditambahkan!";
        } else {
            $message = "Error: " . $sqlInsert . "<br>" . $conn->error;
        }
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
    /* Menambahkan input date */
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