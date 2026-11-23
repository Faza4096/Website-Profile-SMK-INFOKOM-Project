<?php
require_once __DIR__ . '/../../includes/auth.php';
require_once __DIR__ . '/../../../includes/koneksi.php';

$id = (int)($_GET['id'] ?? 0);
$stmt = $pdo->prepare(
    "SELECT p.*, l.posisi, l.perusahaan, l.lokasi, l.gaji
     FROM bkk_pelamar p
     LEFT JOIN bkk_lowongan l ON p.lowongan_id = l.id
     WHERE p.id = ? LIMIT 1"
);
$stmt->execute([$id]);
$d = $stmt->fetch();

if (!$d) {
    header('Location: list.php');
    exit;
}

// Proses ubah status
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $status = $_POST['status'] ?? '';
    if (in_array($status, ['baru', 'diproses', 'lolos', 'tidak_lolos'], true)) {
        $upd = $pdo->prepare("UPDATE bkk_pelamar SET status = ? WHERE id = ?");
        $upd->execute([$status, $id]);
        header('Location: detail.php?id=' . $id . '&sukses=Status lamaran diperbarui');
        exit;
    }
}

$pageTitleAdmin = 'Detail Pelamar';
require_once __DIR__ . '/../../includes/top.php';
?>
<div class="d-flex justify-content-between align-items-center mb-4">
  <h4 class="mb-0">Detail Pelamar</h4>
  <a href="list.php" class="btn btn-outline-secondary"><i class="bi bi-arrow-left"></i> Kembali</a>
</div>

<?php if (isset($_GET['sukses'])): ?><div class="alert alert-success"><?= htmlspecialchars($_GET['sukses']) ?></div><?php endif; ?>

<div class="row g-4">
  <div class="col-lg-7">
    <div class="card card-admin p-4">
      <h5 class="mb-1"><?= htmlspecialchars($d['nama_lengkap']) ?></h5>
      <p class="text-muted mb-3">Kode Lamaran: <code><?= htmlspecialchars($d['kode_lamaran']) ?></code></p>

      <table class="table table-borderless mb-0">
        <tbody>
          <tr><td class="text-muted" style="width:180px;">Lowongan Dilamar</td><td><strong><?= htmlspecialchars($d['posisi'] ?? '(lowongan dihapus)') ?></strong> &mdash; <?= htmlspecialchars($d['perusahaan'] ?? '-') ?></td></tr>
          <tr><td class="text-muted">Tahun Lulus</td><td><?= htmlspecialchars($d['tahun_lulus']) ?></td></tr>
          <tr><td class="text-muted">Jurusan</td><td><?= htmlspecialchars($d['jurusan']) ?></td></tr>
          <tr><td class="text-muted">No. HP / WhatsApp</td><td><?= htmlspecialchars($d['no_hp']) ?></td></tr>
          <tr><td class="text-muted">Email</td><td><?= htmlspecialchars($d['email'] ?? '-') ?></td></tr>
          <tr><td class="text-muted">Alamat</td><td style="white-space:pre-line;"><?= htmlspecialchars($d['alamat'] ?? '-') ?></td></tr>
          <tr><td class="text-muted">CV</td><td>
            <?php if ($d['cv_file']): ?>
              <a href="../../../uploads/bkk/<?= htmlspecialchars($d['cv_file']) ?>" target="_blank" class="btn btn-sm btn-outline-primary"><i class="bi bi-file-earmark-arrow-down"></i> Lihat / Unduh CV</a>
            <?php else: ?>
              <span class="text-muted">Tidak ada CV diunggah</span>
            <?php endif; ?>
          </td></tr>
          <tr><td class="text-muted">Mendaftar pada</td><td><?= date('d M Y H:i', strtotime($d['created_at'])) ?></td></tr>
        </tbody>
      </table>
    </div>
  </div>

  <div class="col-lg-5">
    <div class="card card-admin p-4">
      <h6 class="mb-3">Status Lamaran</h6>
      <form method="POST">
        <div class="mb-3">
          <select name="status" class="form-select">
            <?php
            $opsi = ['baru' => 'Baru (belum diproses)', 'diproses' => 'Diproses (diteruskan ke perusahaan)', 'lolos' => 'Lolos seleksi', 'tidak_lolos' => 'Tidak lolos'];
            foreach ($opsi as $key => $label): ?>
              <option value="<?= $key ?>" <?= $d['status'] === $key ? 'selected' : '' ?>><?= $label ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        <button type="submit" class="btn btn-primary text-white">Simpan Status</button>
      </form>
      <hr>
      <p class="small text-muted mb-0">Alumni yang <strong>diproses</strong> berarti lamarannya sudah diteruskan ke perusahaan. Pantau hasil seleksi untuk memperbarui status menjadi lolos / tidak lolos.</p>
    </div>
  </div>
</div>
<?php require_once __DIR__ . '/../../includes/bottom.php'; ?>
