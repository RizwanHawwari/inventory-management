<?php
session_start();
require "functions.php";

if (!isset($_SESSION["login"])) {
    header("Location: login.php");
    exit;
}

include 'db.php';

// Ambil data pencarian, jika ada
$search = isset($_GET['search']) ? mysqli_real_escape_string($conn, $_GET['search']) : '';

// Tentukan jumlah data per halaman
$limit = 5;

// Hitung total data
$totalQuery = "SELECT COUNT(*) AS total FROM produk WHERE nama_produk LIKE '%$search%'";
$totalResult = mysqli_query($conn, $totalQuery);
$totalRow = mysqli_fetch_assoc($totalResult);
$totalData = $totalRow['total'];

// Hitung total halaman
$totalPages = ceil($totalData / $limit);

// Ambil halaman saat ini (default halaman 1)
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$start = ($page - 1) * $limit;

// Query untuk mengambil data produk berdasarkan pencarian dan halaman
$sql = "SELECT id, nama_produk, produk_keluar AS jumlah, tanggal FROM produk 
        WHERE nama_produk LIKE '%$search%' 
        LIMIT $start, $limit";
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
  <title>Produk Keluar</title>
  <link rel="stylesheet" href="css/produk_keluar.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>

<body>

  <!-- Hamburger Icon and Sidebar -->
  <div class="dashboard-header">
    <div class="hamburger">
      <i class="fas fa-bars" id="hamburger-icon"></i>
    </div>
    <h2>PRODUK KELUAR</h2>
    <a href="addkeluar.php" class="addstock-container">
      <button class="add-stock add-stock-button">
        <span class="plus-icon">+</span>
        Tambahkan Stok
      </button>
    </a>
  </div>

  <!-- Search Bar -->
  <form method="GET" action="" class="search-bar">
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
          <th>Jumlah</th>
          <th>Tanggal</th>
        </tr>
      </thead>
      <tbody>
        <?php
        if (mysqli_num_rows($result) > 0) {
          $i = $start + 1; // Penomoran sesuai halaman
          while ($row = mysqli_fetch_assoc($result)) {
            echo "<tr>";
            echo "<td>" . $i . "</td>";
            echo "<td>" . htmlspecialchars($row["nama_produk"]) . "</td>";
            echo "<td>" . htmlspecialchars($row["jumlah"]) . "</td>";
            echo "<td>" . htmlspecialchars($row["tanggal"]) . "</td>";
            $i++;
            echo "</tr>";
          }
        } else {
          echo "<tr><td colspan='4'>Tidak ada data</td></tr>";
        }
        ?>
      </tbody>
    </table>

    <!-- Pagination -->
    <div class="pagination">
      <?php if ($page > 1): ?>
      <a href="?page=<?php echo $page - 1; ?>&search=<?php echo htmlspecialchars($search); ?>">&laquo; Previous</a>
      <?php endif; ?>

      <?php for ($i = 1; $i <= $totalPages; $i++): ?>
      <a href="?page=<?php echo $i; ?>&search=<?php echo htmlspecialchars($search); ?>"
        class="<?php echo ($i == $page) ? 'active' : ''; ?>">
        <?php echo $i; ?>
      </a>
      <?php endfor; ?>

      <?php if ($page < $totalPages): ?>
      <a href="?page=<?php echo $page + 1; ?>&search=<?php echo htmlspecialchars($search); ?>">Next &raquo;</a>
      <?php endif; ?>
    </div>
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