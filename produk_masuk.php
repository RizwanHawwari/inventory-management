<?php
session_start();
require "functions.php";

if (!isset($_SESSION["login"])) {
    header("Location: login.php");
    exit;
}

include 'db.php';

// Mengambil nilai pencarian
$search = isset($_GET['search']) ? mysqli_real_escape_string($conn, $_GET['search']) : '';

// Query untuk mengambil produk berdasarkan pencarian
$sql = "SELECT id, nama_produk, merk, produk_masuk, jumlah, tanggal FROM produk WHERE nama_produk LIKE '%$search%'";
$result = mysqli_query($conn, $sql);

if (!$result) {
    die("Query failed: " . mysqli_error($conn));
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Produk Masuk</title>
  <link rel="stylesheet" href="css/produk_masuk.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>

<body>

  <!-- Hamburger Icon and Sidebar -->
  <div class="dashboard-header">
    <div class="hamburger">
      <i class="fas fa-bars" id="hamburger-icon"></i>
    </div>
    <h2>PRODUK MASUK</h2>
    <a href="addstock.php">
      <button class="add-stock add-stock-button">
        <span class="plus-icon">+</span>
        Tambahkan Stok
      </button>
    </a>
  </div>

  <!-- Search Bar -->
  <form method="GET" action="" class="search-bar" autocomplete="off">
    <input type="text" name="search" placeholder="cari" class="search-input"
      value="<?php echo htmlspecialchars($search); ?>">
  </form>

  <div class="sidebar" id="sidebar">
    <div class="close-btn" id="close-btn">
      <i class="fas fa-times"></i>
    </div>
    <a href="index.php">
      <i class="fas fa-home"></i>
      <span>Dashboard</span>
    </a>
    <a href="produk_masuk.php">
      <i class="fas fa-shopping-cart"></i>
      <span>Produk Masuk</span>
    </a>
    <a href="produk_keluar.php">
      <i class="fas fa-tags"></i>
      <span>Produk Keluar</span>
    </a>
    <a href="logout.php">
      <i class="fas fa-sign-out-alt"></i>
      <span>Keluar</span>
    </a>
  </div>

  <div class="content-wrapper">
    <table>
      <thead>
        <tr>
          <th>ID</th>
          <th>Nama Produk</th>
          <th>Merk</th>
          <th>Produk Masuk</th>
          <th>Tanggal</th>
        </tr>
      </thead>
      <tbody>
        <?php
        if (mysqli_num_rows($result) > 0) {
          $i = 1;
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($i) . "</td>";
                echo "<td>" . htmlspecialchars($row["nama_produk"]) . "</td>";
                echo "<td>" . htmlspecialchars($row["merk"]) . "</td>";
                echo "<td>" . htmlspecialchars($row["produk_masuk"]) . "</td>";
                echo "<td>" . htmlspecialchars($row["tanggal"]) . "</td>";
                $i++;
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='6'>Tidak ada data yang ditemukan untuk '<strong>" . htmlspecialchars($search) . "</strong>'</td></tr>";
        }
        ?>
      </tbody>
    </table>
  </div>

  <script>
  const hamburgerIcon = document.getElementById('hamburger-icon');
  const sidebar = document.getElementById('sidebar');

  hamburgerIcon.addEventListener('click', () => {
    sidebar.classList.toggle('active');
  });

  const closeBtn = document.getElementById('close-btn');

  closeBtn.addEventListener('click', () => {
    sidebar.classList.remove('active');
  });
  </script>
</body>

</html>

<?php
mysqli_close($conn);
?>