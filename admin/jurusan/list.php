<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../../includes/koneksi.php';

$jurusan = $pdo->query("SELECT * FROM jurusan ORDER BY id ASC")->fetchAll();
$pageTitleAdmin = 'Kelola Jurusan';
require_once __DIR__ . '/../includes/top.php';
?>

    <div class="d-flex justify-content-between align-items-center mb-4">
      <h4 class="mb-0">Kelola Jurusan</h4>
      <a href="tambah.php" class="btn btn-primary"><i class="bi bi-plus-lg"></i> Tambah Jurusan</a>
    </div>

    <?php if (isset($_GET['sukses'])): ?>
      <div class="alert alert-success"><?= htmlspecialchars($_GET['sukses']) ?></div>
    <?php endif; ?>

    <div class="card card-admin">
      <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
          <thead>
            <tr>
              <th>#</th>
              <th>Gambar</th>
              <th>Nama Jurusan</th>
              <th>Singkatan</th>
              <th>Deskripsi</th>
              <th class="text-end">Aksi</th>
            </tr>
          </thead>
          <tbody>
            <?php if (count($jurusan) === 0): ?>
              <tr><td colspan="6" class="text-center text-muted py-4">Belum ada data jurusan.</td></tr>
            <?php endif; ?>
            <?php foreach ($jurusan as $i => $j): ?>
            <tr>
              <td><?= $i + 1 ?></td>
              <td>
                <img src="../../uploads/jurusan/<?= htmlspecialchars($j['gambar'] ?: 'default.jpg') ?>"
                     width="60" height="45" style="object-fit:cover;border-radius:6px;"
                     onerror="this.src='https://placehold.co/60x45'">
              </td>
              <td><?= htmlspecialchars($j['nama_jurusan']) ?></td>
              <td><span class="badge bg-secondary"><?= htmlspecialchars($j['singkatan']) ?></span></td>
              <td class="text-muted small"><?= htmlspecialchars(mb_strimwidth($j['deskripsi'], 0, 60, '...')) ?></td>
              <td class="text-end">
                <a href="edit.php?id=<?= $j['id'] ?>" class="btn btn-sm btn-outline-warning"><i class="bi bi-pencil-square"></i></a>
                <a href="hapus.php?id=<?= $j['id'] ?>" class="btn btn-sm btn-outline-danger"
                   onclick="return confirm('Yakin ingin menghapus jurusan ini?');"><i class="bi bi-trash"></i></a>
              </td>
            </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
<?php require_once __DIR__ . '/../includes/bottom.php'; ?>
