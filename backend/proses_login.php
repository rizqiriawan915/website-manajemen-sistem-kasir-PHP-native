<?php
session_start();
require_once '../config/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    // Cek apakah username ada
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();

        // Verifikasi password
        if ($password === $user['password']) {
            // Simpan data user ke session
            $_SESSION['id_user'] = $user['id_user'];
            $_SESSION['nama'] = $user['nama'];
            $_SESSION['level'] = $user['level'];

            // Redirect sesuai level user
            if ($user['level'] === 'admin') {
                header("Location: ../frontend/dashboard.php");
            } else {
                header("Location: ../frontend/dashboard.php");
            }
            exit;
        } else {
            $_SESSION['error'] = "Password salah.";
        }
    } else {
        $_SESSION['error'] = "Username tidak ditemukan.";
    }

    $stmt->close();
    $conn->close();
    header("Location: ../frontend/login.php");
    exit;
} else {
    $_SESSION['error'] = "Akses tidak sah.";
    header("Location: ../frontend/login.php");
    exit;
}
?>  