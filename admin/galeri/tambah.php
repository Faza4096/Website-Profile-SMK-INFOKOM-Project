<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../../includes/koneksi.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $judul    = trim($_POST['judul'] ?? '');
    $kategori = trim($_POST['kategori'] ?? 'Kegiatan');

    if ($judul === '' || empty($_FILES['gambar']['name'])) {
        $error = 'Judul dan foto wajib diisi.';
    } else {
        $ext = strtolower(pathinfo($_FILES['gambar']['name'], PATHINFO_EXTENSION));
        $allowed = ['jpg', 'jpeg', 'png', 'webp'];
        if (!in_array($ext, $allowed)) {
            $error = 'Format gambar tidak didukung (gunakan jpg/jpeg/png/webp).';
        } else {
            $namaFile = 'galeri_' . time() . '_' . uniqid() . '.' . $ext;
            move_uploaded_file($_FILES['gambar']['tmp_name'], __DIR__ . '/../../uploads/galeri/' . $namaFile);

            $stmt = $pdo->prepare("INSERT INTO galeri (judul, gambar, kategori) VALUES (:judul, :gambar, :kategori)");
            $stmt->execute([
                ':judul'    => $judul,
                ':gambar'   => $namaFile,
                ':kategori' => $kategori,
            ]);

            header('Location: list.php?sukses=Foto berhasil diupload');
            exit;
        }
    }
}
$pageTitleAdmin = 'Upload Foto Galeri';
require_once __DIR__ . '/../includes/top.php';
?>

    <h4 class="mb-4"><i class="bi bi-plus-lg"></i> Upload Foto Galeri</h4>

    <?php if ($error): ?>
      <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <div class="card card-admin p-4" style="max-width:500px;">
      <form method="POST" enctype="multipart/form-data">
        <div class="mb-3">
          <label class="form-label fw-semibold">Judul Foto</label>
          <input type="text" name="judul" class="form-control" required value="<?= htmlspecialchars($_POST['judul'] ?? '') ?>">
        </div>
        <div class="mb-3">
          <label class="form-label fw-semibold">Kategori</label>
          <input type="text" name="kategori" class="form-control" placeholder="Contoh: Olahraga, Akademik, Ekstrakurikuler" value="<?= htmlspecialchars($_POST['kategori'] ?? 'Kegiatan') ?>">
        </div>
        <div class="mb-3">
          <label class="form-label fw-semibold">Foto</label>
          <input type="file" name="gambar" class="form-control" accept=".jpg,.jpeg,.png,.webp" required>
        </div>
        <button type="submit" class="btn btn-primary text-white">Upload</button>
        <a href="list.php" class="btn btn-outline-secondary">Batal</a>
      </form>
    </div>
<?php require_once __DIR__ . '/../includes/bottom.php'; ?>
