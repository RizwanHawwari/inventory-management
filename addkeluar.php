<?php
include "db.php";

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

$message = '';

// Ambil produk yang memiliki produk_masuk lebih dari 0
$sqlProduk = "SELECT nama_produk, merk FROM produk WHERE produk_masuk > 0";
$resultProduk = $conn->query($sqlProduk);

// Inisialisasi array produk dan merk
$products = [];
if ($resultProduk->num_rows > 0) {
    while ($rowProduk = $resultProduk->fetch_assoc()) {
        $products[$rowProduk['nama_produk']][] = $rowProduk['merk'];
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $nama_produk = $_POST['nama_produk'];
  $merek = $_POST['merek']; // Tambahkan ini
  $jumlah = $_POST['jumlah'];
  $tanggal = $_POST['tanggal'];

  // Cek apakah produk sudah terdaftar di produk_masuk
  $produkCheck = "SELECT * FROM produk WHERE nama_produk = '$nama_produk' AND merk = '$merek' AND produk_masuk > 0"; // Tambahkan cek untuk merek
  $rCheck = $conn->query($produkCheck);

  if ($rCheck->num_rows > 0) {
      // Ambil jumlah produk_masuk untuk validasi
      $sqlJumlahMasuk = "SELECT produk_masuk FROM produk WHERE nama_produk = '$nama_produk' AND merk = '$merek'"; // Tambahkan cek untuk merek
      $resultJumlahMasuk = $conn->query($sqlJumlahMasuk);
      $rowJumlahMasuk = $resultJumlahMasuk->fetch_assoc();
      $jumlahMasuk = $rowJumlahMasuk['produk_masuk'];

      // Validasi apakah jumlah keluar melebihi jumlah masuk
      if ($jumlah > $jumlahMasuk) {
          $message = "Error: Jumlah produk keluar tidak boleh melebihi jumlah produk masuk ($jumlahMasuk).";
      } else {
          // Cek apakah produk sudah ada di tabel produk untuk merek tertentu
          $sqlCheck = "SELECT produk_keluar FROM produk WHERE nama_produk = '$nama_produk' AND merk = '$merek'"; // Tambahkan cek untuk merek
          $resultCheck = $conn->query($sqlCheck);

          if ($resultCheck->num_rows > 0) {
              // Produk sudah ada, ambil jumlah saat ini
              $row = $resultCheck->fetch_assoc();
              $jumlahLama = $row['produk_keluar'];

              // Hitung jumlah baru
              $jumlahBaru = $jumlahLama + $jumlah;

              // Update jumlah produk untuk merek tertentu
              $sqlUpdate = "UPDATE produk SET produk_keluar = $jumlahBaru, tanggal = '$tanggal' WHERE nama_produk = '$nama_produk' AND merk = '$merek'"; // Tambahkan cek untuk merek

              if ($conn->query($sqlUpdate) === TRUE) {
                  $message = "Jumlah produk keluar berhasil diperbarui menjadi $jumlahBaru!";
              } else {
                  $message = "Error: " . $sqlUpdate . "<br>" . $conn->error;
              }
          } else {
              // Produk belum ada, insert produk baru ke tabel produk untuk merek tertentu
              $sqlInsert = "INSERT INTO produk (nama_produk, merk, produk_keluar, tanggal) VALUES ('$nama_produk', '$merek', '$jumlah', '$tanggal')"; // Tambahkan merek

              if ($conn->query($sqlInsert) === TRUE) {
                  $message = "Produk berhasil ditambahkan!";
              } else {
                  $message = "Error: " . $sqlInsert . "<br>" . $conn->error;
              }
          }
      }
  } else {
      // Jika produk tidak terdaftar di produk_masuk
      $message = "Penambahan produk keluar gagal! Produk '$nama_produk' dengan merek '$merek' belum terdaftar di produk masuk.";
  }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Tambahkan Stok Produk</title>
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
  input[type="date"],
  select {
    width: calc(100% - 20px);
    padding: 10px;
    margin: 10px 0;
    border: 1px solid #ccc;
    border-radius: 5px;
  }

  input[type="submit"] {
    background-color: #6488ea;
    color: white;
    border: none;
    padding: 10px;
    border-radius: 5px;
    cursor: pointer;
    width: 100%;
  }

  input[type="submit"]:hover {
    background-color: lightskyblue;
  }

  .message {
    text-align: center;
    margin: 10px 0;
    color: red;
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
    <button class="close-button" onclick="window.location.href='produk_keluar.php'">&times;</button>
    <h2>
      <span class="plus-icon">+</span> Tambahkan Produk
    </h2>
    <div class="divider"></div>

    <?php if ($message): ?>
    <div class="message"><?php echo $message; ?></div>
    <?php endif; ?>

    <form method="POST" action="" autocomplete="off">
      <label for="nama_produk">Nama Produk</label>
      <select name="nama_produk" id="nama_produk" required>
        <option value="">Pilih produk</option>
        <?php
        foreach ($products as $nama_produk => $merks) {
            echo "<option value='" . $nama_produk . "'>" . $nama_produk . "</option>";
        }
        ?>
      </select>

      <label for="merek">Merek</label>
      <select name="merek" id="merek" required>
        <option value="">Pilih Merek</option>
      </select>

      <label for="jumlah">Jumlah</label>
      <input type="number" name="jumlah" id="jumlah" placeholder="Tambahkan jumlah" required>

      <label for="tanggal">Tanggal</label>
      <input type="date" name="tanggal" id="tanggal" required>

      <input type="submit" value="Simpan">
    </form>
  </div>

  <script>
  // Data produk dan merk dari PHP
  const products = <?php echo json_encode($products); ?>;

  const namaProdukSelect = document.getElementById('nama_produk');
  const merkSelect = document.getElementById('merek');

  // Ketika nama produk dipilih, update pilihan merek
  namaProdukSelect.addEventListener('change', function() {
    const selectedProduct = this.value;
    merkSelect.innerHTML = '<option value="">Pilih Merek</option>'; // Reset pilihan merek

    if (selectedProduct && products[selectedProduct]) {
      products[selectedProduct].forEach(function(merk) {
        const option = document.createElement('option');
        option.value = merk;
        option.textContent = merk;
        merkSelect.appendChild(option);
      });
    }
  });
  </script>
</body>

</html>