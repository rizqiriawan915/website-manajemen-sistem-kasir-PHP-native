<?php
session_start();

// Proteksi login
if (!isset($_SESSION['id_user'])) {
    header("Location: ../login.php");
    exit;
}

require_once '../../config/database.php';

// Ambil id_kategori dari URL
if (!isset($_GET['id_kategori'])) {
    header("Location: ../dashboard.php");
    exit;
}

$id_kategori = $_GET['id_kategori'];

// Ambil nama kategori
$stmt_kat = $conn->prepare("SELECT nama_kategori FROM kategori_produk WHERE id_kategori = ?");
$stmt_kat->bind_param("i", $id_kategori);
$stmt_kat->execute();
$stmt_kat->bind_result($nama_kategori);
$stmt_kat->fetch();
$stmt_kat->close();

// Ambil produk dari kategori tersebut
$stmt = $conn->prepare("SELECT * FROM produk WHERE id_kategori = ?");
$stmt->bind_param("i", $id_kategori);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Kelola Produk - <?= htmlspecialchars($nama_kategori); ?></title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-4">

  <h2>Produk: <?= htmlspecialchars($nama_kategori); ?></h2>

  <a href="tambah_produk.php?id_kategori=<?= $id_kategori ?>" class="btn btn-success mb-3">Tambah Produk</a>
  <a href="lihat_kategori_produk.php" class="btn btn-secondary mb-3">Kembali ke Daftar Kategori</a>

  <table class="table table-bordered table-striped">
    <thead>
      <tr>
        <th>ID</th>
        <th>Kode</th>
        <th>Nama</th>
        <th>Harga</th>
        <th>Stok</th>
        <th>Satuan</th>
        <th>Aksi</th>
      </tr>
    </thead>
    <tbody>
      <?php if ($result->num_rows > 0): ?>
        <?php while ($produk = $result->fetch_assoc()): ?>
          <tr>
            <td><?= $produk['id_produk']; ?></td>
            <td><?= htmlspecialchars($produk['kode_produk']); ?></td>
            <td><?= htmlspecialchars($produk['nama_produk']); ?></td>
            <td>Rp<?= number_format($produk['harga'], 0, ',', '.'); ?></td>
            <td><?= $produk['stok']; ?></td>
            <td><?= htmlspecialchars($produk['satuan']); ?></td>
            <td>
              <a href="edit_produk.php?id=<?= $produk['id_produk']; ?>&id_kategori=<?= $id_kategori ?>" class="btn btn-warning btn-sm">Edit</a>
              <a href="../../backend/proses_manajemen_produk/proses_hapus_produk.php?id=<?= $produk['id_produk']; ?>&id_kategori=<?= $id_kategori ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus produk ini?')">Hapus</a>
            </td>
          </tr>
        <?php endwhile; ?>
      <?php else: ?>
        <tr>
          <td colspan="7" class="text-center">Tidak ada produk dalam kategori ini.</td>
        </tr>
      <?php endif; ?>
    </tbody>
  </table>

</body>
</html>

<?php
$conn->close();
?>
