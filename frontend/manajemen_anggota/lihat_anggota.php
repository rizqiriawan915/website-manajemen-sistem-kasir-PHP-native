<?php
session_start();

// Proteksi: hanya admin yang bisa mengakses
if (!isset($_SESSION['id_user']) || $_SESSION['level'] !== 'admin') {
    header("Location: ../dashboard.php");
    exit;
}

require_once '../../config/database.php';

// Ambil data anggota dari database
$sql = "SELECT * FROM users";
$result = $conn->query($sql);

?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Manajemen Anggota</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="d-flex">
  <!-- Konten Utama -->
  <div class="flex-grow-1 p-4">
    <h2>Manajemen Anggota</h2>

    <a href="tambah_anggota.php" class="btn btn-primary mb-3">Tambah Anggota</a>
    <a href="../dashboard.php" class="btn btn-primary mb-3">Dashboard</a>

    <table class="table table-bordered">
      <thead>
        <tr>
          <th>ID</th>
          <th>Username</th>
          <th>Nama</th>
          <th>Level</th>
          <th>Aksi</th>
        </tr>
      </thead>
      <tbody>
        <?php if ($result->num_rows > 0): ?>
          <?php while ($user = $result->fetch_assoc()): ?>
            <tr>
              <td><?= $user['id_user']; ?></td>
              <td><?= $user['username']; ?></td>
              <td><?= $user['nama']; ?></td>
              <td><?= ucfirst($user['level']); ?></td>
              <td>
                <a href="edit_anggota.php?id=<?= $user['id_user']; ?>" class="btn btn-warning btn-sm">Edit</a>
                <a href="../../backend/proses_manajemen_anggota/proses_hapus_anggota.php?id=<?= $user['id_user']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus anggota ini?')">Hapus</a>
              </td>
            </tr>
          <?php endwhile; ?>
        <?php else: ?>
          <tr>
            <td colspan="5" class="text-center">Tidak ada anggota.</td>
          </tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>

</body>
</html>

<?php $conn->close(); ?>
