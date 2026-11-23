<?php
require_once __DIR__ . '/../../includes/auth.php';
require_once __DIR__ . '/../../../includes/koneksi.php';
require_once __DIR__ . '/../../../includes/migrasi-lowongan.php';

lowongan_migrasi_pkl($pdo);
// Daftar tipe pekerjaan — termasuk PKL (sesuai filter halaman lowongan publik)
$daftarTipe = ['Full-time', 'Part-time', 'Kontrak', 'Magang', 'PKL'];

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $posisi     = trim($_POST['posisi'] ?? '');
    $perusahaan = trim($_POST['perusahaan'] ?? '');

    if ($posisi === '' || $perusahaan === '') {
        $error = 'Posisi dan perusahaan wajib diisi.';
    } else {
        $stmt = $pdo->prepare(
            "INSERT INTO bkk_lowongan
             (posisi, perusahaan, lokasi, tipe_pekerjaan, gaji, deskripsi, kualifikasi, kuota, batas_daftar, kontak, status)
             VALUES
             (:posisi, :perusahaan, :lokasi, :tipe, :gaji, :deskripsi, :kualifikasi, :kuota, :batas, :kontak, :status)"
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
        ]);
        header('Location: list.php?sukses=Lowongan berhasil ditambahkan');
        exit;
    }
}

$pageTitleAdmin = 'Tambah Lowongan';
require_once __DIR__ . '/../../includes/top.php';
?>
<h4 class="mb-4"><i class="bi bi-plus-lg"></i> Tambah Lowongan</h4>
<?php if ($error): ?><div class="alert alert-danger"><?= htmlspecialchars($error) ?></div><?php endif; ?>

<div class="card card-admin p-4" style="max-width:700px;">
  <form method="POST">
    <div class="row g-3">
      <div class="col-md-6">
        <label class="form-label fw-semibold">Posisi</label>
        <input type="text" name="posisi" class="form-control" required value="<?= htmlspecialchars($_POST['posisi'] ?? '') ?>">
      </div>
      <div class="col-md-6">
        <label class="form-label fw-semibold">Perusahaan</label>
        <input type="text" name="perusahaan" class="form-control" required value="<?= htmlspecialchars($_POST['perusahaan'] ?? '') ?>">
      </div>
      <div class="col-md-6">
        <label class="form-label fw-semibold">Lokasi</label>
        <input type="text" name="lokasi" class="form-control" placeholder="Contoh: Kota Bogor" value="<?= htmlspecialchars($_POST['lokasi'] ?? '') ?>">
      </div>
      <div class="col-md-6">
        <label class="form-label fw-semibold">Tipe Pekerjaan</label>
        <select name="tipe_pekerjaan" class="form-select">
          <?php foreach ($daftarTipe as $t): ?>
            <option value="<?= $t ?>" <?= ($_POST['tipe_pekerjaan'] ?? '') === $t ? 'selected' : '' ?>><?= $t ?></option>
          <?php endforeach; ?>
        </select>
      </div>
      <div class="col-md-6">
        <label class="form-label fw-semibold">Rentang Gaji (opsional)</label>
        <input type="text" name="gaji" class="form-control" placeholder="Contoh: Rp 4.000.000 - Rp 5.000.000" value="<?= htmlspecialchars($_POST['gaji'] ?? '') ?>">
      </div>
      <div class="col-md-6">
        <label class="form-label fw-semibold">Kuota (opsional)</label>
        <input type="number" name="kuota" class="form-control" min="1" value="<?= htmlspecialchars($_POST['kuota'] ?? '') ?>">
      </div>
      <div class="col-md-6">
        <label class="form-label fw-semibold">Batas Daftar (opsional)</label>
        <input type="date" name="batas_daftar" class="form-control" value="<?= htmlspecialchars($_POST['batas_daftar'] ?? '') ?>">
      </div>
      <div class="col-md-6">
        <label class="form-label fw-semibold">Kontak (HP/email perusahaan)</label>
        <input type="text" name="kontak" class="form-control" value="<?= htmlspecialchars($_POST['kontak'] ?? '') ?>">
      </div>
      <div class="col-md-6">
        <label class="form-label fw-semibold">Status</label>
        <select name="status" class="form-select">
          <option value="buka" <?= ($_POST['status'] ?? '') === 'buka' ? 'selected' : '' ?>>Dibuka</option>
          <option value="tutup" <?= ($_POST['status'] ?? '') === 'tutup' ? 'selected' : '' ?>>Ditutup</option>
        </select>
      </div>
      <div class="col-12">
        <label class="form-label fw-semibold">Deskripsi Pekerjaan</label>
        <textarea name="deskripsi" rows="4" class="form-control"><?= htmlspecialchars($_POST['deskripsi'] ?? '') ?></textarea>
      </div>
      <div class="col-12">
        <label class="form-label fw-semibold">Kualifikasi</label>
        <textarea name="kualifikasi" rows="3" class="form-control"><?= htmlspecialchars($_POST['kualifikasi'] ?? '') ?></textarea>
      </div>
      <div class="col-12">
        <button type="submit" class="btn btn-primary text-white">Simpan</button>
        <a href="list.php" class="btn btn-outline-secondary">Batal</a>
      </div>
    </div>
  </form>
</div>
<?php require_once __DIR__ . '/../../includes/bottom.php'; ?>
