<?php
require_once __DIR__ . '/../../includes/auth.php';
require_once __DIR__ . '/../../../includes/koneksi.php';
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $judul = trim($_POST['judul'] ?? ''); $deskripsi = trim($_POST['deskripsi'] ?? '');
    if ($judul === '') { $error = 'Judul wajib diisi.'; }
    else {
        $gambarFile = null; $dokumenFile = null;
        if (!empty($_FILES['gambar']['name'])) {
            $ext = strtolower(pathinfo($_FILES['gambar']['name'], PATHINFO_EXTENSION));
            if (in_array($ext, ['jpg','jpeg','png','webp'])) {
                $gambarFile = 'kurikulum_' . time() . '.' . $ext;
                move_uploaded_file($_FILES['gambar']['tmp_name'], __DIR__ . '/../../../uploads/kurikulum/' . $gambarFile);
            }
        }
        if (!empty($_FILES['file_dokumen']['name'])) {
            $ext = strtolower(pathinfo($_FILES['file_dokumen']['name'], PATHINFO_EXTENSION));
            if ($ext === 'pdf') {
                $dokumenFile = 'dok_kurikulum_' . time() . '.' . $ext;
                move_uploaded_file($_FILES['file_dokumen']['tmp_name'], __DIR__ . '/../../../uploads/kurikulum/' . $dokumenFile);
            } else { $error = 'Dokumen harus berformat PDF.'; }
        }
        if ($error === '') {
            $stmt = $pdo->prepare("INSERT INTO kurikulum (judul, deskripsi, gambar, file_dokumen) VALUES (:judul,:deskripsi,:gambar,:dokumen)");
            $stmt->execute([':judul'=>$judul, ':deskripsi'=>$deskripsi, ':gambar'=>$gambarFile, ':dokumen'=>$dokumenFile]);
            header('Location: list.php?sukses=Data berhasil ditambahkan'); exit;
        }
    }
}
$pageTitleAdmin = 'Tambah Kurikulum';
require_once __DIR__ . '/../../includes/top.php';
?>
<h4 class="mb-4"><i class="bi bi-plus-lg"></i> Tambah Kurikulum</h4>
<?php if ($error): ?><div class="alert alert-danger"><?= htmlspecialchars($error) ?></div><?php endif; ?>
<div class="card card-admin p-4" style="max-width:600px;">
  <form method="POST" enctype="multipart/form-data">
    <div class="mb-3"><label class="form-label fw-semibold">Judul</label><input type="text" name="judul" class="form-control" required></div>
    <div class="mb-3"><label class="form-label fw-semibold">Deskripsi</label><textarea name="deskripsi" rows="5" class="form-control"></textarea></div>
    <div class="mb-3"><label class="form-label fw-semibold">Gambar (opsional)</label><input type="file" name="gambar" class="form-control" accept=".jpg,.jpeg,.png,.webp"></div>
    <div class="mb-3"><label class="form-label fw-semibold">Dokumen PDF (opsional)</label><input type="file" name="file_dokumen" class="form-control" accept=".pdf"></div>
    <button type="submit" class="btn btn-primary text-white">Simpan</button>
    <a href="list.php" class="btn btn-outline-secondary">Batal</a>
  </form>
</div>
<?php require_once __DIR__ . '/../../includes/bottom.php'; ?>
