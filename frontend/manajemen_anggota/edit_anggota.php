<?php
session_start();
require_once '../../config/database.php';

// Proteksi: hanya admin yang boleh akses
if (!isset($_SESSION['id_user']) || $_SESSION['level'] !== 'admin') {
    header("Location: dashboard.php");
    exit;
}

// Ambil ID dari URL
$id_user = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Ambil data user dari database
$sql = "SELECT * FROM users WHERE id_user = $id_user";
$result = $conn->query($sql);

if ($result->num_rows === 0) {
    echo "User tidak ditemukan.";
    exit;
}

$user = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Edit Anggota</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="d-flex">
  
  <!-- Konten Utama -->
  <div class="flex-grow-1 p-4">
    <h2>Edit Anggota</h2>

    <!-- Form Edit -->
    <form action="../../backend/proses_manajemen_anggota/proses_edit_anggota.php" method="POST">
      <!-- Kirim id_user secara tersembunyi -->
      <input type="hidden" name="id_user" value="<?= $user['id_user']; ?>">

      <div class="mb-3">
        <label for="username" class="form-label">Username</label>
        <input type="text" name="username" id="username" class="form-control" required value="<?= htmlspecialchars($user['username']); ?>">
      </div>

      <div class="mb-3">
        <label for="password" class="form-label">Password (biarkan kosong jika tidak ingin diubah)</label>
        <input type="password" name="password" id="password" class="form-control">
      </div>

      <div class="mb-3">
        <label for="nama" class="form-label">Nama</label>
        <input type="text" name="nama" id="nama" class="form-control" required value="<?= htmlspecialchars($user['nama']); ?>">
      </div>

      <div class="mb-3">
        <label for="level" class="form-label">Level</label>
        <select name="level" id="level" class="form-control" required>
          <option value="admin" <?= $user['level'] === 'admin' ? 'selected' : '' ?>>Admin</option>
          <option value="kasir" <?= $user['level'] === 'kasir' ? 'selected' : '' ?>>Kasir</option>
        </select>
      </div>

      <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
      <a href="lihat_anggota.php" class="btn btn-secondary">Batal</a>
    </form>
  </div>

</body>
</html>

<?php $conn->close(); ?>
