<?php
session_start();

// Cek apakah user sudah login dan admin
if (!isset($_SESSION['id_user']) || $_SESSION['level'] !== 'admin') {
    header("Location: ../../frontend/dashboard.php");
    exit;
}

require_once '../../config/database.php';

// Proses form jika disubmit
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $id_kategori = $_POST['id_kategori'];
    $kode_produk = $_POST['kode_produk'];
    $nama_produk = $_POST['nama_produk'];
    $harga = $_POST['harga'];
    $stok = $_POST['stok'];
    $satuan = $_POST['satuan'];

    // Validasi sederhana (bisa ditambahkan sesuai kebutuhan)
    if (empty($kode_produk) || empty($nama_produk) || empty($harga)) {
        $_SESSION['error'] = "Kode, nama produk, dan harga tidak boleh kosong.";
        header("Location: ../../frontend/manajemen_produk/tambah_produk.php?id_kategori=$id_kategori");
        exit;
    }

    // Masukkan data ke database
    $stmt = $conn->prepare("INSERT INTO produk (id_kategori, kode_produk, nama_produk, harga, stok, satuan) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("issdis", $id_kategori, $kode_produk, $nama_produk, $harga, $stok, $satuan);

    if ($stmt->execute()) {
        $_SESSION['success'] = "Produk berhasil ditambahkan.";
        header("Location: ../../frontend/manajemen_produk/lihat_produk.php?id_kategori=$id_kategori");
    } else {
        $_SESSION['error'] = "Gagal menambahkan produk: " . $conn->error;
        header("Location: ../../frontend/manajemen_produk/tambah_produk.php?id_kategori=$id_kategori");
    }

    $stmt->close();
    $conn->close();
} else {
    header("Location: ../../frontend/dashboard.php");
    exit;
}
?>
