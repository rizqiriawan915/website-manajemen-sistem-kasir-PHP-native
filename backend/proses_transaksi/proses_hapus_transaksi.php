<?php
session_start();
require_once '../../config/database.php';

if (isset($_GET['id_produk'])) {
    $id_produk = (int) $_GET['id_produk'];
    $id_user = $_SESSION['id_user'];

    // Hapus produk dari keranjang di database
    $stmt = $conn->prepare("DELETE FROM keranjang WHERE id_user = ? AND id_produk = ?");
    $stmt->bind_param("ii", $id_user, $id_produk);
    $stmt->execute();
    $stmt->close();
    $conn->close();
}

header("Location: ../../frontend/transaksi/keranjang.php");
exit;

?>