<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../../includes/koneksi.php';
$id = (int)($_GET['id'] ?? 0);
$stmt = $pdo->prepare("SELECT * FROM fasilitas WHERE id=?"); $stmt->execute([$id]); $data = $stmt->fetch();
if (!$data) { header('Location: list.php'); exit; }
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = trim($_POST['nama'] ?? ''); $deskripsi = trim($_POST['deskripsi'] ?? ''); $urutan = (int)($_POST['urutan'] ?? 0);
    if ($nama === '') { $error = 'Nama fasilitas wajib diisi.'; }
    else {
        $namaFile = $data['gambar'];
        if (!empty($_FILES['gambar']['name'])) {
            $ext = strtolower(pathinfo($_FILES['gambar']['name'], PATHINFO_EXTENSION));
            if (in_array($ext, ['jpg','jpeg','png','webp'])) {
                $lama = __DIR__ . '/../../uploads/fasilitas/' . $data['gambar'];
                if ($data['gambar'] && file_exists($lama)) unlink($lama);
                $namaFile = 'fasilitas_' . time() . '.' . $ext;
                move_uploaded_file($_FILES['gambar']['tmp_name'], __DIR__ . '/../../uploads/fasilitas/' . $namaFile);
            } else { $error = 'Format gambar tidak didukung.'; }
        }
        if ($error === '') {
            $stmt = $pdo->prepare("UPDATE fasilitas SET nama=:nama, deskripsi=:deskripsi, gambar=:gambar, urutan=:urutan WHERE id=:id");
            $stmt->execute([':nama'=>$nama, ':deskripsi'=>$deskripsi, ':gambar'=>$namaFile, ':urutan'=>$urutan, ':id'=>$id]);
            header('Location: list.php?sukses=Data berhasil diperbarui'); exit;
        }
    }
}
$pageTitleAdmin = 'Edit Fasilitas';
require_once __DIR__ . '/../includes/top.php';
?>
<h4 class="mb-4"><i class="bi bi-pencil-square"></i> Edit Fasilitas</h4>
<?php if ($error): ?><div class="alert alert-danger"><?= htmlspecialchars($error) ?></div><?php endif; ?>
<div class="card card-admin p-4" style="max-width:600px;">
  <form method="POST" enctype="multipart/form-data">
    <div class="mb-3"><label class="form-label fw-semibold">Nama Fasilitas</label><input type="text" name="nama" class="form-control" required value="<?= htmlspecialchars($data['nama']) ?>"></div>
    <?php if ($data['gambar']): ?><div class="mb-3"><img src="../../uploads/fasilitas/<?= htmlspecialchars($data['gambar']) ?>" width="120" class="rounded mb-2"></div><?php endif; ?>
    <div class="mb-3"><label class="form-label fw-semibold">Ganti Gambar (opsional)</label><input type="file" name="gambar" class="form-control" accept=".jpg,.jpeg,.png,.webp"></div>
    <div class="mb-3"><label class="form-label fw-semibold">Deskripsi</label><textarea name="deskripsi" rows="4" class="form-control"><?= htmlspecialchars($data['deskripsi']) ?></textarea></div>
    <div class="mb-3"><label class="form-label fw-semibold">Urutan Tampil</label><input type="number" name="urutan" class="form-control" value="<?= (int)$data['urutan'] ?>"></div>
    <button type="submit" class="btn btn-primary text-white">Update</button>
    <a href="list.php" class="btn btn-outline-secondary">Batal</a>
  </form>
</div>
<?php require_once __DIR__ . '/../includes/bottom.php'; ?>
