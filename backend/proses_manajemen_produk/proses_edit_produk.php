<?php
session_start();

// Proteksi: hanya admin yang bisa akses
if (!isset($_SESSION['id_user']) || $_SESSION['level'] !== 'admin') {
    header("Location: ../../frontend/dashboard.php");
    exit;
}

require_once '../../config/database.php';

// Cek apakah data dikirim melalui POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_produk    = $_POST['id_produk'];
    $id_kategori  = $_POST['id_kategori'];  // untuk redirect balik
    $kode_produk  = trim($_POST['kode_produk']);
    $nama_produk  = trim($_POST['nama_produk']);
    $harga        = $_POST['harga'];
    $stok         = $_POST['stok'];
    $satuan       = trim($_POST['satuan']);

    // Validasi sederhana
    if ($kode_produk === '' || $nama_produk === '' || $harga === '' || $stok === '' || $satuan === '') {
        $_SESSION['error'] = "Semua field wajib diisi.";
        header("Location: ../../frontend/manajemen_produk/edit_produk.php?id=$id_produk&id_kategori=$id_kategori");
        exit;
    }

    // Update produk
    $stmt = $conn->prepare("UPDATE produk SET kode_produk = ?, nama_produk = ?, harga = ?, stok = ?, satuan = ? WHERE id_produk = ?");
    $stmt->bind_param("ssdisi", $kode_produk, $nama_produk, $harga, $stok, $satuan, $id_produk);

    if ($stmt->execute()) {
        $_SESSION['success'] = "Produk berhasil diperbarui.";
    } else {
        $_SESSION['error'] = "Gagal memperbarui produk.";
    }

    $stmt->close();
    $conn->close();

    header("Location: ../../frontend/manajemen_produk/lihat_produk.php?id_kategori=$id_kategori");
    exit;
} else {
    // Jika tidak ada data POST
    $_SESSION['error'] = "Akses tidak sah.";
    header("Location: ../../frontend/dashboard.php");
    exit;
}
