<?php
// Konfigurasi database
$host = "localhost"; 
$username = "root";  
$password = "";    
$dbname = "website_manajemen_kasir"; 

// Membuat koneksi
$conn = new mysqli($host, $username, $password, $dbname);

// Cek koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}
?>