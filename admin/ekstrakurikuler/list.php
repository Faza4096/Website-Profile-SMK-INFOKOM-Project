<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../../includes/koneksi.php';

try {
    $ekstra = $pdo->query("SELECT * FROM ekstrakurikuler ORDER BY urutan ASC, id ASC")->fetchAll();
} catch (Throwable $e) {
    $ekstra = [];
}
$pageTitleAdmin = 'Kelola Ekstrakurikuler';
require_once __DIR__ . '/../includes/top.php';
?>

    <div class="d-flex justify-content-between align-items-center mb-4">
      <h4 class="mb-0">Kelola Ekstrakurikuler</h4>
      <a href="tambah.php" class="btn btn-primary"><i class="bi bi-plus-lg"></i> Tambah Kegiatan</a>
    </div>

    <?php if (isset($_GET['sukses'])): ?>
      <div class="alert alert-success"><?= htmlspecialchars($_GET['sukses']) ?></div>
    <?php endif; ?>

    <?php if (count($ekstra) === 0): ?>
      <div class="card card-admin p-4 text-center text-muted">
        Belum ada data ekstrakurikuler. Impor <code>db_ekstrakurikuler.sql</code> atau tambah kegiatan baru.
      </div>
    <?php endif; ?>

    <?php if (count($ekstra) > 0): ?>
    <div class="card card-admin">
      <div class="table-responsive">
        <table class="table align-middle mb-0">
          <thead>
            <tr>
              <th style="width:60px;">Urutan</th>
              <th>Kegiatan</th>
              <th>Pembina</th>
              <th>Jadwal</th>
              <th style="width:140px;">Gambar</th>
              <th style="width:120px;">Aksi</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($ekstra as $e): ?>
            <tr>
              <td><?= (int)$e['urutan'] ?></td>
              <td>
                <span class="fw-semibold"><?= htmlspecialchars($e['nama']) ?></span>
                <p class="small text-muted mb-0"><?= htmlspecialchars(mb_strimwidth($e['deskripsi'] ?? '', 0, 90, '...')) ?></p>
              </td>
              <td><?= htmlspecialchars($e['pembina'] ?: '-') ?></td>
              <td><?= htmlspecialchars($e['jadwal'] ?: '-') ?></td>
              <td>
                <?php if (!empty($e['gambar'])): ?>
                  <img src="../../uploads/ekstrakurikuler/<?= htmlspecialchars($e['gambar']) ?>" width="70" height="46" style="object-fit:cover;" class="rounded" alt="">
                <?php else: ?>
                  <span class="badge bg-secondary">-</span>
                <?php endif; ?>
              </td>
              <td>
                <div class="d-flex gap-1">
                  <a href="edit.php?id=<?= (int)$e['id'] ?>" class="btn btn-sm btn-outline-warning"><i class="bi bi-pencil-square"></i></a>
                  <a href="hapus.php?id=<?= (int)$e['id'] ?>" class="btn btn-sm btn-outline-danger"
                     onclick="return confirm('Yakin ingin menghapus kegiatan ini?');"><i class="bi bi-trash"></i></a>
                </div>
              </td>
            </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
    <?php endif; ?>
<?php require_once __DIR__ . '/../includes/bottom.php'; ?>
