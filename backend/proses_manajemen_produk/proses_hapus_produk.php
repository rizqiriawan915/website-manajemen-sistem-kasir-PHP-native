<?php
session_start();

// Cek login & admin
if (!isset($_SESSION['id_user']) || $_SESSION['level'] !== 'admin') {
    header("Location: ../../frontend/dashboard.php");
    exit;
}

require_once '../../config/database.php';

// Pastikan id produk dan id kategori ada
if (isset($_GET['id']) && isset($_GET['id_kategori'])) {
    $id_produk = $_GET['id'];
    $id_kategori = $_GET['id_kategori'];

    // Cek apakah produk benar-benar ada (opsional, untuk keamanan)
    $stmt_check = $conn->prepare("SELECT id_produk FROM produk WHERE id_produk = ?");
    $stmt_check->bind_param("i", $id_produk);
    $stmt_check->execute();
    $stmt_check->store_result();

    if ($stmt_check->num_rows === 0) {
        $_SESSION['error'] = "Produk tidak ditemukan.";
        header("Location: ../../frontend/manajemen_produk/lihat_produk.php?id_kategori=$id_kategori");
        exit;
    }

    $stmt_check->close();

    // Hapus semua transaksi detail yang terkait produk ini
    $stmt_del_detail = $conn->prepare("DELETE FROM transaksi_detail WHERE id_produk = ?");
    $stmt_del_detail->bind_param("i", $id_produk);
    $stmt_del_detail->execute();
    $stmt_del_detail->close();

    // Lanjutkan proses hapus produk
    $stmt = $conn->prepare("DELETE FROM produk WHERE id_produk = ?");
    $stmt->bind_param("i", $id_produk);

    if ($stmt->execute()) {
        $_SESSION['success'] = "Produk dan data transaksi terkait berhasil dihapus.";
    } else {
        $_SESSION['error'] = "Gagal menghapus produk.";
    }

    $stmt->close();
    $conn->close();
    header("Location: ../../frontend/manajemen_produk/lihat_produk.php?id_kategori=$id_kategori");
    exit;
} else {
    $_SESSION['error'] = "Data tidak lengkap untuk menghapus produk.";
    header("Location: ../../frontend/dashboard.php");
    exit;
}
?>
