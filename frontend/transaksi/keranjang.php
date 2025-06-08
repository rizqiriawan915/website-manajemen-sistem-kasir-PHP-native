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

$keranjang = [];
$total = 0;

while ($row = $result->fetch_assoc()) {
  $keranjang[] = $row;
  $total += $row['qty'] * $row['harga'];
}

$stmt->close();
$conn->close();

$keranjangKosong = empty($keranjang);
?>

<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <title>Keranjang Belanja</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    .qty-btn {
      width: 30px;
      height: 30px;
      padding: 0;
      font-weight: bold;
      text-align: center;
      line-height: 28px;
      user-select: none;
    }

    .qty-btn:hover {
      text-decoration: none;
      background-color: #e2e6ea;
      border-radius: 4px;
    }
  </style>
</head>

<body class="p-4">
  <h2>Keranjang Belanja</h2>

  <?php if ($keranjangKosong): ?>
    <div class="alert alert-warning">Keranjang masih kosong.</div>
    <a href="pilih_kategori.php" class="btn btn-secondary">Kembali ke Kategori</a>
  <?php else: ?>
    <table class="table table-bordered align-middle">
      <thead>
        <tr>
          <th>Nama Produk</th>
          <th>Harga</th>
          <th style="width: 140px;">Qty</th>
          <th>Subtotal</th>
          <th>Aksi</th>
        </tr>
      </thead>
      <tbody>
        <?php
        $total = 0;
        foreach ($keranjang as $item):
          $subtotal = $item['qty'] * $item['harga'];
          $total += $subtotal;
        ?>
          <tr>
            <td><?= htmlspecialchars($item['nama_produk']); ?></td>
            <td>Rp<?= number_format($item['harga'], 0, ',', '.'); ?></td>
            <td>
              <div class="input-group input-group-sm" style="max-width: 120px;">
                <a href="../../backend/proses_transaksi/proses_keranjang.php?id_produk=<?= $item['id_produk']; ?>&aksi=kurangi" class="btn btn-outline-secondary" style="min-width: 36px;">−</a>
                <input type="text" class="form-control text-center" value="<?= $item['qty']; ?>" readonly style="background: #fff;">
                <a href="../../backend/proses_transaksi/proses_keranjang.php?id_produk=<?= $item['id_produk']; ?>&aksi=tambah" class="btn btn-outline-secondary" style="min-width: 36px;">+</a>
              </div>
            </td>
            <td>Rp<?= number_format($subtotal, 0, ',', '.'); ?></td>
            <td>
              <a href="../../backend/proses_transaksi/proses_hapus_transaksi.php?id_produk=<?= $item['id_produk']; ?>" class="btn btn-danger btn-sm">Hapus</a>
            </td>
          </tr>
        <?php endforeach; ?>
        <tr>
          <th colspan="3" class="text-end">Total</th>
          <th colspan="2">Rp<?= number_format($total, 0, ',', '.'); ?></th>
        </tr>
      </tbody>
    </table>

    <div class="d-flex justify-content-between">
      <a href="pilih_kategori.php" class="btn btn-secondary">Kembali</a>
      <div>
        <a href="pembayaran.php" class="btn btn-success">Bayar</a>
      </div>
    </div>
  <?php endif; ?>
</body>

</html>