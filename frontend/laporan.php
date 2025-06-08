<?php
session_start();
if (!isset($_SESSION['id_user'])) {
    header("Location: login.php");
    exit;
}

require_once '../config/database.php';

// Ambil filter bulan & tahun
$bulan = isset($_GET['bulan']) ? (int)$_GET['bulan'] : (int)date('m');
$tahun = isset($_GET['tahun']) ? (int)$_GET['tahun'] : (int)date('Y');

// Query transaksi
$sql = "SELECT t.*, u.nama as kasir FROM transaksi t JOIN users u ON t.id_user = u.id_user WHERE MONTH(t.tanggal) = ? AND YEAR(t.tanggal) = ? ORDER BY t.tanggal DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $bulan, $tahun);
$stmt->execute();
$result = $stmt->get_result();

// Untuk filter bulan/tahun
$bulanList = [1=>'Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];
$tahunSekarang = (int)date('Y');

$conn->close();
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Laporan Penjualan</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
  <div class="container py-4">
    <h2 class="mb-4">Laporan Penjualan</h2>
    <form class="row g-2 mb-4" method="get">
      <div class="col-auto">
        <select name="bulan" class="form-select">
          <?php foreach ($bulanList as $num => $nama): ?>
            <option value="<?= $num ?>" <?= $bulan == $num ? 'selected' : '' ?>><?= $nama ?></option>
          <?php endforeach; ?>
        </select>
      </div>
      <div class="col-auto">
        <select name="tahun" class="form-select">
          <?php for ($y = $tahunSekarang; $y >= $tahunSekarang-5; $y--): ?>
            <option value="<?= $y ?>" <?= $tahun == $y ? 'selected' : '' ?>><?= $y ?></option>
          <?php endfor; ?>
        </select>
      </div>
      <div class="col-auto">
        <button type="submit" class="btn btn-primary">Tampilkan</button>
      </div>
    </form>
    <div class="card shadow-sm">
      <div class="card-body">
        <div class="table-responsive">
          <table class="table table-bordered table-striped mb-0">
            <thead class="table-light">
              <tr>
                <th>Tanggal</th>
                <th>Kode Transaksi</th>
                <th>Kasir</th>
                <th>Total</th>
                <th>Bayar</th>
                <th>Kembalian</th>
              </tr>
            </thead>
            <tbody>
              <?php if ($result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                  <tr>
                    <td><?= date('d-m-Y H:i', strtotime($row['tanggal'])) ?></td>
                    <td><?= htmlspecialchars($row['kode_transaksi']) ?></td>
                    <td><?= htmlspecialchars($row['kasir']) ?></td>
                    <td>Rp<?= number_format($row['total'], 0, ',', '.') ?></td>
                    <td>Rp<?= number_format($row['bayar'], 0, ',', '.') ?></td>
                    <td>Rp<?= number_format($row['kembalian'], 0, ',', '.') ?></td>
                  </tr>
                <?php endwhile; ?>
              <?php else: ?>
                <tr><td colspan="6" class="text-center">Tidak ada transaksi pada periode ini.</td></tr>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
    <a href="dashboard.php" class="btn btn-secondary mt-4">Kembali ke Dashboard</a>
  </div>
</body>
</html>
