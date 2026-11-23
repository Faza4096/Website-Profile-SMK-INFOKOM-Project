<?php
// Modul "Kelola Data Siswa" dihapus dari panel admin — akses langsung dialihkan ke dashboard.
header('Location: ../index.php');
exit;

require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../../includes/koneksi.php';
$id = (int)($_GET['id'] ?? 0);
$stmt = $pdo->prepare("SELECT * FROM siswa WHERE id = ?");
$stmt->execute([$id]);
$data = $stmt->fetch();
if (!$data) {
    header('Location: list.php'); exit;
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nis    = trim($_POST['nis'] ?? '');
    $nama   = trim($_POST['nama'] ?? '');
    $jk     = $_POST['jenis_kelamin'] ?? 'Laki-laki';
    $kelas  = trim($_POST['kelas'] ?? '');
    $jurusan = (int)($_POST['jurusan_id'] ?? 0) ?: null;
    $status = $_POST['status'] ?? 'aktif';

    if ($nis === '' || $nama === '' || $kelas === '') {
        $error = 'NIS, nama, dan kelas wajib diisi.';
    } elseif (!in_array($jk, ['Laki-laki', 'Perempuan'], true)) {
        $error = 'Jenis kelamin tidak valid.';
    } elseif (!in_array($status, ['aktif', 'lulus', 'nonaktif'], true)) {
        $error = 'Status tidak valid.';
    } else {
        $cek = $pdo->prepare("SELECT COUNT(*) FROM siswa WHERE nis = ? AND id <> ?");
        $cek->execute([$nis, $id]);
        if ((int)$cek->fetchColumn() > 0) {
            $error = 'NIS tersebut sudah dipakai siswa lain.';
        } else {
            $stmt = $pdo->prepare("UPDATE siswa SET nis=:nis, nama=:nama, jenis_kelamin=:jk, kelas=:kelas, jurusan_id=:jurusan, status=:status WHERE id=:id");
            $stmt->execute([':nis'=>$nis, ':nama'=>$nama, ':jk'=>$jk, ':kelas'=>$kelas, ':jurusan'=>$jurusan, ':status'=>$status, ':id'=>$id]);
            header('Location: list.php?sukses=Data siswa berhasil diperbarui'); exit;
        }
    }
}
$jurusanList = $pdo->query("SELECT id, nama_jurusan, singkatan FROM jurusan ORDER BY id ASC")->fetchAll();
$pageTitleAdmin = 'Edit Data Siswa';
require_once __DIR__ . '/../includes/top.php';
?>
<h4 class="mb-4"><i class="bi bi-pencil-square"></i> Edit Data Siswa</h4>
<?php if ($error): ?><div class="alert alert-danger"><?= htmlspecialchars($error) ?></div><?php endif; ?>
<div class="card card-admin p-4" style="max-width:600px;">
  <form method="POST">
    <div class="mb-3"><label class="form-label fw-semibold">NIS</label><input type="text" name="nis" class="form-control" required value="<?= htmlspecialchars($data['nis']) ?>"></div>
    <div class="mb-3"><label class="form-label fw-semibold">Nama Lengkap</label><input type="text" name="nama" class="form-control" required value="<?= htmlspecialchars($data['nama']) ?>"></div>
    <div class="mb-3"><label class="form-label fw-semibold">Jenis Kelamin</label>
      <select name="jenis_kelamin" class="form-select">
        <option value="Laki-laki" <?= $data['jenis_kelamin'] === 'Laki-laki' ? 'selected' : '' ?>>Laki-laki</option>
        <option value="Perempuan" <?= $data['jenis_kelamin'] === 'Perempuan' ? 'selected' : '' ?>>Perempuan</option>
      </select>
    </div>
    <div class="mb-3"><label class="form-label fw-semibold">Kelas</label><input type="text" name="kelas" class="form-control" required value="<?= htmlspecialchars($data['kelas']) ?>"></div>
    <div class="mb-3"><label class="form-label fw-semibold">Jurusan</label>
      <select name="jurusan_id" class="form-select">
        <option value="">- Pilih Jurusan -</option>
        <?php foreach ($jurusanList as $j): ?>
        <option value="<?= (int)$j['id'] ?>" <?= (int)$data['jurusan_id'] === (int)$j['id'] ? 'selected' : '' ?>><?= htmlspecialchars(($j['singkatan'] ? $j['singkatan'] . ' — ' : '') . $j['nama_jurusan']) ?></option>
        <?php endforeach; ?>
      </select>
    </div>
    <div class="mb-3"><label class="form-label fw-semibold">Status</label>
      <select name="status" class="form-select">
        <option value="aktif" <?= $data['status'] === 'aktif' ? 'selected' : '' ?>>Aktif</option>
        <option value="lulus" <?= $data['status'] === 'lulus' ? 'selected' : '' ?>>Lulus</option>
        <option value="nonaktif" <?= $data['status'] === 'nonaktif' ? 'selected' : '' ?>>Nonaktif</option>
      </select>
    </div>
    <button type="submit" class="btn btn-primary text-white">Simpan Perubahan</button>
    <a href="list.php" class="btn btn-outline-secondary">Batal</a>
  </form>
</div>
<?php require_once __DIR__ . '/../includes/bottom.php'; ?>
