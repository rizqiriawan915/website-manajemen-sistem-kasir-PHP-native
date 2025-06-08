<?php
session_start();

// Proteksi hanya untuk admin
if (!isset($_SESSION['id_user']) || $_SESSION['level'] !== 'admin') {
    header("Location: ../dashboard.php");
    exit;
}

// Ambil id_kategori dari URL
if (!isset($_GET['id_kategori'])) {
    header("Location: ../manajemen_produk/kategori.php");
    exit;
}

$id_kategori = $_GET['id_kategori'];

require_once '../../config/database.php';

// Ambil nama kategori
$stmt = $conn->prepare("SELECT nama_kategori FROM kategori_produk WHERE id_kategori = ?");
$stmt->bind_param("i", $id_kategori);
$stmt->execute();
$stmt->bind_result($nama_kategori);
$stmt->fetch();
$stmt->close();
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Tambah Produk</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-4">
  <div class="container">
    <h2>Tambah Produk - Kategori: <?= htmlspecialchars($nama_kategori); ?></h2>
    <form action="../../backend/proses_manajemen_produk/proses_tambah_produk.php" method="POST">
      <!-- Hidden input untuk id_kategori -->
      <input type="hidden" name="id_kategori" value="<?= $id_kategori; ?>">

      <div class="mb-3">
        <label for="kode_produk" class="form-label">Kode Produk</label>
        <input type="text" name="kode_produk" id="kode_produk" class="form-control" required>
      </div>

      <div class="mb-3">
        <label for="nama_produk" class="form-label">Nama Produk</label>
        <input type="text" name="nama_produk" id="nama_produk" class="form-control" required>
      </div>

      <div class="mb-3">
        <label for="harga" class="form-label">Harga</label>
        <input type="number" name="harga" id="harga" class="form-control" required>
      </div>

      <div class="mb-3">
        <label for="stok" class="form-label">Stok</label>
        <input type="number" name="stok" id="stok" class="form-control" value="0" required>
      </div>

      <div class="mb-3">
        <label for="satuan" class="form-label">Satuan</label>
        <input type="text" name="satuan" id="satuan" class="form-control" placeholder="cth: pcs, botol" required>
      </div>

      <button type="submit" class="btn btn-primary">Simpan</button>
      <a href="lihat_produk.php?id_kategori=<?= $id_kategori; ?>" class="btn btn-secondary">Kembali</a>
    </form>
  </div>
</body>
</html>
