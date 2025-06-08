<?php
session_start();
require_once '../../config/database.php';

if (!isset($_SESSION['info_struk'])) {
    header('Location: pembayaran.php');
    exit;
}

$info = $_SESSION['info_struk'];
$id_transaksi = $info['id_transaksi'];
$total = $info['total'];
$uang_dibayar = $info['uang_dibayar'];
$kembalian = $info['kembalian'];
$tanggal = $info['tanggal'];

// Ambil detail barang dari database
$stmt = $conn->prepare("SELECT nama_produk_saat_ini, harga_saat_ini, jumlah, subtotal FROM transaksi_detail WHERE id_transaksi = ?");
$stmt->bind_param("i", $id_transaksi);
$stmt->execute();
$result = $stmt->get_result();
$barang = [];
while ($row = $result->fetch_assoc()) {
    $barang[] = $row;
}
$stmt->close();
$conn->close();

?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Struk Pembayaran</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .struk-box {
            max-width: 400px;
            margin: 40px auto;
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 24px rgba(0,0,0,0.08);
            padding: 2rem 1.5rem;
        }
        .struk-title {
            font-size: 1.3rem;
            font-weight: bold;
            text-align: center;
            margin-bottom: 1rem;
        }
        .struk-table th, .struk-table td {
            font-size: 0.95rem;
        }
        .struk-footer {
            margin-top: 1.5rem;
            text-align: center;
            color: #888;
        }
        @media print {
            .btn-print, .btn-kembali { display: none; }
            .struk-box { box-shadow: none; border: none; }
        }
    </style>
</head>
<body class="bg-light">
    <div class="struk-box">
        <div class="struk-title">Struk Pembayaran</div>
        <div class="mb-2 text-center">
            <small><?= date('d-m-Y H:i', strtotime($tanggal)) ?></small>
        </div>
        <table class="table table-sm struk-table mb-3">
            <thead>
                <tr>
                    <th>Produk</th>
                    <th>Harga</th>
                    <th>Qty</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($barang as $b): ?>
                <tr>
                    <td><?= htmlspecialchars($b['nama_produk_saat_ini']) ?></td>
                    <td>Rp<?= number_format($b['harga_saat_ini'], 0, ',', '.') ?></td>
                    <td><?= $b['jumlah'] ?></td>
                    <td>Rp<?= number_format($b['subtotal'], 0, ',', '.') ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <ul class="list-group mb-3">
            <li class="list-group-item d-flex justify-content-between">
                <span>Total</span>
                <strong>Rp<?= number_format($total, 0, ',', '.') ?></strong>
            </li>
            <li class="list-group-item d-flex justify-content-between">
                <span>Uang Dibayar</span>
                <span>Rp<?= number_format($uang_dibayar, 0, ',', '.') ?></span>
            </li>
            <li class="list-group-item d-flex justify-content-between">
                <span>Kembalian</span>
                <span>Rp<?= number_format($kembalian, 0, ',', '.') ?></span>
            </li>
        </ul>
        <div class="text-center mb-3">
            <button onclick="window.print()" class="btn btn-primary btn-sm btn-print">Cetak Struk</button>
            <a href="pilih_kategori.php" class="btn btn-secondary btn-sm btn-kembali">Transaksi Baru</a>
        </div>
        <div class="struk-footer">
            <small>Terima kasih telah berbelanja!<br>&copy; <?= date('Y') ?> Sistem Manajemen Kasir</small>
        </div>
    </div>
</body>
</html>
