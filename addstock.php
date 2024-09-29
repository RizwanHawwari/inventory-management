<?php
// Cek apakah form telah disubmit
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Ambil data dari form
    $nama_produk = $_POST['nama-produk'];
    $merek = $_POST['merek'];
    $jumlah = $_POST['jumlah'];

    // Lakukan koneksi ke database
    $conn = new mysqli("localhost", "root", "", "nama_database"); // Ganti dengan detail database yang sesuai

    // Cek koneksi
    if ($conn->connect_error) {
        die("Koneksi gagal: " . $conn->connect_error);
    }

    // Insert data ke tabel produk/stok
    $sql = "INSERT INTO stok (nama_produk, merek, jumlah) VALUES ('$nama_produk', '$merek', '$jumlah')";

    if ($conn->query($sql) === TRUE) {
        $message = "Stok berhasil ditambahkan!";
    } else {
        $message = "Error: " . $sql . "<br>" . $conn->error;
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Tambah Stok</title>
  <style>
  body {
    font-family: Arial, sans-serif;
    margin: 0;
    padding: 0;
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
    background-color: #f4f4f4;
  }

  .stock-container {
    background-color: white;
    border: 1px solid #ccc;
    border-radius: 8px;
    width: 90%;
    max-width: 400px;
    padding: 20px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
  }

  .stock-container h2 {
    margin-top: 0;
    font-size: 24px;
    display: flex;
    justify-content: space-between;
  }

  .stock-container input[type="text"] {
    width: 100%;
    padding: 10px;
    margin-top: 10px;
    margin-bottom: 20px;
    border: 1px solid #ccc;
    border-radius: 4px;
  }

  .stock-container input[type="submit"] {
    background-color: #5cb85c;
    color: white;
    padding: 10px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    width: 100%;
  }

  .stock-container input[type="submit"]:hover {
    background-color: #4cae4c;
  }

  .close-btn {
    font-size: 24px;
    cursor: pointer;
    color: black;
  }

  .message {
    color: green;
    /* Atau sesuaikan sesuai kebutuhan */
    margin-bottom: 10px;
  }

  .error {
    color: red;
    /* Atau sesuaikan sesuai kebutuhan */
  }
  </style>
</head>

<body>
  <div class="stock-container">
    <h2>
      Tambahkan Stok
      <span class="close-btn" onclick="window.history.back();">&times;</span>
    </h2>

    <?php if (isset($message)): ?>
    <div class="message"><?php echo $message; ?></div>
    <?php endif; ?>

    <form action="" method="POST">
      <label for="nama-produk">Nama Produk</label>
      <input type="text" id="nama-produk" name="nama-produk" placeholder="Tambahkan nama produk" required>

      <label for="merek">Merek</label>
      <input type="text" id="merek" name="merek" placeholder="Tambahkan merk" required>

      <label for="jumlah">Jumlah</label>
      <input type="text" id="jumlah" name="jumlah" placeholder="Tambahkan jumlah" required>

      <input type="submit" value="Tambah Stok">
    </form>
  </div>
</body>

</html>