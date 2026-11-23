<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../../includes/koneksi.php';
$data = $pdo->query("SELECT * FROM prestasi ORDER BY tahun DESC, created_at DESC")->fetchAll();
$pageTitleAdmin = 'Kelola Prestasi';
require_once __DIR__ . '/../includes/top.php';
?>
<div class="d-flex justify-content-between align-items-center mb-4">
  <h4 class="mb-0">Prestasi</h4>
  <a href="tambah.php" class="btn btn-primary text-white"><i class="bi bi-plus-lg"></i> Tambah</a>
</div>
<?php if (isset($_GET['sukses'])): ?><div class="alert alert-success"><?= htmlspecialchars($_GET['sukses']) ?></div><?php endif; ?>
<div class="card card-admin">
  <div class="table-responsive">
    <table class="table table-hover align-middle mb-0">
      <thead><tr><th>#</th><th>Gambar</th><th>Judul</th><th>Tingkat</th><th>Tahun</th><th class="text-end">Aksi</th></tr></thead>
      <tbody>
        <?php if (!$data): ?><tr><td colspan="6" class="text-center text-muted py-4">Belum ada data.</td></tr><?php endif; ?>
        <?php foreach ($data as $i => $d): ?>
        <tr>
          <td><?= $i+1 ?></td>
          <td><img src="../../uploads/prestasi/<?= htmlspecialchars($d['gambar'] ?: 'default.jpg') ?>" width="60" height="45" style="object-fit:cover;border-radius:6px;" onerror="this.src='https://placehold.co/60x45'"></td>
          <td><?= htmlspecialchars($d['judul']) ?></td>
          <td><span class="badge badge-ink"><?= htmlspecialchars($d['tingkat']) ?></span></td>
          <td><?= htmlspecialchars($d['tahun']) ?></td>
          <td class="text-end">
            <a href="edit.php?id=<?= $d['id'] ?>" class="btn btn-sm btn-outline-warning"><i class="bi bi-pencil-square"></i></a>
            <a href="hapus.php?id=<?= $d['id'] ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Yakin hapus data ini?');"><i class="bi bi-trash"></i></a>
          </td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>
<?php require_once __DIR__ . '/../includes/bottom.php'; ?>
