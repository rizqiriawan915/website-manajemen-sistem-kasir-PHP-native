<?php
session_start();
require_once '../../config/database.php';

// Ambil ID kategori dari URL
if (!isset($_GET['id_kategori'])) {
  header("Location: pilih_kategori.php");
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

// Ambil produk dari kategori ini
$stmt = $conn->prepare("SELECT * FROM produk WHERE id_kategori = ?");
$stmt->bind_param("i", $id_kategori);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Transaksi - <?= htmlspecialchars($nama_kategori) ?></title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-4">
  <h2>Produk Kategori: <?= htmlspecialchars($nama_kategori) ?></h2>
  <a href="pilih_kategori.php" class="btn btn-secondary mb-3">← Kembali ke Daftar Kategori</a>
  <table class="table table-bordered">
    <thead>
      <tr>
        <th>Kode</th>
        <th>Nama Produk</th>
        <th>Harga</th>
        <th>Stok</th>
        <th>Aksi</th>
      </tr>
    </thead>
    <tbody>
      <?php while ($produk = $result->fetch_assoc()): ?>
        <tr>
          <td><?= htmlspecialchars($produk['kode_produk']) ?></td>
          <td><?= htmlspecialchars($produk['nama_produk']) ?></td>
          <td>Rp<?= number_format($produk['harga'], 0, ',', '.') ?></td>
          <td><?= $produk['stok'] ?></td>
          <td>
            <!-- Tombol tambah ke keranjang (belum berfungsi, nanti kita buat) -->
            <form method="post" action="tambah_keranjang.php" style="display: inline;">
              <input type="hidden" name="id_produk" value="<?= $produk['id_produk'] ?>">
              <input type="hidden" name="id_kategori" value="<?= $id_kategori ?>">
              <a href="../../backend/proses_transaksi/proses_tambah_keranjang.php?id_produk=<?= $produk['id_produk']; ?>" class="btn btn-primary btn-sm">
                Tambah
              </a>
            </form>
          </td>
        </tr>
      <?php endwhile; ?>
    </tbody>
  </table>
</body>
</html>
<?php $conn->close(); ?>