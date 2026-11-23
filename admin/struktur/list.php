<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../../includes/koneksi.php';
require_once __DIR__ . '/../includes/urut.php';

// Geser urutan baris (tombol panah naik/turun) lalu kembali ke daftar
if (isset($_GET['aksi'])) {
    urut_pindah($pdo, 'struktur_organisasi', (int)($_GET['id'] ?? 0), (string)$_GET['aksi']);
    header('Location: list.php?sukses=' . urlencode('Urutan data diperbarui.'));
    exit;
}

$data = $pdo->query("SELECT * FROM struktur_organisasi ORDER BY urutan ASC, id ASC")->fetchAll();
$pageTitleAdmin = 'Kelola Struktur Organisasi';
require_once __DIR__ . '/../includes/top.php';
?>
<div class="d-flex justify-content-between align-items-center mb-4">
  <h4 class="mb-0">Struktur Organisasi</h4>
  <a href="tambah.php" class="btn btn-primary text-white"><i class="bi bi-plus-lg"></i> Tambah</a>
</div>
<?php if (isset($_GET['sukses'])): ?><div class="alert alert-success"><?= htmlspecialchars($_GET['sukses']) ?></div><?php endif; ?>
<div class="card card-admin">
  <div class="table-responsive">
    <table class="table table-hover align-middle mb-0">
      <thead><tr><th>#</th><th>Foto</th><th>Jabatan</th><th>Nama</th><th class="text-end">Aksi</th></tr></thead>
      <tbody>
        <?php if (!$data): ?><tr><td colspan="5" class="text-center text-muted py-4">Belum ada data.</td></tr><?php endif; ?>
        <?php foreach ($data as $i => $d): ?>
        <tr>
          <td><?= $i+1 ?></td>
          <td><img src="../../uploads/struktur/<?= htmlspecialchars($d['foto'] ?: 'default.jpg') ?>" width="45" height="45" style="object-fit:cover;border-radius:50%;" onerror="this.src='https://placehold.co/45'"></td>
          <td><span class="badge badge-ink"><?= htmlspecialchars($d['jabatan']) ?></span></td>
          <td><?= htmlspecialchars($d['nama']) ?></td>
          <td class="text-end">
            <a href="list.php?aksi=naik&id=<?= $d['id'] ?>" class="btn btn-sm btn-outline-secondary" title="Naik"<?= $i === 0 ? ' aria-disabled="true"' : '' ?>><i class="bi bi-arrow-up"></i></a>
            <a href="list.php?aksi=turun&id=<?= $d['id'] ?>" class="btn btn-sm btn-outline-secondary" title="Turun"<?= $i === count($data) - 1 ? ' aria-disabled="true"' : '' ?>><i class="bi bi-arrow-down"></i></a>
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
