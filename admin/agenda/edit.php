<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../../includes/koneksi.php';
$id = (int)($_GET['id'] ?? 0);
$stmt = $pdo->prepare("SELECT * FROM agenda WHERE id=?"); $stmt->execute([$id]); $data = $stmt->fetch();
if (!$data) { header('Location: list.php'); exit; }
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $judul = trim($_POST['judul'] ?? ''); $isi = trim($_POST['isi'] ?? ''); $tanggal = $_POST['tanggal'] ?? '';
    if ($judul === '' || $tanggal === '') { $error = 'Judul dan tanggal wajib diisi.'; }
    else {
        $stmt = $pdo->prepare("UPDATE agenda SET judul=:judul, isi=:isi, tanggal=:tanggal WHERE id=:id");
        $stmt->execute([':judul'=>$judul, ':isi'=>$isi, ':tanggal'=>$tanggal, ':id'=>$id]);
        header('Location: list.php?sukses=Data berhasil diperbarui'); exit;
    }
}
$pageTitleAdmin = 'Edit Agenda';
require_once __DIR__ . '/../includes/top.php';
?>
<h4 class="mb-4"><i class="bi bi-pencil-square"></i> Edit Agenda</h4>
<?php if ($error): ?><div class="alert alert-danger"><?= htmlspecialchars($error) ?></div><?php endif; ?>
<div class="card card-admin p-4" style="max-width:600px;">
  <form method="POST">
    <div class="mb-3"><label class="form-label fw-semibold">Judul</label><input type="text" name="judul" class="form-control" required value="<?= htmlspecialchars($data['judul']) ?>"></div>
    <div class="mb-3"><label class="form-label fw-semibold">Tanggal</label><input type="date" name="tanggal" class="form-control" required value="<?= htmlspecialchars($data['tanggal']) ?>"></div>
    <div class="mb-3"><label class="form-label fw-semibold">Isi</label><textarea name="isi" rows="5" class="form-control"><?= htmlspecialchars($data['isi']) ?></textarea></div>
    <button type="submit" class="btn btn-primary text-white">Update</button>
    <a href="list.php" class="btn btn-outline-secondary">Batal</a>
  </form>
</div>
<?php require_once __DIR__ . '/../includes/bottom.php'; ?>
