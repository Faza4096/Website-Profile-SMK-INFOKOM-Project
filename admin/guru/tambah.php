<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../../includes/koneksi.php';
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = trim($_POST['nama'] ?? ''); $nip = trim($_POST['nip'] ?? '');
    $jabatan = trim($_POST['jabatan'] ?? ''); $bidang = trim($_POST['bidang'] ?? '');
    $urutan = (int)($_POST['urutan'] ?? 0);
    if ($nama === '' || $jabatan === '') { $error = 'Nama dan jabatan wajib diisi.'; }
    else {
        $namaFile = null;
        if (!empty($_FILES['foto']['name'])) {
            $ext = strtolower(pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION));
            if (in_array($ext, ['jpg','jpeg','png','webp'])) {
                $namaFile = 'guru_' . time() . '.' . $ext;
                move_uploaded_file($_FILES['foto']['tmp_name'], __DIR__ . '/../../uploads/guru/' . $namaFile);
            } else { $error = 'Format foto tidak didukung.'; }
        }
        if ($error === '') {
            $stmt = $pdo->prepare("INSERT INTO guru_staff (nama, nip, jabatan, bidang, foto, urutan) VALUES (:nama,:nip,:jabatan,:bidang,:foto,:urutan)");
            $stmt->execute([':nama'=>$nama, ':nip'=>$nip ?: null, ':jabatan'=>$jabatan, ':bidang'=>$bidang ?: null, ':foto'=>$namaFile, ':urutan'=>$urutan]);
            header('Location: list.php?sukses=Data berhasil ditambahkan'); exit;
        }
    }
}
$pageTitleAdmin = 'Tambah Guru & Staff';
require_once __DIR__ . '/../includes/top.php';
?>
<h4 class="mb-4"><i class="bi bi-plus-lg"></i> Tambah Guru &amp; Staff</h4>
<?php if ($error): ?><div class="alert alert-danger"><?= htmlspecialchars($error) ?></div><?php endif; ?>
<div class="card card-admin p-4" style="max-width:600px;">
  <form method="POST" enctype="multipart/form-data">
    <div class="mb-3"><label class="form-label fw-semibold">Nama Lengkap</label><input type="text" name="nama" class="form-control" required></div>
    <div class="mb-3"><label class="form-label fw-semibold">NIP (opsional)</label><input type="text" name="nip" class="form-control"></div>
    <div class="mb-3"><label class="form-label fw-semibold">Jabatan</label><input type="text" name="jabatan" class="form-control" placeholder="Contoh: Guru Mapel / Kepala Lab" required></div>
    <div class="mb-3"><label class="form-label fw-semibold">Bidang / Mata Pelajaran</label><input type="text" name="bidang" class="form-control"></div>
    <div class="mb-3"><label class="form-label fw-semibold">Foto</label><input type="file" name="foto" class="form-control" accept=".jpg,.jpeg,.png,.webp"></div>
    <div class="mb-3"><label class="form-label fw-semibold">Urutan Tampil</label><input type="number" name="urutan" class="form-control" value="0"></div>
    <button type="submit" class="btn btn-primary text-white">Simpan</button>
    <a href="list.php" class="btn btn-outline-secondary">Batal</a>
  </form>
</div>
<?php require_once __DIR__ . '/../includes/bottom.php'; ?>
