<?php
session_start();
require "functions.php";

if (!isset($_SESSION["login"])) {
    header("Location: login.php");
    exit;
}

include 'db.php';

// Inisialisasi variabel pagination
$limit = 5; // Jumlah data per halaman
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1; // Halaman saat ini
$offset = ($page - 1) * $limit; // Hitung offset

$search = "";
if (isset($_GET['search'])) {
    $search = mysqli_real_escape_string($conn, $_GET['search']);
}

// Hitung total data untuk pagination
$sql_count = "SELECT COUNT(*) as total FROM produk";
if (!empty($search)) {
    $sql_count .= " WHERE nama_produk LIKE '%$search%'";
}

$result_count = mysqli_query($conn, $sql_count);
$row_count = mysqli_fetch_assoc($result_count);
$total_rows = $row_count['total'];
$total_pages = ceil($total_rows / $limit); // Total halaman

// Query untuk mengambil data produk dengan pagination
$sql = "SELECT id, nama_produk, produk_masuk, produk_keluar, (produk_masuk - produk_keluar) AS total 
        FROM produk";

if (!empty($search)) {
    $sql .= " WHERE nama_produk LIKE '%$search%'";
}

$sql .= " LIMIT $limit OFFSET $offset"; // Tambahkan LIMIT dan OFFSET untuk pagination

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
  <title>Dashboard Stok Produk</title>
  <link rel="stylesheet" href="css/dashboard.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>

<body>

  <!-- Hamburger Icon and Sidebar -->
  <div class="dashboard-header">
    <div class="hamburger">
      <i class="fas fa-bars" id="hamburger-icon"></i>
    </div>
    <h2>DASHBOARD</h2>
    <div class="search-container">
      <form action="" method="GET" autocomplete="off">
        <input type="text" name="search" placeholder="Cari" value="<?= htmlspecialchars($search); ?>">
      </form>
    </div>
  </div>

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
          <th>Produk Masuk</th>
          <th>Produk Keluar</th>
          <th>Total</th>
        </tr>
      </thead>
      <tbody>
        <?php
                if (mysqli_num_rows($result) > 0) {
                  $i = $offset + 1; // Untuk mengurutkan ID di tiap halaman
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "<tr>";
                        echo "<td>" . $i . "</td>";
                        echo "<td>" . $row["nama_produk"] . "</td>";
                        echo "<td>" . $row["produk_masuk"] . "</td>";
                        echo "<td>" . $row["produk_keluar"] . "</td>";
                        echo "<td>" . $row["total"] . "</td>";
                        $i++;
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='5'>Tidak ada data</td></tr>";
                }
                ?>
      </tbody>
    </table>

    <!-- Pagination Links -->
    <div class="pagination">
      <?php if ($page > 1): ?>
      <a href="?page=<?= $page - 1 ?>&search=<?= htmlspecialchars($search); ?>">&laquo; Previous</a>
      <?php endif; ?>

      <?php for ($i = 1; $i <= $total_pages; $i++): ?>
      <a href="?page=<?= $i ?>&search=<?= htmlspecialchars($search); ?>" class="<?= ($i == $page) ? 'active' : '' ?>">
        <?= $i ?>
      </a>
      <?php endfor; ?>

      <?php if ($page < $total_pages): ?>
      <a href="?page=<?= $page + 1 ?>&search=<?= htmlspecialchars($search); ?>">Next &raquo;</a>
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