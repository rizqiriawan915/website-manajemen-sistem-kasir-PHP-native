<?php
session_start();

// Proteksi: hanya admin yang bisa mengakses
if (!isset($_SESSION['id_user']) || $_SESSION['level'] !== 'admin') {
    header("Location: ../dashboard.php");
    exit;
}

require_once '../../config/database.php';

// Ambil data kategori dari database
$sql = "SELECT * FROM kategori_produk";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Manajemen Kategori</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="d-flex">
  <!-- Konten Utama -->
  <div class="flex-grow-1 p-4">
    <h2>Manajemen Kategori</h2>

    <a href="tambah_kategori.php" class="btn btn-primary mb-3">Tambah Kategori</a>
    <a href="../dashboard.php" class="btn btn-primary mb-3">Dashboard</a>

    <table class="table table-bordered">
      <thead>
        <tr>
          <th>No</th>
          <th>Nama Kategori</th>
          <th>Aksi</th>
        </tr>
      </thead>
      <tbody>
        <?php if ($result->num_rows > 0): ?>
          <?php $no = 1; ?>
          <?php while ($kategori = $result->fetch_assoc()): ?>
            <tr>
              <td><?= $no++; ?></td>
              <td><?= $kategori['nama_kategori']; ?></td>
              <td>
                <a href="edit_kategori.php?id=<?= $kategori['id_kategori']; ?>" class="btn btn-warning btn-sm">Edit</a>
                <a href="../../backend/proses_manajemen_kategori/proses_hapus_kategori.php?id=<?= $kategori['id_kategori']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus kategori ini?')">Hapus</a>
              </td>
            </tr>
          <?php endwhile; ?>
        <?php else: ?>
          <tr>
            <td colspan="3" class="text-center">Tidak ada kategori.</td>
          </tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</body>
</html>
<?php $conn->close(); ?>