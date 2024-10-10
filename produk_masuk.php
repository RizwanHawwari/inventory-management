<?php
session_start();
require "functions.php";
include 'db.php';

if (!isset($_SESSION["login"])) {
    header("Location: login.php");
    exit;
}

$search = isset($_GET['search']) ? mysqli_real_escape_string($conn, $_GET['search']) : '';

$limit = 5;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

$totalResult = mysqli_query($conn, "SELECT COUNT(*) as total FROM produk WHERE nama_produk LIKE '%$search%'");
$totalData = mysqli_fetch_assoc($totalResult)['total'];

$sql = "SELECT id, nama_produk, merk, produk_masuk, jumlah, tanggal 
        FROM produk 
        WHERE nama_produk LIKE '%$search%' 
        LIMIT $limit OFFSET $offset";
$result = mysqli_query($conn, $sql);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $id = mysqli_real_escape_string($conn, $_POST['id']);
  $nama_produk = mysqli_real_escape_string($conn, $_POST['nama_produk']);
  $merk = mysqli_real_escape_string($conn, $_POST['merk']);
  $produk_masuk = mysqli_real_escape_string($conn, $_POST['produk_masuk']);

  $sql = "UPDATE produk SET nama_produk='$nama_produk', merk='$merk', produk_masuk='$produk_masuk', tanggal=NOW() WHERE id='$id'";
  if (mysqli_query($conn, $sql)) {
    header("Location: produk_masuk.php?message=Data berhasil diperbarui&search=" . urlencode($search));
    exit;
} else {
    die("Update failed: " . mysqli_error($conn));
}
}

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
  <link rel="stylesheet" href="css/produkmasuk.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>

<body>

  <!-- Hamburger Icon and Sidebar -->
  <div class="dashboard-header">
    <div class="hamburger">
      <i class="fas fa-bars" id="hamburger-icon"></i>
    </div>
    <h2>PRODUK MASUK</h2>
    <a href="addstock.php" class="addstock-container">
      <button class="add-stock add-stock-button" onclick="showPopup()">
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
    <?php if (isset($_GET['message'])): ?>
    <div class="message">
      <?php echo htmlspecialchars($_GET['message']); ?>
    </div>
    <?php endif; ?>
    <table>
      <thead>
        <tr>
          <th>ID</th>
          <th>Nama Produk</th>
          <th>Merk</th>
          <th>Produk Masuk</th>
          <th>Terakhir Diubah</th>
          <th>Edit</th>
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
                echo "<td>
                <a href='javascript:void(0);' class='edit-icon' 
                   onclick='openPopup(" . htmlspecialchars($row['id']) . ", \"" . htmlspecialchars($row['nama_produk']) . "\", \"" . htmlspecialchars($row['merk']) . "\", " . htmlspecialchars($row['produk_masuk']) . ")'>
                    <i class='fas fa-edit' style='color:black; font-size: 1.5em;'></i>
                </a>
            </td>";
                $i++;
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='6'>Tidak ada data yang ditemukan untuk '<strong>" . htmlspecialchars($search) . "</strong>'</td></tr>";
        }
        ?>
      </tbody>
    </table>

    <div class="pagination">
      <?php
      $totalPages = ceil($totalData / $limit);

      if ($page > 1) {
        echo '<a href="?search=' . $search . '&page=' . ($page - 1) . '">&laquo; Prev</a>';
      }

      for ($i = 1; $i <= $totalPages; $i++) {
        if ($i == $page) {
          echo '<a class="active">' . $i . '</a>';
        } else {
          echo '<a href="?search=' . $search . '&page=' . $i . '">' . $i . '</a>';
        }
      }

      if ($page < $totalPages) {
        echo '<a href="?search=' . $search . '&page=' . ($page + 1) . '">Next &raquo;</a>';
      }
      ?>
    </div>
  </div>

  <!-- Popup Edit -->
  <div id="popup-form" class="popup-form" style="display:none;">
    <div class="form-container">
      <span class="close" id="close-popup">&times;</span>
      <div class="popup-header">
        <i class="fas fa-edit"></i> <span>Edit Produk</span>
      </div>
      <hr>
      <form id="form-produk" method="POST" action="" autocomplete="off">
        <input type="hidden" name="id" id="product-id">
        <label for="nama_produk">Nama Produk:</label>
        <input type="text" name="nama_produk" id="nama_produk" required>
        <label for="merk">Merk:</label>
        <input type="text" name="merk" id="merk" required>
        <label for="produk_masuk">Jumlah:</label>
        <input type="number" name="produk_masuk" id="produk_masuk" required>

        <div class="form-buttons">
          <input type="submit" value="Simpan" style="background-color: #6488ea;">
          <button type="button" class="delete-button" onclick="confirmDelete()"><i class="fas fa-trash"></i> Hapus
            Produk</button>
        </div>
      </form>

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

  const popupForm = document.getElementById('popup-form');
  const closePopup = document.getElementById('close-popup');

  // Popup Edit
  function openPopup(id, nama_produk, merk, produk_masuk) {
    document.getElementById('product-id').value = id;
    document.getElementById('nama_produk').value = nama_produk;
    document.getElementById('merk').value = merk;
    document.getElementById('produk_masuk').value = produk_masuk;
    popupForm.style.display = 'flex';
  }

  closePopup.onclick = function() {
    popupForm.style.display = 'none';
  };

  window.onclick = function(event) {
    if (event.target == popupForm) {
      popupForm.style.display = 'none';
    }
  };

  // Fungsi untuk mengkonfirmasi dan menghapus produk
  function confirmDelete() {
    const productId = document.getElementById('product-id').value;
    if (confirm('Apakah Anda yakin ingin menghapus produk ini?')) {
      window.location.href = 'delete_product.php?id=' + productId;
    }
  }
  </script>
</body>

</html>

<?php
mysqli_close($conn);
?>