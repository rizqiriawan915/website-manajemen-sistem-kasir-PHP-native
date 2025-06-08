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
    $id_kategori = $_POST['id_kategori'];
    $nama_kategori = $_POST['nama_kategori'];

    // Validasi input
    if (empty($nama_kategori)) {
        $_SESSION['error'] = "Nama kategori tidak boleh kosong!";
        header("Location: ../../frontend/manajemen_kategori/lihat_kategori.php?id=" . $id_kategori);
        exit;
    }

    // Update kategori di database
    $sql = "UPDATE kategori_produk SET nama_kategori = ? WHERE id_kategori = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $nama_kategori, $id_kategori);

    if ($stmt->execute()) {
        // Jika berhasil
        $_SESSION['success'] = "Kategori berhasil diubah!";
        header("Location: ../../frontend/manajemen_kategori/lihat_kategori.php");
    } else {
        // Jika gagal
        $_SESSION['error'] = "Terjadi kesalahan, kategori gagal diubah.";
        header("Location: ../../manajemen_kategori/lihat_kategori.php?id=" . $id_kategori);
    }

    $stmt->close();
}

$conn->close();
?>
