<?php
session_start();

// Proteksi: hanya admin yang bisa mengakses
if (!isset($_SESSION['id_user']) || $_SESSION['level'] !== 'admin') {
    header("Location: ../../frontend/dashboard.php");
    exit;
}

require_once '../../config/database.php';

// Periksa apakah ID kategori dikirim melalui URL
if (isset($_GET['id'])) {
    $id_kategori = $_GET['id'];

    // 1. Hapus transaksi detail untuk semua produk di kategori ini
    $sql = "DELETE FROM transaksi_detail WHERE id_produk IN (SELECT id_produk FROM produk WHERE id_kategori = ? )";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id_kategori);
    $stmt->execute();
    $stmt->close();

    // 2. Hapus semua produk di kategori ini
    $sql = "DELETE FROM produk WHERE id_kategori = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id_kategori);
    $stmt->execute();
    $stmt->close();

    // 3. Hapus kategori
    $sql = "DELETE FROM kategori_produk WHERE id_kategori = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id_kategori);
    if ($stmt->execute()) {
        $_SESSION['success'] = "Kategori, produk, dan transaksi terkait berhasil dihapus.";
    } else {
        $_SESSION['error'] = "Terjadi kesalahan saat menghapus kategori.";
    }
    $stmt->close();
} else {
    $_SESSION['error'] = "ID kategori tidak ditemukan.";
}

$conn->close();
header("Location: ../../frontend/manajemen_kategori/lihat_kategori.php");
exit;
?>
