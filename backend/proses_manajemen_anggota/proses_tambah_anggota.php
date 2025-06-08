<?php
session_start();

// Proteksi: hanya admin yang bisa mengakses
if (!isset($_SESSION['id_user'])) {
    header("Location: ../../dashboard.php");
    exit;
}

require_once '../../config/database.php';

// Variabel untuk menampung pesan
$successMessage = '';
$errorMessage = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    $nama = trim($_POST['nama']);
    $level = $_POST['level'];

    // Validasi input
    if (empty($username) || empty($password) || empty($nama) || empty($level)) {
        $errorMessage = 'Semua field harus diisi.';
    } else {
        // Encrypt password jika perlu, bisa disesuaikan
        // $hashedPassword = password_hash($password, PASSWORD_DEFAULT); // Untuk menggunakan hash
        $hashedPassword = $password; // Menggunakan password plain text (sesuai permintaan)

        // Query untuk menambah anggota
        $sql = "INSERT INTO users (username, password, nama, level) VALUES ('$username', '$hashedPassword', '$nama', '$level')";
        
        if ($conn->query($sql) === TRUE) {
            $successMessage = 'Anggota berhasil ditambahkan!';
            header("Location: ../../frontend/manajemen_anggota/lihat_anggota.php");
            exit;
        } else {
            $errorMessage = 'Terjadi kesalahan: ' . $conn->error;
        }
    }
}
?>