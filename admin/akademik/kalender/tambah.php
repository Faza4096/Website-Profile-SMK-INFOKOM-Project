<?php
require_once __DIR__ . '/../../includes/auth.php';
require_once __DIR__ . '/../../../includes/koneksi.php';
require_once __DIR__ . '/../../../includes/kalender.php';

kalender_pendidikan_siapkan($pdo);
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tp       = trim($_POST['tahun_pelajaran'] ?? '2026/2027');
    $kegiatan = trim($_POST['kegiatan'] ?? '');
    $kategori = $_POST['kategori'] ?? 'Kegiatan';
    $mulai    = $_POST['tanggal_mulai'] ?? '';
    $selesai  = $_POST['tanggal_selesai'] ?? '';
    $keterangan = trim($_POST['keterangan'] ?? '');

    if ($kegiatan === '' || $mulai === '') {
        $error = 'Kegiatan dan tanggal mulai wajib diisi.';
    } elseif (!in_array($kategori, ['Kegiatan','Akademik','Ujian','Libur','PPDB'], true)) {
        $error = 'Kategori tidak dikenal.';
    } else {
        $stmt = $pdo->prepare("INSERT INTO kalender_pendidikan (tahun_pelajaran, kegiatan, kategori, tanggal_mulai, tanggal_selesai, keterangan)
                               VALUES (:tp, :kegiatan, :kategori, :mulai, :selesai, :keterangan)");
        $stmt->execute([
            ':tp'         => $tp !== '' ? $tp : '2026/2027',
            ':kegiatan'   => $kegiatan,
            ':kategori'   => $kategori,
            ':mulai'      => $mulai,
            ':selesai'    => ($selesai !== '' ? $selesai : null),
            ':keterangan' => $keterangan !== '' ? $keterangan : null,
        ]);
        header('Location: list.php?sukses=Kegiatan berhasil ditambahkan'); exit;
    }
}
$pageTitleAdmin = 'Tambah Kalender Pendidikan';
require_once __DIR__ . '/../../includes/top.php';
?>
<h4 class="mb-4"><i class="bi bi-plus-lg"></i> Tambah Kegiatan</h4>
<?php if ($error): ?><div class="alert alert-danger"><?= htmlspecialchars($error) ?></div><?php endif; ?>
<div class="card card-admin p-4" style="max-width:640px;">
  <form method="POST">
    <div class="row g-3">
      <div class="col-md-6">
        <label class="form-label fw-semibold">Tahun Pelajaran</label>
        <input type="text" name="tahun_pelajaran" class="form-control" value="<?= htmlspecialchars($_POST['tahun_pelajaran'] ?? '2026/2027') ?>" placeholder="2026/2027" required>
      </div>
      <div class="col-md-6">
        <label class="form-label fw-semibold">Kategori</label>
        <select name="kategori" class="form-select">
          <?php foreach (['Kegiatan','Akademik','Ujian','Libur','PPDB'] as $kat): ?>
            <option value="<?= $kat ?>" <?= ($_POST['kategori'] ?? 'Kegiatan') === $kat ? 'selected' : '' ?>><?= $kat ?></option>
          <?php endforeach; ?>
        </select>
      </div>
      <div class="col-12">
        <label class="form-label fw-semibold">Nama Kegiatan</label>
        <input type="text" name="kegiatan" class="form-control" value="<?= htmlspecialchars($_POST['kegiatan'] ?? '') ?>" placeholder="mis. Penilaian Tengah Semester Ganjil" required>
      </div>
      <div class="col-md-6">
        <label class="form-label fw-semibold">Tanggal Mulai</label>
        <input type="date" name="tanggal_mulai" class="form-control" value="<?= htmlspecialchars($_POST['tanggal_mulai'] ?? '') ?>" required>
      </div>
      <div class="col-md-6">
        <label class="form-label fw-semibold">Tanggal Selesai <span class="text-muted fw-normal">(opsional)</span></label>
        <input type="date" name="tanggal_selesai" class="form-control" value="<?= htmlspecialchars($_POST['tanggal_selesai'] ?? '') ?>">
      </div>
      <div class="col-12">
        <label class="form-label fw-semibold">Keterangan <span class="text-muted fw-normal">(opsional)</span></label>
        <textarea name="keterangan" rows="3" class="form-control" placeholder="Keterangan singkat kegiatan"><?= htmlspecialchars($_POST['keterangan'] ?? '') ?></textarea>
      </div>
    </div>
    <div class="mt-4">
      <button type="submit" class="btn btn-primary text-white">Simpan</button>
      <a href="list.php" class="btn btn-outline-secondary">Batal</a>
    </div>
  </form>
</div>
<?php require_once __DIR__ . '/../../includes/bottom.php'; ?>
