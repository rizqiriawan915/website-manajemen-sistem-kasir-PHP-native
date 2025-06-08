<?php
session_start();
require_once '../../config/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $bayar = (int) $_POST['uang_dibayar'];
    $id_user = $_SESSION['id_user'];

    // Ambil keranjang dari database
    $query = "SELECT * FROM keranjang WHERE id_user = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id_user);
    $stmt->execute();
    $result = $stmt->get_result();

    $keranjang = [];
    $total = 0;
    while ($row = $result->fetch_assoc()) {
        $keranjang[] = $row;
        $total += $row['harga'] * $row['qty'];
    }
    $stmt->close();

    if (empty($keranjang)) {
        $_SESSION['error'] = "Keranjang kosong. Tidak dapat memproses pembayaran.";
        header("Location: ../../frontend/transaksi/keranjang.php");
        exit;
    }

    if (!isset($_POST['uang_dibayar'])) {
        $_SESSION['error'] = "Input pembayaran tidak ditemukan.";
        header("Location: ../../frontend/transaksi/pembayaran.php");
        exit;
    }

    if ($bayar < $total) {
        $_SESSION['error'] = "Uang dibayar kurang dari total belanja.";
        header("Location: ../../frontend/transaksi/pembayaran.php");
        exit;
    }

    $kembalian = $bayar - $total;
    $tanggal = date('Y-m-d H:i:s');

    function generateKodeTransaksi($conn)
    {
        $tanggal = date('Ymd');
        $tanggal_awal = date('Y-m-d 00:00:00');
        $tanggal_akhir = date('Y-m-d 23:59:59');
        $stmt = $conn->prepare("SELECT COUNT(*) as total FROM transaksi WHERE tanggal BETWEEN ? AND ?");
        $stmt->bind_param("ss", $tanggal_awal, $tanggal_akhir);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();
        $stmt->close();
        $no_urut = $result['total'] + 1;
        $kode = "TRX-$tanggal-" . str_pad($no_urut, 4, '0', STR_PAD_LEFT);
        return $kode;
    }

    // Simpan transaksi utama
    $kode_transaksi = generateKodeTransaksi($conn);
    $stmt = $conn->prepare("INSERT INTO transaksi (kode_transaksi, id_user, total, bayar, kembalian, tanggal) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("siiids", $kode_transaksi, $id_user, $total, $bayar, $kembalian, $tanggal);
    $stmt->execute();
    $id_transaksi = $stmt->insert_id;
    $stmt->close();

    // Simpan detail transaksi
    $stmt_detail = $conn->prepare("INSERT INTO transaksi_detail (id_transaksi, id_produk, nama_produk_saat_ini, harga_saat_ini, jumlah, subtotal) VALUES (?, ?, ?, ?, ?, ?)");
    foreach ($keranjang as $produk) {
        $id_produk = $produk['id_produk'];
        $nama_produk = $produk['nama_produk'];
        $harga = $produk['harga'];
        $qty = $produk['qty'];
        $subtotal = $harga * $qty;
        $stmt_detail->bind_param("issdii", $id_transaksi, $id_produk, $nama_produk, $harga, $qty, $subtotal);
        $stmt_detail->execute();

        // Update stok produk
        $stmt_update_stok = $conn->prepare("UPDATE produk SET stok = stok - ? WHERE id_produk = ?");
        $stmt_update_stok->bind_param("ii", $qty, $id_produk);
        $stmt_update_stok->execute();
        $stmt_update_stok->close();
    }
    $stmt_detail->close();

    // Hapus keranjang user dari database
    $stmt = $conn->prepare("DELETE FROM keranjang WHERE id_user = ?");
    $stmt->bind_param("i", $id_user);
    $stmt->execute();
    $stmt->close();

    // Simpan info struk ke session
    $_SESSION['info_struk'] = [
        'id_transaksi' => $id_transaksi,
        'total' => $total,
        'uang_dibayar' => $bayar,
        'kembalian' => $kembalian,
        'tanggal' => $tanggal
    ];

    header("Location: ../../frontend/transaksi/struk.php");
    exit;
} else {
    $_SESSION['error'] = "Akses tidak sah.";
    header("Location: ../../frontend/transaksi/keranjang.php");
    exit;
}
