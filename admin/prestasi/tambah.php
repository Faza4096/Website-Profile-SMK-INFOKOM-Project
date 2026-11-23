<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../../includes/koneksi.php';
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $judul = trim($_POST['judul'] ?? ''); $deskripsi = trim($_POST['deskripsi'] ?? '');
    $tingkat = $_POST['tingkat'] ?? 'Kota/Kabupaten'; $tahun = (int)($_POST['tahun'] ?? date('Y'));
    if ($judul === '') { $error = 'Judul wajib diisi.'; }
    else {
        $namaFile = null;
        if (!empty($_FILES['gambar']['name'])) {
            $ext = strtolower(pathinfo($_FILES['gambar']['name'], PATHINFO_EXTENSION));
            if (in_array($ext, ['jpg','jpeg','png','webp'])) {
                $namaFile = 'prestasi_' . time() . '.' . $ext;
                move_uploaded_file($_FILES['gambar']['tmp_name'], __DIR__ . '/../../uploads/prestasi/' . $namaFile);
            } else { $error = 'Format gambar tidak didukung.'; }
        }
        if ($error === '') {
            $stmt = $pdo->prepare("INSERT INTO prestasi (judul, deskripsi, tingkat, tahun, gambar) VALUES (:judul,:deskripsi,:tingkat,:tahun,:gambar)");
            $stmt->execute([':judul'=>$judul, ':deskripsi'=>$deskripsi, ':tingkat'=>$tingkat, ':tahun'=>$tahun, ':gambar'=>$namaFile]);
            header('Location: list.php?sukses=Data berhasil ditambahkan'); exit;
        }
    }
}
$pageTitleAdmin = 'Tambah Prestasi';
require_once __DIR__ . '/../includes/top.php';
?>
<h4 class="mb-4"><i class="bi bi-plus-lg"></i> Tambah Prestasi</h4>
<?php if ($error): ?><div class="alert alert-danger"><?= htmlspecialchars($error) ?></div><?php endif; ?>
<div class="card card-admin p-4" style="max-width:600px;">
  <form method="POST" enctype="multipart/form-data">
    <div class="mb-3"><label class="form-label fw-semibold">Judul Prestasi</label><input type="text" name="judul" class="form-control" required></div>
    <div class="mb-3"><label class="form-label fw-semibold">Tingkat</label><select name="tingkat" class="form-select"><option value="Sekolah">Sekolah</option><option value="Kota/Kabupaten">Kota/Kabupaten</option><option value="Provinsi">Provinsi</option><option value="Nasional">Nasional</option><option value="Internasional">Internasional</option></select></div>
    <div class="mb-3"><label class="form-label fw-semibold">Tahun</label><input type="number" name="tahun" class="form-control" value="<?= date('Y') ?>" min="2000" max="2100"></div>
    <div class="mb-3"><label class="form-label fw-semibold">Gambar</label><input type="file" name="gambar" class="form-control" accept=".jpg,.jpeg,.png,.webp"></div>
    <div class="mb-3"><label class="form-label fw-semibold">Deskripsi</label><textarea name="deskripsi" rows="4" class="form-control"></textarea></div>
    <button type="submit" class="btn btn-primary text-white">Simpan</button>
    <a href="list.php" class="btn btn-outline-secondary">Batal</a>
  </form>
</div>
<?php require_once __DIR__ . '/../includes/bottom.php'; ?>
