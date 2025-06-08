<?php
session_start();
require_once '../../config/database.php';

// Proteksi: hanya admin yang bisa akses
if (!isset($_SESSION['id_user']) || $_SESSION['level'] !== 'admin') {
    header("Location: ../dashboard.php");
    exit;
}

// Ambil ID produk dan ID kategori dari URL
if (!isset($_GET['id']) || !isset($_GET['id_kategori'])) {
    header("Location: ../dashboard.php");
    exit;
}

$id_produk = $_GET['id'];
$id_kategori = $_GET['id_kategori'];

// Ambil data produk
$stmt = $conn->prepare("SELECT * FROM produk WHERE id_produk = ?");
$stmt->bind_param("i", $id_produk);
$stmt->execute();
$result = $stmt->get_result();
$produk = $result->fetch_assoc();

if (!$produk) {
    echo "Produk tidak ditemukan.";
    exit;
}

$stmt->close();
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Edit Produk</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-5">
  <h2>Edit Produk</h2>

  <form action="../../backend/proses_manajemen_produk/proses_edit_produk.php" method="POST">
    <input type="hidden" name="id_produk" value="<?= $produk['id_produk']; ?>">
    <input type="hidden" name="id_kategori" value="<?= $id_kategori; ?>">

    <div class="mb-3">
      <label for="kode_produk" class="form-label">Kode Produk</label>
      <input type="text" class="form-control" name="kode_produk" id="kode_produk" value="<?= htmlspecialchars($produk['kode_produk']); ?>" required>
    </div>

    <div class="mb-3">
      <label for="nama_produk" class="form-label">Nama Produk</label>
      <input type="text" class="form-control" name="nama_produk" id="nama_produk" value="<?= htmlspecialchars($produk['nama_produk']); ?>" required>
    </div>

    <div class="mb-3">
      <label for="harga" class="form-label">Harga</label>
      <input type="number" class="form-control" name="harga" id="harga" value="<?= $produk['harga']; ?>" required>
    </div>

    <div class="mb-3">
      <label for="stok" class="form-label">Stok</label>
      <input type="number" class="form-control" name="stok" id="stok" value="<?= $produk['stok']; ?>" required>
    </div>

    <div class="mb-3">
      <label for="satuan" class="form-label">Satuan</label>
      <input type="text" class="form-control" name="satuan" id="satuan" value="<?= htmlspecialchars($produk['satuan']); ?>" required>
    </div>

    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
    <a href="lihat_produk.php?id_kategori=<?= $id_kategori; ?>" class="btn btn-secondary">Batal</a>
  </form>
</body>
</html>

<?php $conn->close(); ?>
