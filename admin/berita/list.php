<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../../includes/koneksi.php';

$berita = $pdo->query("SELECT * FROM berita ORDER BY created_at DESC")->fetchAll();
$pageTitleAdmin = 'Kelola Berita';
require_once __DIR__ . '/../includes/top.php';
?>
<div class="d-flex justify-content-between align-items-center mb-4">
  <h4 class="mb-0">Kelola Berita</h4>
  <a href="tambah.php" class="btn btn-primary text-white"><i class="bi bi-plus-lg"></i> Tambah Berita</a>
</div>

<?php if (isset($_GET['sukses'])): ?>
  <div class="alert alert-success"><?= htmlspecialchars($_GET['sukses']) ?></div>
<?php endif; ?>

<div class="card card-admin">
  <div class="table-responsive">
    <table class="table table-hover align-middle mb-0">
      <thead>
        <tr>
          <th>#</th><th>Gambar</th><th>Judul</th><th>Penulis</th><th>Status</th><th>Tanggal</th><th class="text-end">Aksi</th>
        </tr>
      </thead>
      <tbody>
        <?php if (count($berita) === 0): ?>
          <tr><td colspan="7" class="text-center text-muted py-4">Belum ada data berita.</td></tr>
        <?php endif; ?>
        <?php foreach ($berita as $i => $b): ?>
        <tr>
          <td><?= $i + 1 ?></td>
          <td>
            <img src="../../uploads/berita/<?= htmlspecialchars($b['gambar'] ?: 'default.jpg') ?>"
                 width="60" height="45" style="object-fit:cover;border-radius:6px;"
                 onerror="this.src='https://placehold.co/60x45'">
          </td>
          <td><?= htmlspecialchars($b['judul']) ?></td>
          <td><?= htmlspecialchars($b['penulis']) ?></td>
          <td>
            <span class="badge <?= $b['status']==='publish' ? 'bg-success' : 'bg-secondary' ?>">
              <?= htmlspecialchars(ucfirst($b['status'])) ?>
            </span>
          </td>
          <td><?= date('d M Y', strtotime($b['created_at'])) ?></td>
          <td class="text-end">
            <a href="edit.php?id=<?= $b['id'] ?>" class="btn btn-sm btn-outline-warning"><i class="bi bi-pencil-square"></i></a>
            <a href="hapus.php?id=<?= $b['id'] ?>" class="btn btn-sm btn-outline-danger"
               onclick="return confirm('Yakin ingin menghapus berita ini?');"><i class="bi bi-trash"></i></a>
          </td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>
<?php require_once __DIR__ . '/../includes/bottom.php'; ?>
