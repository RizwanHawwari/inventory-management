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

// Jika form update dikirim
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // Mengambil dan mengamankan input
  $id = mysqli_real_escape_string($conn, $_POST['id']);
  $nama_produk = mysqli_real_escape_string($conn, $_POST['nama_produk']);
  $merk = mysqli_real_escape_string($conn, $_POST['merk']);
  $produk_keluar = mysqli_real_escape_string($conn, $_POST['produk_keluar']);
  $penerima_barang = mysqli_real_escape_string($conn, $_POST['penerima_barang']);
  $alasan_keluar = mysqli_real_escape_string($conn, $_POST['alasan_keluar']);
  $jam = mysqli_real_escape_string($conn, $_POST['jam']); // Waktu keluar

  // Mengupdate data produk
  $sql = "UPDATE produk SET 
              nama_produk='$nama_produk', 
              merk='$merk', 
              produk_keluar='$produk_keluar',
              penerima_barang_keluar='$penerima_barang',
              alasan_keluar='$alasan_keluar',
              waktu='$jam'
          WHERE id='$id'";

  if (mysqli_query($conn, $sql)) {
      header("Location: produk_keluar.php?message=Data berhasil diperbarui");
      exit;
  } else {
      die("Update failed: " . mysqli_error($conn));
  }
}

$limit = 5;

$totalQuery = "SELECT COUNT(*) AS total FROM produk WHERE nama_produk LIKE '%$search%'";
$totalResult = mysqli_query($conn, $totalQuery);
$totalRow = mysqli_fetch_assoc($totalResult);
$totalData = $totalRow['total'];
$totalPages = ceil($totalData / $limit);

$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$start = ($page - 1) * $limit;

$sql = "SELECT id, nama_produk, merk, produk_keluar AS jumlah, tanggal, penerima_barang_keluar, alasan_keluar, waktu
        FROM produk 
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
  <link rel="stylesheet" href="css/prodkeluar.css">
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
          <th>Merek</th>
          <th>Jumlah</th>
          <th>Penerima</th>
          <th>Tanggal</th>
          <th>Alasan Keluar</th> <!-- Tambahkan kolom Alasan Keluar -->
          <th>Edit</th>
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
                echo "<td>" . htmlspecialchars($row["merk"]) . "</td>";
                echo "<td>" . htmlspecialchars($row["jumlah"]) . "</td>";
                echo "<td>" . htmlspecialchars($row["penerima_barang_keluar"]) . "</td>";

                $tanggal = $row['tanggal'];
                $waktu = $row['waktu'];
                $datetimeString = $tanggal . ' ' . $waktu;
                $dateTime = new DateTime($datetimeString);
                $formattedDate = $dateTime->format('d F Y, H:i:s');
                echo "<td>" . htmlspecialchars($formattedDate) . "</td>";

                echo "<td>" . htmlspecialchars($row["alasan_keluar"]) . "</td>";
                
                echo "<td>
                        <a href='javascript:void(0);' class='edit-icon' 
                           onclick='openPopup(" . htmlspecialchars($row['id']) . ", \"" . htmlspecialchars($row['nama_produk']) . "\", \"" . htmlspecialchars($row['merk']) . "\", \"" . htmlspecialchars($row['jumlah']) . "\", \"" . htmlspecialchars($row['tanggal']) . "\")'>
                            <i class='fas fa-edit' style='color:black; font-size: 1.5em;'></i>
                        </a>
                      </td>";
                $i++;
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='8'>Tidak ada data</td></tr>"; // Ganti colspan sesuai jumlah kolom
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

  <!-- Pop-Up Form -->
  <div id="popup-form" class="popup-form" style="display:none;">
    <div class="form-container">
      <span class="close" id="close-popup">&times;</span>
      <div class="popup-header">
        <i class="fas fa-edit"></i> <span>Edit Produk</span>
      </div>
      <hr>
      <form id="form-produk" method="POST" action="">
        <input type="hidden" name="id" id="product-id">

        <label for="nama_produk">Nama Produk:</label>
        <input type="text" name="nama_produk" id="nama_produk" required>

        <label for="merk">Merek:</label>
        <input type="text" name="merk" id="merk" required>

        <label for="produk_keluar">Jumlah:</label>
        <input type="number" name="produk_keluar" id="produk_keluar" required>

        <label for="penerima_barang">Penerima:</label>
        <input type="text" name="penerima_barang" id="penerima_barang" required>

        <label for="alasan_keluar">Alasan Keluar</label>
        <select name="alasan_keluar" id="alasan_keluar" required>
          <option value="Terjual">Terjual</option>
          <option value="Expired">Expired</option>
        </select>

        <label for="jam">Waktu:</label>
        <input type="time" name="jam" id="jam" required>

        <input type="submit" value="Simpan">
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

  // Tambahkan deklarasi variabel popupForm
  const popupForm = document.getElementById('popup-form');
  const closePopup = document.getElementById('close-popup');

  function openPopup(id, nama_produk, merk, produk_keluar) {
    document.getElementById('product-id').value = id;
    document.getElementById('nama_produk').value = nama_produk;
    document.getElementById('merk').value = merk;
    document.getElementById('produk_keluar').value = produk_keluar;
    popupForm.style.display = 'flex';
  }

  closePopup.onclick = function() {
    popupForm.style.display = 'none';
  }

  window.onclick = function(event) {
    if (event.target == popupForm) {
      popupForm.style.display = 'none';
    }
  };
  </script>
</body>

</html>

<?php
mysqli_close($conn);
?>