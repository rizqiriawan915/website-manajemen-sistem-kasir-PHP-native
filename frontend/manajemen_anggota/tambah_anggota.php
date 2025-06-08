<?php
session_start();

// Proteksi: hanya admin yang bisa mengakses
if (!isset($_SESSION['id_user']) ) {
    header("Location: ../../dashboard.php");
    exit;
}

require_once '../../config/database.php';
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Tambah Anggota</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="d-flex">

  <!-- Konten Utama -->
  <div class="flex-grow-1 p-4">
    <h2>Tambah Anggota</h2>

    <?php if (!empty($successMessage)): ?>
      <div class="alert alert-success">
        <?= $successMessage; ?>
      </div>
    <?php endif; ?>

    <?php if (!empty($errorMessage)): ?>
      <div class="alert alert-danger">
        <?= $errorMessage; ?>
      </div>
    <?php endif; ?>

    <!-- Form Tambah Anggota -->
    <form action="../../backend/proses_manajemen_anggota/proses_tambah_anggota.php" method="POST">
      <div class="mb-3">
        <label for="username" class="form-label">Username</label>
        <input type="text" class="form-control" id="username" name="username" required>
      </div>

      <div class="mb-3">
        <label for="password" class="form-label">Password</label>
        <input type="password" class="form-control" id="password" name="password" required>
      </div>

      <div class="mb-3">
        <label for="nama" class="form-label">Nama</label>
        <input type="text" class="form-control" id="nama" name="nama" required>
      </div>

      <div class="mb-3">
        <label for="level" class="form-label">Level</label>
        <select class="form-control" id="level" name="level" required>
          <option value="admin">Admin</option>
          <option value="kasir">Kasir</option>
        </select>
      </div>

      <button type="submit" class="btn btn-primary">Tambah Anggota</button>
      <a href="lihat_anggota.php" class="btn btn-secondary">Kembali</a>
    </form>
  </div>

</body>
</html>