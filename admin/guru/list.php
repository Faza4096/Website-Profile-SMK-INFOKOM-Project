<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../../includes/koneksi.php';
require_once __DIR__ . '/../includes/urut.php';
$base = $GLOBALS['ADMIN_BASE'];

// Geser urutan baris (tombol panah naik/turun) lalu kembali ke daftar
if (isset($_GET['aksi'])) {
    urut_pindah($pdo, 'guru_staff', (int)($_GET['id'] ?? 0), (string)$_GET['aksi']);
    header('Location: list.php?sukses=' . urlencode('Urutan data diperbarui.'));
    exit;
}

$guru = $pdo->query("SELECT * FROM guru_staff ORDER BY urutan ASC, id ASC")->fetchAll();
$pageTitleAdmin = 'Kelola Guru & Staff';
require_once __DIR__ . '/../includes/top.php';
?>
<div class="d-flex justify-content-between align-items-center mb-4">
  <h4 class="mb-0">Kelola Guru &amp; Staff</h4>
  <a href="tambah.php" class="btn btn-primary text-white"><i class="bi bi-plus-lg"></i> Tambah</a>
</div>
<?php if (isset($_GET['sukses'])): ?><div class="alert alert-success"><?= htmlspecialchars($_GET['sukses']) ?></div><?php endif; ?>
<div class="card card-admin">
  <div class="table-responsive">
    <table class="table table-hover align-middle mb-0">
      <thead><tr><th>#</th><th>Foto</th><th>Nama</th><th>Jabatan</th><th>Bidang</th><th class="text-end">Aksi</th></tr></thead>
      <tbody>
        <?php if (!$guru): ?><tr><td colspan="6" class="text-center text-muted py-4">Belum ada data.</td></tr><?php endif; ?>
        <?php foreach ($guru as $i => $g): ?>
        <tr>
          <td><?= $i+1 ?></td>
          <td><img src="../../uploads/guru/<?= htmlspecialchars($g['foto'] ?: 'default.jpg') ?>" width="50" height="50" style="object-fit:cover;border-radius:50%;" onerror="this.src='https://placehold.co/50'"></td>
          <td><?= htmlspecialchars($g['nama']) ?></td>
          <td><?= htmlspecialchars($g['jabatan']) ?></td>
          <td><?= htmlspecialchars($g['bidang'] ?? '-') ?></td>
          <td class="text-end">
            <a href="list.php?aksi=naik&id=<?= $g['id'] ?>" class="btn btn-sm btn-outline-secondary" title="Naik"<?= $i === 0 ? ' aria-disabled="true"' : '' ?>><i class="bi bi-arrow-up"></i></a>
            <a href="list.php?aksi=turun&id=<?= $g['id'] ?>" class="btn btn-sm btn-outline-secondary" title="Turun"<?= $i === count($guru) - 1 ? ' aria-disabled="true"' : '' ?>><i class="bi bi-arrow-down"></i></a>
            <a href="edit.php?id=<?= $g['id'] ?>" class="btn btn-sm btn-outline-warning"><i class="bi bi-pencil-square"></i></a>
            <a href="hapus.php?id=<?= $g['id'] ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Yakin hapus data ini?');"><i class="bi bi-trash"></i></a>
          </td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>
<?php require_once __DIR__ . '/../includes/bottom.php'; ?>
