<?php
require_once __DIR__ . '/../../includes/auth.php';
require_once __DIR__ . '/../../../includes/koneksi.php';
require_once __DIR__ . '/../../../includes/migrasi-lowongan.php';

lowongan_migrasi_pkl($pdo);
// Daftar tipe pekerjaan — termasuk PKL (sesuai filter halaman lowongan publik)
$daftarTipe = ['Full-time', 'Part-time', 'Kontrak', 'Magang', 'PKL'];

$id = (int)($_GET['id'] ?? 0);
$stmt = $pdo->prepare("SELECT * FROM bkk_lowongan WHERE id = ? LIMIT 1");
$stmt->execute([$id]);
$data = $stmt->fetch();

if (!$data) {
    header('Location: list.php');
    exit;
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $posisi     = trim($_POST['posisi'] ?? '');
    $perusahaan = trim($_POST['perusahaan'] ?? '');

    if ($posisi === '' || $perusahaan === '') {
        $error = 'Posisi dan perusahaan wajib diisi.';
    } else {
        $stmt = $pdo->prepare(
            "UPDATE bkk_lowongan SET
             posisi = :posisi, perusahaan = :perusahaan, lokasi = :lokasi, tipe_pekerjaan = :tipe,
             gaji = :gaji, deskripsi = :deskripsi, kualifikasi = :kualifikasi, kuota = :kuota,
             batas_daftar = :batas, kontak = :kontak, status = :status
             WHERE id = :id"
        );
        $stmt->execute([
            ':posisi'      => $posisi,
            ':perusahaan'  => $perusahaan,
            ':lokasi'      => trim($_POST['lokasi'] ?? '') ?: null,
            ':tipe'        => in_array($_POST['tipe_pekerjaan'] ?? '', $daftarTipe, true) ? $_POST['tipe_pekerjaan'] : 'Full-time',
            ':gaji'        => trim($_POST['gaji'] ?? '') ?: null,
            ':deskripsi'   => trim($_POST['deskripsi'] ?? '') ?: null,
            ':kualifikasi' => trim($_POST['kualifikasi'] ?? '') ?: null,
            ':kuota'       => ($_POST['kuota'] ?? '') !== '' ? (int)$_POST['kuota'] : null,
            ':batas'       => ($_POST['batas_daftar'] ?? '') !== '' ? $_POST['batas_daftar'] : null,
            ':kontak'      => trim($_POST['kontak'] ?? '') ?: null,
            ':status'      => ($_POST['status'] ?? 'buka') === 'tutup' ? 'tutup' : 'buka',
            ':id'          => $id,
        ]);
        header('Location: list.php?sukses=Lowongan berhasil diperbarui');
        exit;
    }
}

$pageTitleAdmin = 'Edit Lowongan';
require_once __DIR__ . '/../../includes/top.php';
?>
<h4 class="mb-4"><i class="bi bi-pencil-square"></i> Edit Lowongan</h4>
<?php if ($error): ?><div class="alert alert-danger"><?= htmlspecialchars($error) ?></div><?php endif; ?>

<div class="card card-admin p-4" style="max-width:700px;">
  <form method="POST">
    <div class="row g-3">
      <div class="col-md-6">
        <label class="form-label fw-semibold">Posisi</label>
        <input type="text" name="posisi" class="form-control" required value="<?= htmlspecialchars($data['posisi']) ?>">
      </div>
      <div class="col-md-6">
        <label class="form-label fw-semibold">Perusahaan</label>
        <input type="text" name="perusahaan" class="form-control" required value="<?= htmlspecialchars($data['perusahaan']) ?>">
      </div>
      <div class="col-md-6">
        <label class="form-label fw-semibold">Lokasi</label>
        <input type="text" name="lokasi" class="form-control" value="<?= htmlspecialchars($data['lokasi'] ?? '') ?>">
      </div>
      <div class="col-md-6">
        <label class="form-label fw-semibold">Tipe Pekerjaan</label>
        <select name="tipe_pekerjaan" class="form-select">
          <?php foreach ($daftarTipe as $t): ?>
            <option value="<?= $t ?>" <?= $data['tipe_pekerjaan'] === $t ? 'selected' : '' ?>><?= $t ?></option>
          <?php endforeach; ?>
        </select>
      </div>
      <div class="col-md-6">
        <label class="form-label fw-semibold">Rentang Gaji</label>
        <input type="text" name="gaji" class="form-control" value="<?= htmlspecialchars($data['gaji'] ?? '') ?>">
      </div>
      <div class="col-md-6">
        <label class="form-label fw-semibold">Kuota</label>
        <input type="number" name="kuota" class="form-control" min="1" value="<?= $data['kuota'] !== null ? (int)$data['kuota'] : '' ?>">
      </div>
      <div class="col-md-6">
        <label class="form-label fw-semibold">Batas Daftar</label>
        <input type="date" name="batas_daftar" class="form-control" value="<?= htmlspecialchars($data['batas_daftar'] ?? '') ?>">
      </div>
      <div class="col-md-6">
        <label class="form-label fw-semibold">Kontak</label>
        <input type="text" name="kontak" class="form-control" value="<?= htmlspecialchars($data['kontak'] ?? '') ?>">
      </div>
      <div class="col-md-6">
        <label class="form-label fw-semibold">Status</label>
        <select name="status" class="form-select">
          <option value="buka" <?= $data['status'] === 'buka' ? 'selected' : '' ?>>Dibuka</option>
          <option value="tutup" <?= $data['status'] === 'tutup' ? 'selected' : '' ?>>Ditutup</option>
        </select>
      </div>
      <div class="col-12">
        <label class="form-label fw-semibold">Deskripsi Pekerjaan</label>
        <textarea name="deskripsi" rows="4" class="form-control"><?= htmlspecialchars($data['deskripsi'] ?? '') ?></textarea>
      </div>
      <div class="col-12">
        <label class="form-label fw-semibold">Kualifikasi</label>
        <textarea name="kualifikasi" rows="3" class="form-control"><?= htmlspecialchars($data['kualifikasi'] ?? '') ?></textarea>
      </div>
      <div class="col-12">
        <button type="submit" class="btn btn-primary text-white">Update</button>
        <a href="list.php" class="btn btn-outline-secondary">Batal</a>
      </div>
    </div>
  </form>
</div>
<?php require_once __DIR__ . '/../../includes/bottom.php'; ?>
