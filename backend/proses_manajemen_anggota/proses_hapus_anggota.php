<?php
session_start();
require_once '../../config/database.php';

// Cek apakah user login dan admin
if (!isset($_SESSION['id_user']) || $_SESSION['level'] !== 'admin') {
    header("Location: ../../frontend/manajemen_anggota/lihat_anggota.php");
    exit;
}

// Ambil id user dari URL
$id_user = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Cegah admin menghapus dirinya sendiri
if ($id_user === $_SESSION['id_user']) {
    echo "Kamu tidak bisa menghapus akunmu sendiri.";
    exit;
}

// Cek apakah user yang akan dihapus adalah admin
$sql_user = "SELECT * FROM users WHERE id_user = ?";
$stmt_user = $conn->prepare($sql_user);
$stmt_user->bind_param("i", $id_user);
$stmt_user->execute();
$result_user = $stmt_user->get_result();

if ($result_user->num_rows === 0) {
    echo "User tidak ditemukan.";
    exit;
}

$user = $result_user->fetch_assoc();

// Cek apakah admin tersisa 1
if ($user['level'] === 'admin') {
    $sql_count_admin = "SELECT COUNT(*) AS total_admin FROM users WHERE level = 'admin'";
    $result_count = $conn->query($sql_count_admin);
    $row_count = $result_count->fetch_assoc();

    if ($row_count['total_admin'] <= 1) {
        echo "Tidak bisa menghapus admin terakhir.";
        exit;
    }
}

// Cek apakah user punya transaksi
$sql_cek_transaksi = "SELECT COUNT(*) AS total_transaksi FROM transaksi WHERE id_user = ?";
$stmt_transaksi = $conn->prepare($sql_cek_transaksi);
$stmt_transaksi->bind_param("i", $id_user);
$stmt_transaksi->execute();
$result_transaksi = $stmt_transaksi->get_result();
$row_transaksi = $result_transaksi->fetch_assoc();

if ($row_transaksi['total_transaksi'] > 0) {
    echo "User tidak bisa dihapus karena masih memiliki transaksi.";
    exit;
}

// Semua pengecekan lolos → lanjut hapus user
$sql_delete = "DELETE FROM users WHERE id_user = ?";
$stmt_delete = $conn->prepare($sql_delete);
$stmt_delete->bind_param("i", $id_user);

if ($stmt_delete->execute()) {
    header("Location: ../../frontend/manajemen_anggota/lihat_anggota.php?hapus=success");
    exit;
} else {
    echo "Gagal menghapus user: " . $stmt_delete->error;
}

$conn->close();
?>
