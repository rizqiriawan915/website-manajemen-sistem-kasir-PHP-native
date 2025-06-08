<?php
session_start();
require_once '../../config/database.php';

// Cek apakah yang login adalah admin
if (!isset($_SESSION['id_user']) || $_SESSION['level'] !== 'admin') {
    header("Location: ../../frontend/manajemen_anggota/lihat_anggota.php");
    exit;
}

// Ambil data dari form
$id_user = intval($_POST['id_user']);
$username = trim($_POST['username']);
$password = trim($_POST['password']);
$nama = trim($_POST['nama']);
$level = $_POST['level'];

// Validasi data dasar
if (empty($username) || empty($nama) || empty($level)) {
    echo "Data tidak lengkap. Silakan kembali dan isi semua field.";
    exit;
}

// Cek apakah password diisi atau tidak
if (!empty($password)) {
    // Update semua field termasuk password
    $sql = "UPDATE users 
                SET username = ?, password = ?, nama = ?, level = ?
                WHERE id_user = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssi", $username, $password, $nama, $level, $id_user);
} else {
    // Update tanpa password
    $sql = "UPDATE users 
                SET username = ?, nama = ?, level = ?
                WHERE id_user = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssi", $username, $nama, $level, $id_user);
}

if ($stmt->execute()) {
    // Berhasil update, arahkan balik ke halaman manajemen anggota
    header("Location: ../../frontend/manajemen_anggota/lihat_anggota.php?update=success");
    exit;
} else {
    echo "Terjadi kesalahan saat update data: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
