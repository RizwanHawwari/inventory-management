<?php
session_start();
require "functions.php";

if (!isset($_SESSION["login"])) {
    header("Location: login.php");
    exit;
}

// Include koneksi database
include 'db.php';

// Menangkap input pencarian jika ada
$search = "";
if (isset($_GET['search'])) {
    $search = mysqli_real_escape_string($conn, $_GET['search']);
}

// Query untuk mengambil data produk dengan fitur pencarian
$sql = "SELECT id, nama_produk, produk_masuk, produk_keluar, (produk_masuk - produk_keluar) AS total 
        FROM produk";

// Jika ada input pencarian, tambahkan kondisi pencarian ke query
if (!empty($search)) {
    $sql .= " WHERE nama_produk LIKE '%$search%'";
}

// Menjalankan query dan menyimpan hasilnya
$result = mysqli_query($conn, $sql);

// Periksa jika query berhasil
if (!$result) {
    die("Query failed: " . mysqli_error($conn)); // Tampilkan pesan kesalahan jika query gagal
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard Stok Produk</title>
  <link rel="stylesheet" href="css/style.css">
  <!-- CDN Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>

<body>

  <div class="sidebar">
    <a href="#"><i class="fas fa-home"></i></a>
    <a href="#"><i class="fas fa-shopping-cart"></i></a>
    <a href="#"><i class="fas fa-tags"></i></a> <!-- Icon Discount -->
    <a href="logout.php"><i class="fas fa-sign-out-alt"></i></a> <!-- Icon Log Out -->
    <a href="#"><i class="fas fa-cog"></i></a>
  </div>

  <!-- Wrapper untuk dashboard header dan tabel -->
  <div class="content-wrapper">
    <div class="dashboard-header">
      <h2>DASHBOARD</h2>
      <div class="search-container">
        <form action="" method="GET" autocomplete="off">
          <input type="text" name="search" placeholder="Cari" value="<?= htmlspecialchars($search); ?>">
        </form>
      </div>
    </div>

    <table>
      <thead>
        <tr>
          <th>ID</th>
          <th>Nama Produk</th>
          <th>Produk Masuk</th>
          <th>Produk Keluar</th>
          <th>Total</th>
        </tr>
      </thead>
      <tbody>
        <?php
            if (mysqli_num_rows($result) > 0) {
                // Menampilkan data produk
                while($row = mysqli_fetch_assoc($result)) {
                    echo "<tr>";
                    echo "<td>" . $row["id"] . "</td>";
                    echo "<td>" . $row["nama_produk"] . "</td>";
                    echo "<td>" . $row["produk_masuk"] . "</td>";
                    echo "<td>" . $row["produk_keluar"] . "</td>";
                    echo "<td>" . $row["total"] . "</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='5'>Tidak ada data</td></tr>";
            }
            ?>
      </tbody>
    </table>
  </div>

</body>

</html>

<?php
// Menutup koneksi
mysqli_close($conn);
?>