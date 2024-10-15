<?php
session_start();
require "functions.php";

if (!isset($_SESSION["login"])) {
    header("Location: login.php");
    exit;
}

include 'db.php';

// Inisialisasi variabel pagination
$limit = 5;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1; 
$offset = ($page - 1) * $limit;

$search = "";
if (isset($_GET['search'])) {
    $search = mysqli_real_escape_string($conn, $_GET['search']);
}

// Hitung total data untuk pagination
$sql_count = "SELECT COUNT(*) as total FROM riwayat_perubahan rp JOIN produk p ON rp.nama_produk = p.nama_produk AND rp.merk = p.merk";
if (!empty($search)) {
    $sql_count .= " WHERE rp.nama_produk LIKE '%$search%'"; // Ganti nama_produk untuk pencarian
}

$result_count = mysqli_query($conn, $sql_count);
$row_count = mysqli_fetch_assoc($result_count);
$total_rows = $row_count['total'];
$total_pages = ceil($total_rows / $limit); // Total halaman

// Query untuk mengambil data dari tabel riwayat_perubahan dengan pagination
$sql = "SELECT rp.id, rp.nama_produk, rp.merk, rp.perubahan, rp.penerima_barang, rp.jenis_perubahan, rp.waktu_perubahan, p.alasan_keluar 
        FROM riwayat_perubahan rp 
        JOIN produk p ON rp.nama_produk = p.nama_produk AND rp.merk = p.merk";

if (!empty($search)) {
    $sql .= " WHERE rp.nama_produk LIKE '%$search%'"; // Ganti nama_produk untuk pencarian
}

$sql .= " LIMIT $limit OFFSET $offset";


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
  <link rel="stylesheet" href="css/riwayat.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>

<body>

  <!-- Hamburger Icon and Sidebar -->
  <div class="dashboard-header">
    <div class="hamburger">
      <i class="fas fa-bars" id="hamburger-icon"></i>
    </div>

    <h2>RIWAYAT PERUBAHAN</h2>

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
    <a href="riwayat.php">
      <i class="fas fa-history"></i>
      <span>Riwayat Perubahan</span>
    </a>
    <a href="logout.php">
      <i class="fas fa-sign-out-alt"></i>
      <span>Keluar</span>
    </a>
  </div>



  <div class="content-wrapper">
    <?php if (isset($_GET['message'])): ?>
    <div class="alert alert-success">
      <?= htmlspecialchars($_GET['message']); ?>
    </div>
    <?php endif; ?>
    <table>
      <thead>
        <tr>
          <th>ID</th>
          <th>Nama Produk</th>
          <th>Merk</th>
          <th>Perubahan</th>
          <th>Penerima Barang</th>
          <th>Jenis Perubahan</th>
          <th>Waktu Perubahan</th>
          <th>Aksi</th>
        </tr>
      </thead>
      <tbody>
        <?php
  if (mysqli_num_rows($result) > 0) {
    $i = $offset + 1;
    while ($row = mysqli_fetch_assoc($result)) {
        echo "<tr>";
        echo "<td>" . $i . "</td>";
        echo "<td>" . $row["nama_produk"] . "</td>";
        echo "<td>" . $row["merk"] . "</td>";
        echo "<td>" . $row["perubahan"] . "</td>";
        echo "<td>" . $row["penerima_barang"] . "</td>";
        echo "<td>" . $row["jenis_perubahan"] . "</td>";
        echo "<td>" . $row["waktu_perubahan"] . "</td>";
        echo "<td><a href='delete.php?id=" . $row["id"] . "' onclick=\"return confirm('Apakah Anda yakin ingin menghapus data ini?');\">Hapus</a></td>";
        $i++;
        echo "</tr>";
    }
  } else {
      echo "<tr><td colspan='8'>Tidak ada data riwayat</td></tr>";
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