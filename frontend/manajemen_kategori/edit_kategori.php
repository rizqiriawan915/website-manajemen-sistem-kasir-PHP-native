<?php
session_start();

// Proteksi: hanya admin yang bisa mengakses
if (!isset($_SESSION['id_user']) || $_SESSION['level'] !== 'admin') {
    header("Location: ../../frontend/dashboard.php");
    exit;
}

require_once '../../config/database.php';

// Ambil id kategori dari URL
if (isset($_GET['id'])) {
    $id_kategori = $_GET['id'];

    // Ambil data kategori dari database
    $sql = "SELECT * FROM kategori_produk WHERE id_kategori = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id_kategori);
    $stmt->execute();
    $result = $stmt->get_result();
    $kategori = $result->fetch_assoc();

    // Jika kategori tidak ditemukan
    if (!$kategori) {
        $_SESSION['error'] = "Kategori tidak ditemukan!";
        header("Location: ../../frontend/manajemen_kategori/lihat_kategori.php");
        exit;
    }

    $stmt->close();
} else {
    // Jika tidak ada id di URL
    header("Location: ../../frontend/manajemen_kategori/lihat_kategori.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Edit Kategori</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="d-flex">
  <!-- Konten Utama -->
  <div class="flex-grow-1 p-4">
    <h2>Edit Kategori</h2>

    <a href="lihat_kategori.php" class="btn btn-primary mb-3">Kembali</a>

    <!-- Form Edit Kategori -->
    <form action="../../backend/proses_manajemen_kategori/proses_edit_kategori.php" method="POST">
      <input type="hidden" name="id_kategori" value="<?= $kategori['id_kategori']; ?>">

      <div class="mb-3">
        <label for="nama_kategori" class="form-label">Nama Kategori</label>
        <input type="text" name="nama_kategori" id="nama_kategori" class="form-control" value="<?= $kategori['nama_kategori']; ?>" required>
      </div>
      <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
    </form>
  </div>

</body>
</html>

<?php $conn->close(); ?>
