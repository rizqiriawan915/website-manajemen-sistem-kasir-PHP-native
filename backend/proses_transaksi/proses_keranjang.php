<?php
session_start();
require_once '../../config/database.php';

if (!isset($_SESSION['id_user'])) {
    $_SESSION['error'] = "Anda harus login terlebih dahulu.";
    header("Location: ../../login.php");
    exit;
}

if (isset($_GET['id_produk']) && isset($_GET['aksi'])) {
    $id_produk = (int) $_GET['id_produk'];
    $aksi = $_GET['aksi'];
    $id_user = $_SESSION['id_user'];

    // Ambil data produk
    $stmt = $conn->prepare("SELECT id_produk, nama_produk, harga FROM produk WHERE id_produk = ?");
    $stmt->bind_param("i", $id_produk);
    $stmt->execute();
    $result = $stmt->get_result();
    $produk = $result->fetch_assoc();
    $stmt->close();

    if (!$produk) {
        $_SESSION['error'] = "Produk tidak ditemukan.";
        header("Location: ../../frontend/transaksi/keranjang.php");
        exit;
    }

    // Cek apakah produk sudah ada di keranjang user ini
    $stmt_cek = $conn->prepare("SELECT qty FROM keranjang WHERE id_user = ? AND id_produk = ?");
    $stmt_cek->bind_param("ii", $id_user, $id_produk);
    $stmt_cek->execute();
    $result_cek = $stmt_cek->get_result();
    $data_keranjang = $result_cek->fetch_assoc();
    $stmt_cek->close();

    // Proses aksi tambah atau kurangi
    if ($aksi === 'tambah') {
        if ($data_keranjang) {
            // Produk sudah ada → update qty
            $stmt_update = $conn->prepare("UPDATE keranjang SET qty = qty + 1 WHERE id_user = ? AND id_produk = ?");
            $stmt_update->bind_param("ii", $id_user, $id_produk);
            $stmt_update->execute();
            $stmt_update->close();
        } else {
            // Produk belum ada → insert baru
            $qty = 1;
            $stmt_insert = $conn->prepare("
                INSERT INTO keranjang (id_user, id_produk, qty, harga_saat_ini, nama_produk_saat_ini)
                VALUES (?, ?, ?, ?, ?)
            ");
            $stmt_insert->bind_param("iiids", $id_user, $id_produk, $qty, $produk['harga'], $produk['nama_produk']);
            $stmt_insert->execute();
            $stmt_insert->close();
        }

    } elseif ($aksi === 'kurangi') {
        if ($data_keranjang) {
            if ($data_keranjang['qty'] > 1) {
                // Kurangi qty
                $stmt_kurangi = $conn->prepare("UPDATE keranjang SET qty = qty - 1 WHERE id_user = ? AND id_produk = ?");
                $stmt_kurangi->bind_param("ii", $id_user, $id_produk);
                $stmt_kurangi->execute();
                $stmt_kurangi->close();
            } else {
                // Jika tinggal 1 → hapus dari keranjang
                $stmt_hapus = $conn->prepare("DELETE FROM keranjang WHERE id_user = ? AND id_produk = ?");
                $stmt_hapus->bind_param("ii", $id_user, $id_produk);
                $stmt_hapus->execute();
                $stmt_hapus->close();
            }
        }
    }

    $conn->close();
    header("Location: ../../frontend/transaksi/keranjang.php");
    exit;
} else {
    $_SESSION['error'] = "Permintaan tidak valid.";
    header("Location: ../../frontend/transaksi/keranjang.php");
    exit;
}
?>