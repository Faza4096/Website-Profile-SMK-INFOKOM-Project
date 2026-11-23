<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../../includes/koneksi.php';
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $jabatan = trim($_POST['jabatan'] ?? ''); $nama = trim($_POST['nama'] ?? ''); $urutan = (int)($_POST['urutan'] ?? 0);
    if ($jabatan === '' || $nama === '') { $error = 'Jabatan dan nama wajib diisi.'; }
    else {
        $namaFile = null;
        if (!empty($_FILES['foto']['name'])) {
            $ext = strtolower(pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION));
            if (in_array($ext, ['jpg','jpeg','png','webp'])) {
                $namaFile = 'struktur_' . time() . '.' . $ext;
                move_uploaded_file($_FILES['foto']['tmp_name'], __DIR__ . '/../../uploads/struktur/' . $namaFile);
            } else { $error = 'Format foto tidak didukung.'; }
        }
        if ($error === '') {
            $stmt = $pdo->prepare("INSERT INTO struktur_organisasi (jabatan, nama, foto, urutan) VALUES (:jabatan,:nama,:foto,:urutan)");
            $stmt->execute([':jabatan'=>$jabatan, ':nama'=>$nama, ':foto'=>$namaFile, ':urutan'=>$urutan]);
            header('Location: list.php?sukses=Data berhasil ditambahkan'); exit;
        }
    }
}
$pageTitleAdmin = 'Tambah Struktur Organisasi';
require_once __DIR__ . '/../includes/top.php';
?>
<h4 class="mb-4"><i class="bi bi-plus-lg"></i> Tambah Struktur Organisasi</h4>
<?php if ($error): ?><div class="alert alert-danger"><?= htmlspecialchars($error) ?></div><?php endif; ?>
<div class="card card-admin p-4" style="max-width:600px;">
  <form method="POST" enctype="multipart/form-data">
    <div class="mb-3"><label class="form-label fw-semibold">Jabatan</label><input type="text" name="jabatan" class="form-control" required placeholder="Contoh: Kepala Sekolah"></div>
    <div class="mb-3"><label class="form-label fw-semibold">Nama</label><input type="text" name="nama" class="form-control" required></div>
    <div class="mb-3"><label class="form-label fw-semibold">Foto</label><input type="file" name="foto" class="form-control" accept=".jpg,.jpeg,.png,.webp"></div>
    <div class="mb-3"><label class="form-label fw-semibold">Urutan Tampil</label><input type="number" name="urutan" class="form-control" value="0"></div>
    <button type="submit" class="btn btn-primary text-white">Simpan</button>
    <a href="list.php" class="btn btn-outline-secondary">Batal</a>
  </form>
</div>
<?php require_once __DIR__ . '/../includes/bottom.php'; ?>
