<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../../includes/koneksi.php';

$filterStatus = $_GET['status'] ?? 'semua';
$sql = "SELECT p.*, j.nama_jurusan FROM pendaftaran_ppdb p LEFT JOIN jurusan j ON p.jurusan_pilihan = j.id";
if (in_array($filterStatus, ['menunggu','diverifikasi','ditolak','diterima'], true)) {
    $sql .= " WHERE p.status = " . $pdo->quote($filterStatus);
}
$sql .= " ORDER BY p.created_at DESC";
$data = $pdo->query($sql)->fetchAll();

$pageTitleAdmin = 'Data Pendaftar PPDB';
require_once __DIR__ . '/../includes/top.php';

$badgeColor = ['menunggu'=>'secondary','diverifikasi'=>'info','diterima'=>'success','ditolak'=>'danger'];
?>
<div class="d-flex justify-content-between align-items-center mb-4">
  <h4 class="mb-0">Data Pendaftar PPDB</h4>
</div>

<?php if (isset($_GET['sukses'])): ?><div class="alert alert-success"><?= htmlspecialchars($_GET['sukses']) ?></div><?php endif; ?>

<div class="d-flex flex-wrap gap-2 mb-3">
  <?php foreach (['semua'=>'Semua','menunggu'=>'Menunggu','diverifikasi'=>'Diverifikasi','diterima'=>'Diterima','ditolak'=>'Ditolak'] as $key => $label): ?>
    <a href="list.php?status=<?= $key ?>" class="btn btn-sm <?= $filterStatus===$key ? 'btn-primary text-white' : 'btn-outline-secondary' ?>"><?= $label ?></a>
  <?php endforeach; ?>
</div>

<div class="card card-admin">
  <div class="table-responsive">
    <table class="table table-hover align-middle mb-0">
      <thead><tr><th>No. Pendaftaran</th><th>Nama</th><th>Jurusan Pilihan</th><th>No. HP</th><th>Tanggal</th><th>Status</th><th class="text-end">Aksi</th></tr></thead>
      <tbody>
        <?php if (!$data): ?><tr><td colspan="7" class="text-center text-muted py-4">Belum ada pendaftar.</td></tr><?php endif; ?>
        <?php foreach ($data as $d): ?>
        <tr>
          <td><code><?= htmlspecialchars($d['nomor_pendaftaran']) ?></code></td>
          <td><?= htmlspecialchars($d['nama_lengkap']) ?></td>
          <td><?= htmlspecialchars($d['nama_jurusan'] ?? '-') ?></td>
          <td><?= htmlspecialchars($d['no_hp']) ?></td>
          <td><?= date('d M Y', strtotime($d['created_at'])) ?></td>
          <td><span class="badge bg-<?= $badgeColor[$d['status']] ?>"><?= htmlspecialchars(ucfirst($d['status'])) ?></span></td>
          <td class="text-end">
            <a href="detail.php?id=<?= $d['id'] ?>" class="btn btn-sm btn-outline-primary"><i class="bi bi-eye"></i></a>
            <a href="hapus.php?id=<?= $d['id'] ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Yakin hapus data pendaftar ini?');"><i class="bi bi-trash"></i></a>
          </td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>
<?php require_once __DIR__ . '/../includes/bottom.php'; ?>
