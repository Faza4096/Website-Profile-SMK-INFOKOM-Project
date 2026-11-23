<?php
require_once __DIR__ . '/../../includes/auth.php';
require_once __DIR__ . '/../../../includes/koneksi.php';

$filterStatus = $_GET['status'] ?? 'semua';
$sql = "SELECT p.*, l.posisi, l.perusahaan FROM bkk_pelamar p
        LEFT JOIN bkk_lowongan l ON p.lowongan_id = l.id";
if (in_array($filterStatus, ['baru', 'diproses', 'lolos', 'tidak_lolos'], true)) {
    $sql .= " WHERE p.status = " . $pdo->quote($filterStatus);
}
$sql .= " ORDER BY p.created_at DESC";
$data = $pdo->query($sql)->fetchAll();

$pageTitleAdmin = 'Daftar Pelamar';
require_once __DIR__ . '/../../includes/top.php';

$badgeColor = ['baru' => 'secondary', 'diproses' => 'info', 'lolos' => 'success', 'tidak_lolos' => 'danger'];
$labelStatus = ['baru' => 'Baru', 'diproses' => 'Diproses', 'lolos' => 'Lolos', 'tidak_lolos' => 'Tidak Lolos'];
?>
<div class="d-flex justify-content-between align-items-center mb-4">
  <h4 class="mb-0">Daftar Pelamar</h4>
</div>

<?php if (isset($_GET['sukses'])): ?><div class="alert alert-success"><?= htmlspecialchars($_GET['sukses']) ?></div><?php endif; ?>

<div class="d-flex flex-wrap gap-2 mb-3">
  <?php foreach (['semua' => 'Semua', 'baru' => 'Baru', 'diproses' => 'Diproses', 'lolos' => 'Lolos', 'tidak_lolos' => 'Tidak Lolos'] as $key => $label): ?>
    <a href="list.php?status=<?= $key ?>" class="btn btn-sm <?= $filterStatus === $key ? 'btn-primary text-white' : 'btn-outline-secondary' ?>"><?= $label ?></a>
  <?php endforeach; ?>
</div>

<div class="card card-admin">
  <div class="table-responsive">
    <table class="table table-hover align-middle mb-0">
      <thead><tr><th>Kode Lamaran</th><th>Nama</th><th>Lowongan</th><th>Jurusan</th><th>Tahun Lulus</th><th>Tanggal</th><th>Status</th><th class="text-end">Aksi</th></tr></thead>
      <tbody>
        <?php if (!$data): ?><tr><td colspan="8" class="text-center text-muted py-4">Belum ada pelamar.</td></tr><?php endif; ?>
        <?php foreach ($data as $d): ?>
        <tr>
          <td><code><?= htmlspecialchars($d['kode_lamaran']) ?></code></td>
          <td>
            <strong><?= htmlspecialchars($d['nama_lengkap']) ?></strong>
            <div class="small text-muted"><?= htmlspecialchars($d['no_hp']) ?></div>
          </td>
          <td>
            <?= htmlspecialchars($d['posisi'] ?? '(lowongan dihapus)') ?>
            <div class="small text-muted"><?= htmlspecialchars($d['perusahaan'] ?? '-') ?></div>
          </td>
          <td class="small"><?= htmlspecialchars($d['jurusan']) ?></td>
          <td><?= htmlspecialchars($d['tahun_lulus']) ?></td>
          <td class="small"><?= date('d M Y', strtotime($d['created_at'])) ?></td>
          <td><span class="badge bg-<?= $badgeColor[$d['status']] ?>"><?= $labelStatus[$d['status']] ?></span></td>
          <td class="text-end">
            <a href="detail.php?id=<?= $d['id'] ?>" class="btn btn-sm btn-outline-primary"><i class="bi bi-eye"></i></a>
            <a href="hapus.php?id=<?= $d['id'] ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Yakin hapus data pelamar ini?');"><i class="bi bi-trash"></i></a>
          </td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>
<?php require_once __DIR__ . '/../../includes/bottom.php'; ?>
