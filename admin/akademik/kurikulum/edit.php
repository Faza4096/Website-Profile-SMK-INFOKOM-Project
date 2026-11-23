<?php
require_once __DIR__ . '/../../includes/auth.php';
require_once __DIR__ . '/../../../includes/koneksi.php';
$id = (int)($_GET['id'] ?? 0);
$stmt = $pdo->prepare("SELECT * FROM kurikulum WHERE id=?"); $stmt->execute([$id]); $data = $stmt->fetch();
if (!$data) { header('Location: list.php'); exit; }
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $judul = trim($_POST['judul'] ?? ''); $deskripsi = trim($_POST['deskripsi'] ?? '');
    if ($judul === '') { $error = 'Judul wajib diisi.'; }
    else {
        $gambarFile = $data['gambar']; $dokumenFile = $data['file_dokumen'];
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
            }
        }
        if ($error === '') {
            $stmt = $pdo->prepare("UPDATE kurikulum SET judul=:judul, deskripsi=:deskripsi, gambar=:gambar, file_dokumen=:dokumen WHERE id=:id");
            $stmt->execute([':judul'=>$judul, ':deskripsi'=>$deskripsi, ':gambar'=>$gambarFile, ':dokumen'=>$dokumenFile, ':id'=>$id]);
            header('Location: list.php?sukses=Data berhasil diperbarui'); exit;
        }
    }
}
$pageTitleAdmin = 'Edit Kurikulum';
require_once __DIR__ . '/../../includes/top.php';
?>
<h4 class="mb-4"><i class="bi bi-pencil-square"></i> Edit Kurikulum</h4>
<?php if ($error): ?><div class="alert alert-danger"><?= htmlspecialchars($error) ?></div><?php endif; ?>
<div class="card card-admin p-4" style="max-width:600px;">
  <form method="POST" enctype="multipart/form-data">
    <div class="mb-3"><label class="form-label fw-semibold">Judul</label><input type="text" name="judul" class="form-control" required value="<?= htmlspecialchars($data['judul']) ?>"></div>
    <div class="mb-3"><label class="form-label fw-semibold">Deskripsi</label><textarea name="deskripsi" rows="5" class="form-control"><?= htmlspecialchars($data['deskripsi']) ?></textarea></div>
    <div class="mb-3"><label class="form-label fw-semibold">Ganti Gambar (opsional)</label><input type="file" name="gambar" class="form-control" accept=".jpg,.jpeg,.png,.webp"></div>
    <div class="mb-3"><label class="form-label fw-semibold">Ganti Dokumen PDF (opsional)</label><input type="file" name="file_dokumen" class="form-control" accept=".pdf"></div>
    <button type="submit" class="btn btn-primary text-white">Update</button>
    <a href="list.php" class="btn btn-outline-secondary">Batal</a>
  </form>
</div>
<?php require_once __DIR__ . '/../../includes/bottom.php'; ?>
