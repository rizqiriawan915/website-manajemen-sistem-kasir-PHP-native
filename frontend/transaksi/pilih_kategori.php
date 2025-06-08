<?php
session_start();
require_once '../../config/database.php';

// Ambil semua kategori dari database
$sql = "SELECT * FROM kategori_produk ORDER BY nama_kategori ASC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Transaksi - Pilih Kategori</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-4">
  <h2>Pilih Kategori Produk</h2>
  <a href="../dashboard.php" class="btn btn-secondary mb-3">Kembali</a> 
  <a href="keranjang.php" class="btn btn-secondary mb-3">Keranjang</a> 

  <div class="row">
    <?php while ($kategori = $result->fetch_assoc()): ?>
      <div class="col-md-4 mb-3">
        <div class="card">
          <div class="card-body">
            <h5 class="card-title"><?= htmlspecialchars($kategori['nama_kategori']) ?></h5>
            <a href="pilih_produk.php?id_kategori=<?= $kategori['id_kategori'] ?>" class="btn btn-primary">Lihat Produk</a>
          </div>
        </div>
      </div>
    <?php endwhile; ?>
  </div>
</body>
</html>

<?php $conn->close(); ?>
