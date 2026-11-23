<?php
// Modul "Kelola Data Siswa" dihapus dari panel admin — akses langsung dialihkan ke dashboard.
header('Location: ../index.php');
exit;

require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../../includes/koneksi.php';
$base = $GLOBALS['ADMIN_BASE'];

$q     = trim($_GET['q'] ?? '');
$status = $_GET['status'] ?? 'semua';
$statusValid = ['semua', 'aktif', 'lulus', 'nonaktif'];
if (!in_array($status, $statusValid, true)) {
    $status = 'semua';
}

$sql = "SELECT s.*, j.nama_jurusan, j.singkatan
        FROM siswa s
        LEFT JOIN jurusan j ON j.id = s.jurusan_id
        WHERE 1=1";
$params = [];
if ($q !== '') {
    $sql .= " AND (s.nama LIKE :q OR s.nis LIKE :q)";
    $params[':q'] = '%' . $q . '%';
}
if ($status !== 'semua') {
    $sql .= " AND s.status = :status";
    $params[':status'] = $status;
}
$sql .= " ORDER BY s.kelas ASC, s.nis ASC";
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$siswa = $stmt->fetchAll();

$labelStatus = ['aktif' => 'Aktif', 'lulus' => 'Lulus', 'nonaktif' => 'Nonaktif'];
$pageTitleAdmin = 'Kelola Data Siswa';
require_once __DIR__ . '/../includes/top.php';
?>
<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
  <div>
    <h4 class="mb-0">Kelola Data Siswa</h4>
    <small class="text-muted">Angka "Siswa Aktif" di beranda dihitung langsung dari tabel ini (realtime).</small>
  </div>
  <a href="tambah.php" class="btn btn-primary text-white"><i class="bi bi-plus-lg"></i> Tambah</a>
</div>
<?php if (isset($_GET['sukses'])): ?><div class="alert alert-success"><?= htmlspecialchars($_GET['sukses']) ?></div><?php endif; ?>

<div class="card card-admin p-3 mb-4">
  <form method="GET" class="d-flex flex-wrap gap-2 align-items-center">
    <input type="text" name="q" class="form-control" style="max-width:280px;" placeholder="Cari nama atau NIS..." value="<?= htmlspecialchars($q) ?>">
    <select name="status" class="form-select" style="max-width:160px;">
      <?php foreach ($statusValid as $s): ?>
      <option value="<?= $s ?>" <?= $s === $status ? 'selected' : '' ?>><?= $s === 'semua' ? 'Semua Status' : $labelStatus[$s] ?></option>
      <?php endforeach; ?>
    </select>
    <button class="btn btn-primary text-white"><i class="bi bi-search"></i> Cari</button>
    <a href="list.php" class="btn btn-outline-secondary">Reset</a>
  </form>
</div>

<div class="card card-admin">
  <div class="table-responsive">
    <table class="table table-hover align-middle mb-0">
      <thead><tr><th>#</th><th>NIS</th><th>Nama</th><th>JK</th><th>Kelas</th><th>Jurusan</th><th>Status</th><th class="text-end">Aksi</th></tr></thead>
      <tbody>
        <?php if (!$siswa): ?><tr><td colspan="8" class="text-center text-muted py-4">Tidak ada data siswa yang cocok.</td></tr><?php endif; ?>
        <?php foreach ($siswa as $i => $s): ?>
        <tr>
          <td><?= $i + 1 ?></td>
          <td><?= htmlspecialchars($s['nis']) ?></td>
          <td><?= htmlspecialchars($s['nama']) ?></td>
          <td><?= $s['jenis_kelamin'] === 'Laki-laki' ? 'L' : 'P' ?></td>
          <td><?= htmlspecialchars($s['kelas']) ?></td>
          <td><?= htmlspecialchars($s['singkatan'] ?: ($s['nama_jurusan'] ?? '-')) ?></td>
          <td><span class="badge bg-<?= $s['status'] === 'aktif' ? 'success' : ($s['status'] === 'lulus' ? 'secondary' : 'danger') ?>"><?= $labelStatus[$s['status']] ?></span></td>
          <td class="text-end">
            <a href="edit.php?id=<?= (int)$s['id'] ?>" class="btn btn-sm btn-outline-warning"><i class="bi bi-pencil-square"></i></a>
            <a href="hapus.php?id=<?= (int)$s['id'] ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Yakin hapus data siswa ini?');"><i class="bi bi-trash"></i></a>
          </td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>
<?php require_once __DIR__ . '/../includes/bottom.php'; ?>
