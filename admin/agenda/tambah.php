<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../../includes/koneksi.php';
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $judul = trim($_POST['judul'] ?? ''); $isi = trim($_POST['isi'] ?? ''); $tanggal = $_POST['tanggal'] ?? '';
    if ($judul === '' || $tanggal === '') { $error = 'Judul dan tanggal wajib diisi.'; }
    else {
        $stmt = $pdo->prepare("INSERT INTO agenda (judul, isi, tanggal) VALUES (:judul,:isi,:tanggal)");
        $stmt->execute([':judul'=>$judul, ':isi'=>$isi, ':tanggal'=>$tanggal]);
        header('Location: list.php?sukses=Data berhasil ditambahkan'); exit;
    }
}
$pageTitleAdmin = 'Tambah Agenda';
require_once __DIR__ . '/../includes/top.php';
?>
<h4 class="mb-4"><i class="bi bi-plus-lg"></i> Tambah Agenda</h4>
<?php if ($error): ?><div class="alert alert-danger"><?= htmlspecialchars($error) ?></div><?php endif; ?>
<div class="card card-admin p-4" style="max-width:600px;">
  <form method="POST">
    <div class="mb-3"><label class="form-label fw-semibold">Judul</label><input type="text" name="judul" class="form-control" required></div>
    <div class="mb-3"><label class="form-label fw-semibold">Tanggal</label><input type="date" name="tanggal" class="form-control" required></div>
    <div class="mb-3"><label class="form-label fw-semibold">Isi</label><textarea name="isi" rows="5" class="form-control"></textarea></div>
    <button type="submit" class="btn btn-primary text-white">Simpan</button>
    <a href="list.php" class="btn btn-outline-secondary">Batal</a>
  </form>
</div>
<?php require_once __DIR__ . '/../includes/bottom.php'; ?>
