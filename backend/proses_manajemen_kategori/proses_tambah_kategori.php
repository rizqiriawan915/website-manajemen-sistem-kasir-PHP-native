<?php
session_start();

// Proteksi: hanya admin yang bisa mengakses
if (!isset($_SESSION['id_user']) || $_SESSION['level'] !== 'admin') {
    header("Location: ../../frontend/dashboard.php");
    exit;
}

require_once '../../config/database.php';

// Cek apakah form sudah disubmit
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Ambil data dari form
    $nama_kategori = $_POST['nama_kategori'];

    // Validasi input
    if (empty($nama_kategori)) {
        $_SESSION['error'] = "Nama kategori tidak boleh kosong!";
        header("Location: ../kategori/tambah_kategori.php");
        exit;
    }

    // Masukkan data kategori ke database
    $sql = "INSERT INTO kategori_produk (nama_kategori) VALUES (?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $nama_kategori);

    if ($stmt->execute()) {
        // Jika berhasil
        $_SESSION['success'] = "Kategori berhasil ditambahkan!";
        header("Location: ../../frontend/manajemen_kategori/lihat_kategori.php");
    } else {
        // Jika gagal
        $_SESSION['error'] = "Terjadi kesalahan, kategori gagal ditambahkan.";
        header("Location: ../kategori/tambah_kategori.php");
    }

    $stmt->close();
}

$conn->close();
?>
