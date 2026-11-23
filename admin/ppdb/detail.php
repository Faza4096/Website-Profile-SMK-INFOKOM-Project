<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../../includes/koneksi.php';
require_once __DIR__ . '/../../includes/migrasi-ppdb.php';

$id = (int)($_GET['id'] ?? 0);
$stmt = $pdo->prepare("SELECT p.*, j.nama_jurusan FROM pendaftaran_ppdb p LEFT JOIN jurusan j ON p.jurusan_pilihan = j.id WHERE p.id = ?");
$stmt->execute([$id]);
$data = $stmt->fetch();
if (!$data) { header('Location: list.php'); exit; }

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $status = $_POST['status'] ?? 'menunggu';
    if (in_array($status, ['menunggu','diverifikasi','ditolak','diterima'], true)) {
        // Alasan disimpan apa adanya; ditampilkan ke peserta hanya saat status ditolak
        $alasan = trim($_POST['alasan'] ?? '');
        $stmt = $pdo->prepare("UPDATE pendaftaran_ppdb SET status = ?, alasan = ? WHERE id = ?");
        $stmt->execute([$status, $alasan, $id]);
        header('Location: list.php?sukses=Status pendaftar berhasil diperbarui');
        exit;
    }
}

$pageTitleAdmin = 'Detail Pendaftar PPDB';
require_once __DIR__ . '/../includes/top.php';
?>
<h4 class="mb-4"><i class="bi bi-person-lines-fill"></i> Detail Pendaftar</h4>

<div class="row g-4">
  <div class="col-lg-8">
    <div class="card card-admin p-4">
      <table class="table table-borderless mb-0">
        <tr><th width="220">Nomor Pendaftaran</th><td><code><?= htmlspecialchars($data['nomor_pendaftaran']) ?></code></td></tr>
        <tr><th>Nama Lengkap</th><td><?= htmlspecialchars($data['nama_lengkap']) ?></td></tr>
        <tr><th>NISN</th><td><?= htmlspecialchars($data['nisn'] ?: '-') ?></td></tr>
        <tr><th>Jenis Kelamin</th><td><?= htmlspecialchars($data['jenis_kelamin']) ?></td></tr>
        <tr><th>Tempat, Tanggal Lahir</th><td><?= htmlspecialchars($data['tempat_lahir'] ?: '-') ?>, <?= $data['tanggal_lahir'] ? date('d M Y', strtotime($data['tanggal_lahir'])) : '-' ?></td></tr>
        <tr><th>Asal Sekolah</th><td><?= htmlspecialchars($data['asal_sekolah']) ?></td></tr>
        <tr><th>Jurusan Pilihan</th><td><?= htmlspecialchars($data['nama_jurusan'] ?? '-') ?></td></tr>
        <tr><th>Nama Orang Tua/Wali</th><td><?= htmlspecialchars($data['nama_orangtua'] ?: '-') ?></td></tr>
        <tr><th>No. HP</th><td><?= htmlspecialchars($data['no_hp']) ?></td></tr>
        <tr><th>Email</th><td><?= htmlspecialchars($data['email'] ?: '-') ?></td></tr>
        <tr><th>Alamat</th><td><?= nl2br(htmlspecialchars($data['alamat'] ?: '-')) ?></td></tr>
        <tr><th>Berkas Dokumen</th>
          <td>
            <?php if ($data['berkas_dokumen']): ?>
              <a href="../../uploads/ppdb/<?= htmlspecialchars($data['berkas_dokumen']) ?>" target="_blank" class="btn btn-sm btn-outline-primary"><i class="bi bi-file-earmark-arrow-down"></i> Lihat Berkas</a>
            <?php else: ?>
              <span class="text-muted">Tidak ada berkas diupload</span>
            <?php endif; ?>
          </td>
        </tr>
        <tr><th>Alasan (jika ditolak)</th><td><?= ($data['alasan'] ?? '') !== '' ? nl2br(htmlspecialchars($data['alasan'])) : '<span class="text-muted">-</span>' ?></td></tr>
        <tr><th>Tanggal Daftar</th><td><?= date('d M Y H:i', strtotime($data['created_at'])) ?></td></tr>
      </table>
    </div>
  </div>

  <div class="col-lg-4">
    <div class="card card-admin p-4">
      <h6 class="mb-3">Status Pendaftaran</h6>
      <form method="POST">
        <label class="form-label small fw-semibold mb-1">Status</label>
        <select name="status" class="form-select mb-3">
          <?php foreach (['menunggu'=>'Menunggu','diverifikasi'=>'Diverifikasi','diterima'=>'Diterima','ditolak'=>'Ditolak'] as $key => $label): ?>
            <option value="<?= $key ?>" <?= $data['status']===$key?'selected':'' ?>><?= $label ?></option>
          <?php endforeach; ?>
        </select>
        <label class="form-label small fw-semibold mb-1">Alasan <span class="text-muted fw-normal">(tampil ke peserta jika status Ditolak)</span></label>
        <textarea name="alasan" rows="3" class="form-control mb-3" placeholder="Contoh: Berkas ijazah tidak terbaca / kuota jurusan penuh"><?= htmlspecialchars($data['alasan'] ?? '') ?></textarea>
        <button type="submit" class="btn btn-primary text-white w-100">Simpan Status</button>
      </form>
      <a href="list.php" class="btn btn-outline-secondary w-100 mt-2">Kembali</a>
    </div>
  </div>
</div>
<?php require_once __DIR__ . '/../includes/bottom.php'; ?>
