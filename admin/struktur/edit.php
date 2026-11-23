<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../../includes/koneksi.php';
$id = (int)($_GET['id'] ?? 0);
$stmt = $pdo->prepare("SELECT * FROM struktur_organisasi WHERE id=?"); $stmt->execute([$id]); $data = $stmt->fetch();
if (!$data) { header('Location: list.php'); exit; }
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $jabatan = trim($_POST['jabatan'] ?? ''); $nama = trim($_POST['nama'] ?? ''); $urutan = (int)($_POST['urutan'] ?? 0);
    if ($jabatan === '' || $nama === '') { $error = 'Jabatan dan nama wajib diisi.'; }
    else {
        $namaFile = $data['foto'];
        if (!empty($_FILES['foto']['name'])) {
            $ext = strtolower(pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION));
            if (in_array($ext, ['jpg','jpeg','png','webp'])) {
                $lama = __DIR__ . '/../../uploads/struktur/' . $data['foto'];
                if ($data['foto'] && file_exists($lama)) unlink($lama);
                $namaFile = 'struktur_' . time() . '.' . $ext;
                move_uploaded_file($_FILES['foto']['tmp_name'], __DIR__ . '/../../uploads/struktur/' . $namaFile);
            } else { $error = 'Format foto tidak didukung.'; }
        }
        if ($error === '') {
            $stmt = $pdo->prepare("UPDATE struktur_organisasi SET jabatan=:jabatan, nama=:nama, foto=:foto, urutan=:urutan WHERE id=:id");
            $stmt->execute([':jabatan'=>$jabatan, ':nama'=>$nama, ':foto'=>$namaFile, ':urutan'=>$urutan, ':id'=>$id]);
            header('Location: list.php?sukses=Data berhasil diperbarui'); exit;
        }
    }
}
$pageTitleAdmin = 'Edit Struktur Organisasi';
require_once __DIR__ . '/../includes/top.php';
?>
<h4 class="mb-4"><i class="bi bi-pencil-square"></i> Edit Struktur Organisasi</h4>
<?php if ($error): ?><div class="alert alert-danger"><?= htmlspecialchars($error) ?></div><?php endif; ?>
<div class="card card-admin p-4" style="max-width:600px;">
  <form method="POST" enctype="multipart/form-data">
    <div class="mb-3"><label class="form-label fw-semibold">Jabatan</label><input type="text" name="jabatan" class="form-control" required value="<?= htmlspecialchars($data['jabatan']) ?>"></div>
    <div class="mb-3"><label class="form-label fw-semibold">Nama</label><input type="text" name="nama" class="form-control" required value="<?= htmlspecialchars($data['nama']) ?>"></div>
    <?php if ($data['foto']): ?><div class="mb-3"><img src="../../uploads/struktur/<?= htmlspecialchars($data['foto']) ?>" width="100" class="rounded mb-2"></div><?php endif; ?>
    <div class="mb-3"><label class="form-label fw-semibold">Ganti Foto (opsional)</label><input type="file" name="foto" class="form-control" accept=".jpg,.jpeg,.png,.webp"></div>
    <div class="mb-3"><label class="form-label fw-semibold">Urutan Tampil</label><input type="number" name="urutan" class="form-control" value="<?= (int)$data['urutan'] ?>"></div>
    <button type="submit" class="btn btn-primary text-white">Update</button>
    <a href="list.php" class="btn btn-outline-secondary">Batal</a>
  </form>
</div>
<?php require_once __DIR__ . '/../includes/bottom.php'; ?>
