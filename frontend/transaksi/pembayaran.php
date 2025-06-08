<?php
session_start();
require_once '../../config/database.php';

$id_user = $_SESSION['id_user'];

// Ambil isi keranjang dari database
$query = "SELECT * FROM keranjang WHERE id_user = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $id_user);
$stmt->execute();
$result = $stmt->get_result();

$daftarBarang = [];
$total = 0;

while ($row = $result->fetch_assoc()) {
    $subtotal = $row['harga'] * $row['qty'];
    $row['subtotal'] = $subtotal;
    $daftarBarang[] = $row;
    $total += $subtotal;
}

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Pembayaran</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-4">
  <h2>Halaman Pembayaran</h2>

  <!-- Tabel Daftar Barang -->
  <div class="card p-3 mb-4">
    <h5>Barang yang Dibeli:</h5>
    <table class="table table-bordered">
      <thead>
        <tr>
          <th>Nama Produk</th>
          <th>Harga</th>
          <th>Qty</th>
          <th>Subtotal</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($daftarBarang as $barang): ?>
          <tr>
            <td><?= htmlspecialchars($barang['nama_produk']); ?></td>
            <td>Rp<?= number_format($barang['harga'], 0, ',', '.') ?></td>
            <td><?= $barang['qty']; ?></td>
            <td>Rp<?= number_format($barang['subtotal'], 0, ',', '.') ?></td>
          </tr>
        <?php endforeach; ?>
        <tr>
          <th colspan="3" class="text-end">Total</th>
          <th>Rp<?= number_format($total, 0, ',', '.') ?></th>
        </tr>
      </tbody>
    </table>
  </div>

  <!-- Form Pembayaran -->
  <div class="card p-3">
    <form action="../../backend/proses_transaksi/proses_pembayaran.php" method="post">
      <div class="mb-3">
        <label for="uang_dibayar" class="form-label">Uang Dibayar (Rp)</label>
        <input type="number" name="uang_dibayar" id="uang_dibayar" class="form-control" required min="<?= $total ?>">
      </div>
      <button type="submit" class="btn btn-success">Bayar</button>
      <a href="keranjang.php" class="btn btn-secondary">Kembali ke Keranjang</a>
    </form>
  </div>
</body>
</html>
