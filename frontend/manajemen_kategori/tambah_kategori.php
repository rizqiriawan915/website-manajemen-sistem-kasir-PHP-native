<?php
session_start();

// Proteksi: hanya admin yang bisa mengakses
if (!isset($_SESSION['id_user']) || $_SESSION['level'] !== 'admin') {
    header("Location: ../dashboard.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Tambah Kategori</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="d-flex">
  <!-- Konten Utama -->
  <div class="flex-grow-1 p-4">
    <h2>Tambah Kategori</h2>

    <a href="lihat_kategori.php" class="btn btn-primary mb-3">Kembali</a>

    <!-- Form Tambah Kategori -->
    <form action="../../backend/proses_manajemen_kategori/proses_tambah_kategori.php" method="POST">
      <div class="mb-3">
        <label for="nama_kategori" class="form-label">Nama Kategori</label>
        <input type="text" name="nama_kategori" id="nama_kategori" class="form-control" required>
      </div>
      <button type="submit" class="btn btn-primary">Simpan</button>
    </form>
  </div>

</body>
</html>
