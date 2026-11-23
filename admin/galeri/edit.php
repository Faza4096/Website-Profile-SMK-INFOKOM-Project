<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../../includes/koneksi.php';

$id = (int)($_GET['id'] ?? 0);
$stmt = $pdo->prepare("SELECT * FROM galeri WHERE id = ?");
$stmt->execute([$id]);
$data = $stmt->fetch();

if (!$data) {
    header('Location: list.php');
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $judul    = trim($_POST['judul'] ?? '');
    $kategori = trim($_POST['kategori'] ?? 'Kegiatan');

    if ($judul === '') {
        $error = 'Judul wajib diisi.';
    } else {
        $namaFile = $data['gambar'];

        if (!empty($_FILES['gambar']['name'])) {
            $ext = strtolower(pathinfo($_FILES['gambar']['name'], PATHINFO_EXTENSION));
            $allowed = ['jpg', 'jpeg', 'png', 'webp'];
            if (!in_array($ext, $allowed)) {
                $error = 'Format gambar tidak didukung.';
            } else {
                $lama = __DIR__ . '/../../uploads/galeri/' . $data['gambar'];
                if (file_exists($lama)) {
                    unlink($lama);
                }
                $namaFile = 'galeri_' . time() . '_' . uniqid() . '.' . $ext;
                move_uploaded_file($_FILES['gambar']['tmp_name'], __DIR__ . '/../../uploads/galeri/' . $namaFile);
            }
        }

        if ($error === '') {
            $stmt = $pdo->prepare("UPDATE galeri SET judul=:judul, kategori=:kategori, gambar=:gambar WHERE id=:id");
            $stmt->execute([
                ':judul'    => $judul,
                ':kategori' => $kategori,
                ':gambar'   => $namaFile,
                ':id'       => $id,
            ]);
            header('Location: list.php?sukses=Foto berhasil diperbarui');
            exit;
        }
    }
}
$pageTitleAdmin = 'Edit Foto Galeri';
require_once __DIR__ . '/../includes/top.php';
?>

    <h4 class="mb-4"><i class="bi bi-pencil-square"></i> Edit Foto Galeri</h4>

    <?php if ($error): ?>
      <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <div class="card card-admin p-4" style="max-width:500px;">
      <form method="POST" enctype="multipart/form-data">
        <div class="mb-3">
          <label class="form-label fw-semibold">Judul Foto</label>
          <input type="text" name="judul" class="form-control" required value="<?= htmlspecialchars($data['judul']) ?>">
        </div>
        <div class="mb-3">
          <label class="form-label fw-semibold">Kategori</label>
          <input type="text" name="kategori" class="form-control" value="<?= htmlspecialchars($data['kategori']) ?>">
        </div>
        <div class="mb-3">
          <label class="form-label fw-semibold">Foto Saat Ini</label><br>
          <img src="../../uploads/galeri/<?= htmlspecialchars($data['gambar']) ?>" width="150" class="rounded mb-2">
        </div>
        <div class="mb-3">
          <label class="form-label fw-semibold">Ganti Foto (opsional)</label>
          <input type="file" name="gambar" class="form-control" accept=".jpg,.jpeg,.png,.webp">
        </div>
        <button type="submit" class="btn btn-primary text-white">Update</button>
        <a href="list.php" class="btn btn-outline-secondary">Batal</a>
      </form>
    </div>
<?php require_once __DIR__ . '/../includes/bottom.php'; ?>
