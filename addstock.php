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

// Variabel untuk menyimpan pesan
$message = '';

// Proses form ketika disubmit
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama_produk = $_POST['nama_produk'];
    $merk = $_POST['merk'];
    $jumlah = $_POST['jumlah'];

    // Query untuk menambahkan data ke tabel produk
    $sql = "INSERT INTO produk (nama_produk, merk, produk_masuk) VALUES ('$nama_produk', '$merk', '$jumlah')";

    if ($conn->query($sql) === TRUE) {
        $message = "Stok berhasil ditambahkan!";
    } else {
        $message = "Error: " . $sql . "<br>" . $conn->error;
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
    position: relative;
    /* Untuk penempatan tombol close */
  }

  h2 {
    text-align: center;
    display: flex;
    align-items: center;
    /* Align icon and text vertically */
    justify-content: center;
    /* Center the content */
    margin-bottom: 20px;
    /* Space below the header */
  }

  .plus-icon {
    font-size: 24px;
    /* Size of the plus icon */
    margin-right: 10px;
    /* Space between icon and text */
  }

  .divider {
    width: 100%;
    /* Full width */
    height: 1px;
    /* Height of the line */
    background-color: #ccc;
    /* Color of the line */
    margin: 10px 0;
    /* Space above and below the line */
  }

  label {
    margin: 10px 0 5px;
    /* Space around the label */
    display: block;
    /* Block display for labels */
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
    background-color: #a6abd8;
    /* Changed button color */
    color: white;
    border: none;
    padding: 10px;
    border-radius: 5px;
    cursor: pointer;
    width: 100%;
  }

  input[type="submit"]:hover {
    background-color: #8a8db0;
    /* Slightly darker on hover */
  }

  .message {
    text-align: center;
    /* Center the message */
    margin: 10px 0;
    color: green;
    /* Color for success message */
  }

  .close-button {
    position: absolute;
    /* Position button in the top right */
    top: 10px;
    right: 15px;
    background: none;
    /* No background */
    border: none;
    /* No border */
    font-size: 24px;
    /* Font size */
    cursor: pointer;
    /* Pointer cursor */
    color: #777;
    /* Color for button */
  }

  .close-button:hover {
    color: red;
    /* Change color on hover */
  }
  </style>
</head>

<body>

  <div class="container">
    <button class="close-button" onclick="window.location.href='index.php'">&times;</button>
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

      <input type="submit" value="Simpan">
    </form>
  </div>

</body>

</html>