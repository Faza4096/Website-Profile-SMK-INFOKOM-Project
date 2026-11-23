<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../../includes/koneksi.php';
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = trim($_POST['nama'] ?? ''); $deskripsi = trim($_POST['deskripsi'] ?? ''); $urutan = (int)($_POST['urutan'] ?? 0);
    if ($nama === '') { $error = 'Nama fasilitas wajib diisi.'; }
    else {
        $namaFile = null;
        if (!empty($_FILES['gambar']['name'])) {
            $ext = strtolower(pathinfo($_FILES['gambar']['name'], PATHINFO_EXTENSION));
            if (in_array($ext, ['jpg','jpeg','png','webp'])) {
                $namaFile = 'fasilitas_' . time() . '.' . $ext;
                move_uploaded_file($_FILES['gambar']['tmp_name'], __DIR__ . '/../../uploads/fasilitas/' . $namaFile);
            } else { $error = 'Format gambar tidak didukung.'; }
        }
        if ($error === '') {
            $stmt = $pdo->prepare("INSERT INTO fasilitas (nama, deskripsi, gambar, urutan) VALUES (:nama,:deskripsi,:gambar,:urutan)");
            $stmt->execute([':nama'=>$nama, ':deskripsi'=>$deskripsi, ':gambar'=>$namaFile, ':urutan'=>$urutan]);
            header('Location: list.php?sukses=Data berhasil ditambahkan'); exit;
        }
    }
}
$pageTitleAdmin = 'Tambah Fasilitas';
require_once __DIR__ . '/../includes/top.php';
?>
<h4 class="mb-4"><i class="bi bi-plus-lg"></i> Tambah Fasilitas</h4>
<?php if ($error): ?><div class="alert alert-danger"><?= htmlspecialchars($error) ?></div><?php endif; ?>
<div class="card card-admin p-4" style="max-width:600px;">
  <form method="POST" enctype="multipart/form-data">
    <div class="mb-3"><label class="form-label fw-semibold">Nama Fasilitas</label><input type="text" name="nama" class="form-control" required></div>
    <div class="mb-3"><label class="form-label fw-semibold">Gambar</label><input type="file" name="gambar" class="form-control" accept=".jpg,.jpeg,.png,.webp"></div>
    <div class="mb-3"><label class="form-label fw-semibold">Deskripsi</label><textarea name="deskripsi" rows="4" class="form-control"></textarea></div>
    <div class="mb-3"><label class="form-label fw-semibold">Urutan Tampil</label><input type="number" name="urutan" class="form-control" value="0"></div>
    <button type="submit" class="btn btn-primary text-white">Simpan</button>
    <a href="list.php" class="btn btn-outline-secondary">Batal</a>
  </form>
</div>
<?php require_once __DIR__ . '/../includes/bottom.php'; ?>
