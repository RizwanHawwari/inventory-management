<?php
session_start();
require "functions.php";

if (!isset($_SESSION["login"])) {
    header("Location: login.php");
    exit;
}

include 'db.php';

$search = "";
if (isset($_GET['search'])) {
    $search = mysqli_real_escape_string($conn, $_GET['search']);
}

$sql = "SELECT id, nama_produk, produk_masuk, produk_keluar, (produk_masuk - produk_keluar) AS total 
        FROM produk";

if (!empty($search)) {
    $sql .= " WHERE nama_produk LIKE '%$search%'";
}

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
    <a href="#"><i class="fas fa-home"></i></a>
    <a href="#"><i class="fas fa-shopping-cart"></i></a>
    <a href="#"><i class="fas fa-tags"></i></a>
    <a href="logout.php"><i class="fas fa-sign-out-alt"></i></a>
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
          while ($row = mysqli_fetch_assoc($result)) {
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