<?php
require_once __DIR__ . '/../../includes/auth.php';
require_once __DIR__ . '/../../../includes/koneksi.php';

// Kategori lowongan — sama dengan pilihan di form tambah/edit.
$daftarTipe = ['Full-time', 'Part-time', 'Kontrak', 'Magang', 'PKL'];

$filterStatus = $_GET['status'] ?? 'semua';
if (!in_array($filterStatus, ['semua', 'buka', 'tutup'], true)) {
    $filterStatus = 'semua';
}
$filterTipe = $_GET['tipe'] ?? 'semua';
if (!in_array($filterTipe, array_merge(['semua'], $daftarTipe), true)) {
    $filterTipe = 'semua';
}

$where  = [];
$params = [];
if ($filterStatus !== 'semua') {
    $where[]  = 'status = ?';
    $params[] = $filterStatus;
}
if ($filterTipe !== 'semua') {
    $where[]  = 'tipe_pekerjaan = ?';
    $params[] = $filterTipe;
}
$sql = "SELECT * FROM bkk_lowongan"
     . ($where ? ' WHERE ' . implode(' AND ', $where) : '')
     . " ORDER BY created_at DESC";
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$data = $stmt->fetchAll();

$pageTitleAdmin = 'Daftar Lowongan';
require_once __DIR__ . '/../../includes/top.php';
?>
<div class="d-flex justify-content-between align-items-center mb-4">
  <h4 class="mb-0">Daftar Lowongan</h4>
  <a href="tambah.php" class="btn btn-primary text-white"><i class="bi bi-plus-lg"></i> Tambah Lowongan</a>
</div>

<?php if (isset($_GET['sukses'])): ?><div class="alert alert-success"><?= htmlspecialchars($_GET['sukses']) ?></div><?php endif; ?>

<div class="d-flex flex-wrap gap-2 mb-2">
  <?php foreach (['semua' => 'Semua', 'buka' => 'Dibuka', 'tutup' => 'Ditutup'] as $key => $label): ?>
    <a href="list.php?status=<?= $key ?>&tipe=<?= urlencode($filterTipe) ?>" class="btn btn-sm <?= $filterStatus === $key ? 'btn-primary text-white' : 'btn-outline-secondary' ?>"><?= $label ?></a>
  <?php endforeach; ?>
</div>
<div class="d-flex flex-wrap gap-2 mb-3">
  <?php foreach (array_merge(['semua' => 'Semua Kategori'], array_combine($daftarTipe, $daftarTipe)) as $key => $label): ?>
    <a href="list.php?status=<?= urlencode($filterStatus) ?>&tipe=<?= urlencode($key) ?>" class="btn btn-sm <?= $filterTipe === $key ? 'btn-primary text-white' : 'btn-outline-secondary' ?>"><?= $label ?></a>
  <?php endforeach; ?>
</div>

<div class="card card-admin">
  <div class="table-responsive">
    <table class="table table-hover align-middle mb-0">
      <thead><tr><th>#</th><th>Posisi</th><th>Perusahaan</th><th>Tipe</th><th>Batas Daftar</th><th>Status</th><th class="text-end">Aksi</th></tr></thead>
      <tbody>
        <?php if (!$data): ?><tr><td colspan="7" class="text-center text-muted py-4">Belum ada lowongan.</td></tr><?php endif; ?>
        <?php foreach ($data as $i => $d): ?>
        <tr>
          <td><?= $i + 1 ?></td>
          <td>
            <strong><?= htmlspecialchars($d['posisi']) ?></strong>
            <div class="small text-muted"><?= htmlspecialchars($d['lokasi'] ?: '-') ?></div>
          </td>
          <td><?= htmlspecialchars($d['perusahaan']) ?></td>
          <td><span class="badge badge-ink"><?= htmlspecialchars($d['tipe_pekerjaan']) ?></span></td>
          <td class="small"><?= $d['batas_daftar'] ? date('d M Y', strtotime($d['batas_daftar'])) : '-' ?></td>
          <td><span class="badge <?= $d['status'] === 'buka' ? 'bg-success' : 'bg-secondary' ?>"><?= $d['status'] === 'buka' ? 'Dibuka' : 'Ditutup' ?></span></td>
          <td class="text-end">
            <a href="edit.php?id=<?= $d['id'] ?>" class="btn btn-sm btn-outline-warning"><i class="bi bi-pencil-square"></i></a>
            <a href="hapus.php?id=<?= $d['id'] ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Yakin hapus lowongan ini? Seluruh pelamar terkait juga akan terhapus.');"><i class="bi bi-trash"></i></a>
          </td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>
<?php require_once __DIR__ . '/../../includes/bottom.php'; ?>
