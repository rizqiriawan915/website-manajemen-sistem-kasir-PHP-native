<?php
session_start();
if (!isset($_SESSION['id_user'])) {
    header("Location: login.php");
    exit;
}

$nama = $_SESSION['nama'];
$level = $_SESSION['level'];

require_once '../config/database.php';

// Query kategori dan jumlah produk
$kategoriInfo = [];
$sql = "SELECT k.nama_kategori, COUNT(p.id_produk) as jumlah_produk
            FROM kategori_produk k
            LEFT JOIN produk p ON k.id_kategori = p.id_kategori
            GROUP BY k.id_kategori
            ORDER BY k.nama_kategori ASC";
$result = $conn->query($sql);
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $kategoriInfo[] = $row;
    }
}

// Query pemasukan bulan ini
$pemasukan = 0;
$awalBulan = date('Y-m-01 00:00:00');
$akhirBulan = date('Y-m-t 23:59:59');
$sql = "SELECT SUM(total) as total_pemasukan FROM transaksi WHERE tanggal BETWEEN ? AND ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $awalBulan, $akhirBulan);
$stmt->execute();
$stmt->bind_result($total_pemasukan);
$stmt->fetch();
$pemasukan = $total_pemasukan ?: 0;
$stmt->close();

// Dummy pengeluaran (bisa diambil dari tabel pengeluaran jika ada)
$pengeluaran = 0; // Ganti dengan query jika ada tabel pengeluaran

$conn->close();
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Dashboard</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
  <style>
    body {
      min-height: 100vh;
      background: #f0f2f5;
    }
    .sidebar {
      width: 280px;
      min-height: 100vh;
      background: linear-gradient(135deg, #2c5ba8 0%, #007bff 100%);
      color: #fff;
      box-shadow: 4px 0 15px rgba(0,0,0,0.1);
      position: sticky;
      top: 0;
    }
    .sidebar .nav-link {
      color: #e0e0e0;
      font-weight: 500;
      border-radius: 8px;
      margin-bottom: 8px;
      transition: background 0.2s, color 0.2s;
      padding: 10px 15px;
      display: flex;
      align-items: center;
    }
    .sidebar .nav-link i {
      margin-right: 10px;
      font-size: 1.1rem;
    }
    .sidebar .nav-link.active, .sidebar .nav-link:hover {
      background: rgba(255,255,255,0.25);
      color: #fff;
    }
    .sidebar .logo {
      font-size: 1.8rem;
      font-weight: bold;
      letter-spacing: 1px;
      margin-bottom: 2.5rem;
      text-align: center;
      padding-top: 1.5rem;
    }
    .main-content {
      padding: 2.5rem 3rem;
      background-color: #fff;
    }
    .card {
        border: none;
        border-radius: 1rem;
        box-shadow: 0 8px 32px rgba(0,0,0,0.1);
    }
    .table-bordered th, .table-bordered td {
        border-color: #e9ecef;
    }
    .table-striped tbody tr:nth-of-type(odd) {
        background-color: rgba(0,0,0,.03);
    }
    .badge {
        padding: 0.5em 0.7em;
        font-size: 0.9em;
    }
    @media (max-width: 768px) {
      .sidebar {
        width: 100%;
        min-height: auto;
        flex-direction: row;
        padding: 1rem 0.5rem;
        position: relative;
        box-shadow: none;
      }
      .sidebar .nav-link {
        justify-content: center;
        text-align: center;
      }
      .sidebar .nav-link i {
        margin-right: 0;
        display: block;
      }
      .sidebar .logo {
        display: none;
      }
      .main-content {
        padding: 1rem;
      }
    }
  </style>
</head>
<body class="d-flex">

  <!-- Sidebar -->
  <nav class="sidebar d-flex flex-column p-3">
    <div class="logo mb-4">
        <i class="bi bi-shop me-2"></i> Manajemen Kasir
    </div>
    <ul class="nav flex-column mb-auto">
      <li><a href="#" class="nav-link active"><i class="bi bi-house-door-fill"></i> Home</a></li>
      <?php if ($level === 'admin'): ?>
        <li><a href="./manajemen_produk/lihat_kategori_produk.php" class="nav-link"><i class="bi bi-box-seam"></i> Manajemen Produk</a></li>
        <li><a href="./manajemen_anggota/lihat_anggota.php" class="nav-link"><i class="bi bi-people-fill"></i> Manajemen Anggota</a></li>
        <li><a href="./manajemen_kategori/lihat_kategori.php" class="nav-link"><i class="bi bi-tags-fill"></i> Manajemen Kategori</a></li>
      <?php endif; ?>
      <li><a href="./transaksi/pilih_kategori.php" class="nav-link"><i class="bi bi-cash-stack"></i> Transaksi</a></li>
      <li><a href="laporan.php" class="nav-link"><i class="bi bi-graph-up"></i> Laporan</a></li>
    </ul>
    <div class="mt-auto">
      <hr class="bg-white">
      <a href="../backend/proses_logout.php" class="btn btn-outline-light btn-sm w-100"><i class="bi bi-box-arrow-right"></i> Logout</a>
    </div>
  </nav>

  <!-- Konten Utama -->
  <div class="main-content flex-grow-1">
    <div class="container-fluid">
      <div class="row mb-4">
        <div class="col-12">
          <h2 class="fw-bold text-primary mb-2">Selamat datang, <?= htmlspecialchars($nama) ?>!</h2>
          <p class="text-muted fs-5">Aktivitas Anda di <span class="fw-bold text-dark">Sistem Manajemen Kasir</span></p>
          <p class="text-muted mb-0">Anda login sebagai <span class="badge bg-primary text-capitalize fs-6"><?= htmlspecialchars($level) ?></span></p>
        </div>
      </div>
      <div class="row mb-4">
        <div class="col-md-6 mb-4">
          <div class="card">
            <div class="card-body">
              <h5 class="card-title mb-3"><i class="bi bi-grid-3x3-gap-fill me-2 text-primary"></i> Kategori Produk & Jumlah Produk</h5>
              <div class="table-responsive">
                <table class="table table-hover mb-0">
                  <thead class="table-light">
                    <tr>
                      <th>Nama Kategori</th>
                      <th class="text-center">Jumlah Produk</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php if (count($kategoriInfo) > 0): ?>
                      <?php foreach ($kategoriInfo as $kat): ?>
                        <tr>
                          <td><?= htmlspecialchars($kat['nama_kategori']) ?></td>
                          <td class="text-center"><span class="badge bg-info text-dark fs-6"><?= $kat['jumlah_produk'] ?></span></td>
                        </tr>
                      <?php endforeach; ?>
                    <?php else: ?>
                      <tr><td colspan="2" class="text-center">Belum ada data kategori/produk.</td></tr>
                    <?php endif; ?>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
        <div class="col-md-6 mb-4">
          <div class="card">
            <div class="card-body">
              <h5 class="card-title mb-3"><i class="bi bi-wallet-fill me-2 text-primary"></i> Ringkasan Keuangan Bulan Ini</h5>
              <ul class="list-group list-group-flush mb-2">
                <li class="list-group-item d-flex justify-content-between align-items-center">
                  <span><i class="bi bi-arrow-down-left-square-fill me-2 text-success"></i>Pemasukan</span>
                  <span class="badge bg-success fs-6">Rp<?= number_format($pemasukan, 0, ',', '.') ?></span>
                </li>
                <li class="list-group-item d-flex justify-content-between align-items-center">
                  <span><i class="bi bi-arrow-up-right-square-fill me-2 text-danger"></i>Pengeluaran</span>
                  <span class="badge bg-danger fs-6">Rp<?= number_format($pengeluaran, 0, ',', '.') ?></span>
                </li>
                <li class="list-group-item d-flex justify-content-between align-items-center">
                  <span><i class="bi bi-currency-dollar me-2 text-primary"></i>Saldo Bersih</span>
                  <span class="badge bg-primary fs-6">Rp<?= number_format($pemasukan - $pengeluaran, 0, ',', '.') ?></span>
                </li>
              </ul>
              <small class="text-muted">* Pengeluaran diambil dari tabel pengeluaran jika tersedia.</small>
            </div>
          </div>
        </div>
      </div>
      <div class="row mt-5">
      </div>
    </div>
  </div>

  <!-- Optional Bootstrap JavaScript -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
