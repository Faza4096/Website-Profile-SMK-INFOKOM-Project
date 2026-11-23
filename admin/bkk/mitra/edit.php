<?php
require_once __DIR__ . '/../../includes/auth.php';
require_once __DIR__ . '/../../../includes/koneksi.php';
require_once __DIR__ . '/../../../includes/migrasi-mitra.php';

mitra_migrasi_jenis($pdo);
$id = (int)($_GET['id'] ?? 0);
$stmt = $pdo->prepare("SELECT * FROM bkk_mitra WHERE id = ? LIMIT 1");
$stmt->execute([$id]);
$data = $stmt->fetch();

if (!$data) {
    header('Location: list.php');
    exit;
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = trim($_POST['nama_perusahaan'] ?? '');
    $jenisMit = in_array($_POST['jenis'] ?? '', ['industri', 'universitas'], true) ? $_POST['jenis'] : 'industri';

    if ($nama === '') {
        $error = 'Nama perusahaan wajib diisi.';
    } else {
        $namaFile = $data['logo'];
        if (!empty($_FILES['logo']['name'])) {
            $ext = strtolower(pathinfo($_FILES['logo']['name'], PATHINFO_EXTENSION));
            if (in_array($ext, ['jpg', 'jpeg', 'png', 'webp'])) {
                if ($data['logo']) {
                    $lama = __DIR__ . '/../../../uploads/bkk/' . $data['logo'];
                    if (file_exists($lama)) unlink($lama);
                }
                $namaFile = 'bkk_mitra_' . time() . '.' . $ext;
                move_uploaded_file($_FILES['logo']['tmp_name'], __DIR__ . '/../../../uploads/bkk/' . $namaFile);
            } else {
                $error = 'Format logo tidak didukung.';
            }
        }

        if ($error === '') {
            $stmt = $pdo->prepare(
                "UPDATE bkk_mitra SET nama_perusahaan = :nama, jenis = :jenis, bidang = :bidang, alamat = :alamat,
                 logo = :logo, keterangan = :keterangan, urutan = :urutan WHERE id = :id"
            );
            $stmt->execute([
                ':nama'       => $nama,
                ':jenis'      => $jenisMit,
                ':bidang'     => trim($_POST['bidang'] ?? '') ?: null,
                ':alamat'     => trim($_POST['alamat'] ?? '') ?: null,
                ':logo'       => $namaFile,
                ':keterangan' => trim($_POST['keterangan'] ?? '') ?: null,
                ':urutan'     => (int)($_POST['urutan'] ?? 0),
                ':id'         => $id,
            ]);
            header('Location: list.php?sukses=Mitra berhasil diperbarui');
            exit;
        }
    }
}

$pageTitleAdmin = 'Edit Mitra BKK';
require_once __DIR__ . '/../../includes/top.php';
?>
<h4 class="mb-4"><i class="bi bi-pencil-square"></i> Edit Perusahaan Mitra</h4>
<?php if ($error): ?><div class="alert alert-danger"><?= htmlspecialchars($error) ?></div><?php endif; ?>

<div class="card card-admin p-4" style="max-width:600px;">
  <form method="POST" enctype="multipart/form-data">
    <div class="mb-3">
      <label class="form-label fw-semibold">Nama Perusahaan / Institusi</label>
      <input type="text" name="nama_perusahaan" class="form-control" required value="<?= htmlspecialchars($data['nama_perusahaan']) ?>">
    </div>
    <div class="mb-3">
      <label class="form-label fw-semibold">Jenis Mitra</label>
      <select name="jenis" class="form-select">
        <option value="industri" <?= ($data['jenis'] ?? 'industri') === 'industri' ? 'selected' : '' ?>>Industri (perusahaan)</option>
        <option value="universitas" <?= ($data['jenis'] ?? '') === 'universitas' ? 'selected' : '' ?>>Universitas (perguruan tinggi)</option>
      </select>
    </div>
    <div class="mb-3">
      <label class="form-label fw-semibold">Bidang Usaha</label>
      <input type="text" name="bidang" class="form-control" value="<?= htmlspecialchars($data['bidang'] ?? '') ?>">
    </div>
    <div class="mb-3">
      <label class="form-label fw-semibold">Alamat</label>
      <input type="text" name="alamat" class="form-control" value="<?= htmlspecialchars($data['alamat'] ?? '') ?>">
    </div>
    <?php if ($data['logo']): ?>
    <div class="mb-3">
      <label class="form-label fw-semibold">Logo Saat Ini</label><br>
      <img src="../../../uploads/bkk/<?= htmlspecialchars($data['logo']) ?>" width="72" class="rounded mb-2" onerror="this.src='https://placehold.co/72x72'">
    </div>
    <?php endif; ?>
    <div class="mb-3">
      <label class="form-label fw-semibold">Ganti Logo (opsional)</label>
      <input type="file" name="logo" class="form-control" accept=".jpg,.jpeg,.png,.webp">
    </div>
    <div class="mb-3">
      <label class="form-label fw-semibold">Keterangan Kerja Sama</label>
      <textarea name="keterangan" rows="3" class="form-control"><?= htmlspecialchars($data['keterangan'] ?? '') ?></textarea>
    </div>
    <div class="mb-3">
      <label class="form-label fw-semibold">Urutan Tampil</label>
      <input type="number" name="urutan" class="form-control" value="<?= (int)$data['urutan'] ?>">
    </div>
    <button type="submit" class="btn btn-primary text-white">Update</button>
    <a href="list.php" class="btn btn-outline-secondary">Batal</a>
  </form>
</div>
<?php require_once __DIR__ . '/../../includes/bottom.php'; ?>
