<?php
require_once __DIR__ . '/../../includes/auth.php';
require_once __DIR__ . '/../../../includes/koneksi.php';
require_once __DIR__ . '/../../../includes/kalender.php';

kalender_pendidikan_siapkan($pdo);
$data = $pdo->query("SELECT * FROM kalender_pendidikan ORDER BY tanggal_mulai ASC, id ASC")->fetchAll();

$pageTitleAdmin = 'Kalender Pendidikan';
require_once __DIR__ . '/../../includes/top.php';

$badgeKalender = ['Kegiatan'=>'bg-primary','Akademik'=>'bg-primary','Ujian'=>'bg-warning text-dark','Libur'=>'bg-secondary','PPDB'=>'bg-success'];
$tanggal = function (?string $d): string { return $d ? date('d M Y', strtotime($d)) : '-'; };
?>
<div class="d-flex justify-content-between align-items-center mb-4">
  <h4 class="mb-0">Kalender Pendidikan</h4>
  <a href="tambah.php" class="btn btn-primary text-white"><i class="bi bi-plus-lg"></i> Tambah</a>
</div>
<?php if (isset($_GET['sukses'])): ?><div class="alert alert-success"><?= htmlspecialchars($_GET['sukses']) ?></div><?php endif; ?>

<div class="card card-admin">
  <div class="table-responsive">
    <table class="table table-hover align-middle mb-0">
      <thead><tr><th>Tahun Pelajaran</th><th>Kegiatan</th><th>Kategori</th><th>Tanggal</th><th class="text-end">Aksi</th></tr></thead>
      <tbody>
        <?php if (!$data): ?><tr><td colspan="5" class="text-center text-muted py-4">Belum ada data.</td></tr><?php endif; ?>
        <?php foreach ($data as $d): ?>
        <tr>
          <td><code><?= htmlspecialchars($d['tahun_pelajaran']) ?></code></td>
          <td>
            <span class="fw-semibold"><?= htmlspecialchars($d['kegiatan']) ?></span>
            <?php if (!empty($d['keterangan'])): ?><br><small class="text-muted"><?= htmlspecialchars($d['keterangan']) ?></small><?php endif; ?>
          </td>
          <td><span class="badge <?= $badgeKalender[$d['kategori']] ?? 'bg-primary' ?>"><?= htmlspecialchars($d['kategori']) ?></span></td>
          <td class="small"><?= $tanggal($d['tanggal_mulai']) ?><?= $d['tanggal_selesai'] ? ' &ndash; ' . $tanggal($d['tanggal_selesai']) : '' ?></td>
          <td class="text-end">
            <a href="edit.php?id=<?= $d['id'] ?>" class="btn btn-sm btn-outline-warning"><i class="bi bi-pencil-square"></i></a>
            <a href="hapus.php?id=<?= $d['id'] ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Yakin hapus kegiatan ini?');"><i class="bi bi-trash"></i></a>
          </td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>
<?php require_once __DIR__ . '/../../includes/bottom.php'; ?>
