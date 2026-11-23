<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../../includes/koneksi.php';
$id = (int)($_GET['id'] ?? 0);
$stmt = $pdo->prepare("SELECT * FROM prestasi WHERE id=?"); $stmt->execute([$id]); $data = $stmt->fetch();
if (!$data) { header('Location: list.php'); exit; }
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $judul = trim($_POST['judul'] ?? ''); $deskripsi = trim($_POST['deskripsi'] ?? '');
    $tingkat = $_POST['tingkat'] ?? 'Kota/Kabupaten'; $tahun = (int)($_POST['tahun'] ?? date('Y'));
    if ($judul === '') { $error = 'Judul wajib diisi.'; }
    else {
        $namaFile = $data['gambar'];
        if (!empty($_FILES['gambar']['name'])) {
            $ext = strtolower(pathinfo($_FILES['gambar']['name'], PATHINFO_EXTENSION));
            if (in_array($ext, ['jpg','jpeg','png','webp'])) {
                $lama = __DIR__ . '/../../uploads/prestasi/' . $data['gambar'];
                if ($data['gambar'] && file_exists($lama)) unlink($lama);
                $namaFile = 'prestasi_' . time() . '.' . $ext;
                move_uploaded_file($_FILES['gambar']['tmp_name'], __DIR__ . '/../../uploads/prestasi/' . $namaFile);
            } else { $error = 'Format gambar tidak didukung.'; }
        }
        if ($error === '') {
            $stmt = $pdo->prepare("UPDATE prestasi SET judul=:judul, deskripsi=:deskripsi, tingkat=:tingkat, tahun=:tahun, gambar=:gambar WHERE id=:id");
            $stmt->execute([':judul'=>$judul, ':deskripsi'=>$deskripsi, ':tingkat'=>$tingkat, ':tahun'=>$tahun, ':gambar'=>$namaFile, ':id'=>$id]);
            header('Location: list.php?sukses=Data berhasil diperbarui'); exit;
        }
    }
}
$pageTitleAdmin = 'Edit Prestasi';
require_once __DIR__ . '/../includes/top.php';
?>
<h4 class="mb-4"><i class="bi bi-pencil-square"></i> Edit Prestasi</h4>
<?php if ($error): ?><div class="alert alert-danger"><?= htmlspecialchars($error) ?></div><?php endif; ?>
<div class="card card-admin p-4" style="max-width:600px;">
  <form method="POST" enctype="multipart/form-data">
    <div class="mb-3"><label class="form-label fw-semibold">Judul Prestasi</label><input type="text" name="judul" class="form-control" required value="<?= htmlspecialchars($data['judul']) ?>"></div>
    <div class="mb-3"><label class="form-label fw-semibold">Tingkat</label>
      <select name="tingkat" class="form-select">
        <?php foreach (['Sekolah','Kota/Kabupaten','Provinsi','Nasional','Internasional'] as $t): ?>
          <option value="<?= $t ?>" <?= $data['tingkat']===$t?'selected':'' ?>><?= $t ?></option>
        <?php endforeach; ?>
      </select>
    </div>
    <div class="mb-3"><label class="form-label fw-semibold">Tahun</label><input type="number" name="tahun" class="form-control" value="<?= (int)$data['tahun'] ?>" min="2000" max="2100"></div>
    <?php if ($data['gambar']): ?><div class="mb-3"><img src="../../uploads/prestasi/<?= htmlspecialchars($data['gambar']) ?>" width="120" class="rounded mb-2"></div><?php endif; ?>
    <div class="mb-3"><label class="form-label fw-semibold">Ganti Gambar (opsional)</label><input type="file" name="gambar" class="form-control" accept=".jpg,.jpeg,.png,.webp"></div>
    <div class="mb-3"><label class="form-label fw-semibold">Deskripsi</label><textarea name="deskripsi" rows="4" class="form-control"><?= htmlspecialchars($data['deskripsi']) ?></textarea></div>
    <button type="submit" class="btn btn-primary text-white">Update</button>
    <a href="list.php" class="btn btn-outline-secondary">Batal</a>
  </form>
</div>
<?php require_once __DIR__ . '/../includes/bottom.php'; ?>
