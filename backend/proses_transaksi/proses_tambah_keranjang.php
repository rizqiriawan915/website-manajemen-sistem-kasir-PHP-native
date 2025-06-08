<?php
session_start();
require_once '../../config/database.php';

// Pastikan user sudah login
if (!isset($_SESSION['id_user'])) {
    $_SESSION['error'] = "Anda harus login terlebih dahulu.";
    header("Location: ../../frontend/login.php");
    exit;
}

// Pastikan ada id_produk yang dikirim
if (!isset($_GET['id_produk'])) {
    $_SESSION['error'] = "Produk tidak ditemukan.";
    header("Location: ../../frontend/transaksi/pilih_kategori.php");
    exit;
}

$id_user = $_SESSION['id_user'];
$id_produk = (int)$_GET['id_produk'];

// Ambil data produk
$stmt = $conn->prepare("SELECT nama_produk, harga FROM produk WHERE id_produk = ?");
$stmt->bind_param("i", $id_produk);
$stmt->execute();
$result = $stmt->get_result();
$produk = $result->fetch_assoc();
$stmt->close();

if (!$produk) {
    $_SESSION['error'] = "Produk tidak ditemukan.";
    header("Location: ../../frontend/transaksi/pilih_kategori.php");
    exit;
}

// Cek apakah produk sudah ada di keranjang
$stmt = $conn->prepare("SELECT qty FROM keranjang WHERE id_user = ? AND id_produk = ?");
$stmt->bind_param("ii", $id_user, $id_produk);
$stmt->execute();
$result = $stmt->get_result();
$data_keranjang = $result->fetch_assoc();
$stmt->close();

if ($data_keranjang) {
    // Jika sudah ada, update qty
    $stmt = $conn->prepare("UPDATE keranjang SET qty = qty + 1 WHERE id_user = ? AND id_produk = ?");
    $stmt->bind_param("ii", $id_user, $id_produk);
    $stmt->execute();
    $stmt->close();
} else {
    // Jika belum ada, insert baru
    $qty = 1;
    $stmt = $conn->prepare("INSERT INTO keranjang (id_user, id_produk, nama_produk, harga, qty) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("iisdi", $id_user, $id_produk, $produk['nama_produk'], $produk['harga'], $qty);
    $stmt->execute();
    $stmt->close();
}

$conn->close();

// Redirect ke halaman keranjang
header("Location: ../../frontend/transaksi/keranjang.php");
exit;
?>