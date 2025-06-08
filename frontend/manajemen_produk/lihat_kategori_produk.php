<?php
session_start();

// Proteksi: hanya admin yang bisa akses
if (!isset($_SESSION['id_user']) || $_SESSION['level'] !== 'admin') {
    header("Location: ../dashboard.php");
    exit;
}

require_once '../../config/database.php';

// Ambil semua kategori dari database
$sql = "SELECT * FROM kategori_produk ORDER BY nama_kategori ASC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Manajemen Produk - Pilih Kategori</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-4">
  <div class="container">
    <h2 class="mb-4">Manajemen Produk</h2>

    <a href="../dashboard.php" class="btn btn-secondary mb-3">Kembali ke Dashboard</a>

    <div class="table-responsive">
      <table class="table table-bordered table-hover">
        <thead class="table-light">
          <tr>
            <th>No</th>
            <th>Nama Kategori</th>
            <th>Aksi</th>
          </tr>
        </thead>
        <tbody>
          <?php if ($result->num_rows > 0): $no = 1; ?>
            <?php while ($kategori = $result->fetch_assoc()): ?>
              <tr>
                <td><?= $no++; ?></td>
                <td><?= htmlspecialchars($kategori['nama_kategori']); ?></td>
                <td>
                  <a href="lihat_produk.php?id_kategori=<?= $kategori['id_kategori']; ?>" class="btn btn-primary btn-sm">
                    Kelola Produk
                  </a>
                </td>
              </tr>
            <?php endwhile; ?>
          <?php else: ?>
            <tr>
              <td colspan="3" class="text-center">Belum ada kategori produk.</td>
            </tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</body>
</html>
<?php $conn->close(); ?>