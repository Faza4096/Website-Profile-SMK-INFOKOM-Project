<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../../includes/koneksi.php';

$galeri = $pdo->query("SELECT * FROM galeri ORDER BY created_at DESC")->fetchAll();
$pageTitleAdmin = 'Kelola Galeri';
require_once __DIR__ . '/../includes/top.php';
?>

    <div class="d-flex justify-content-between align-items-center mb-4">
      <h4 class="mb-0">Kelola Galeri</h4>
      <a href="tambah.php" class="btn btn-primary"><i class="bi bi-plus-lg"></i> Upload Foto</a>
    </div>

    <?php if (isset($_GET['sukses'])): ?>
      <div class="alert alert-success"><?= htmlspecialchars($_GET['sukses']) ?></div>
    <?php endif; ?>

    <div class="row g-3">
      <?php if (count($galeri) === 0): ?>
        <p class="text-center text-muted py-4">Belum ada foto galeri.</p>
      <?php endif; ?>

      <?php foreach ($galeri as $g): ?>
      <div class="col-6 col-md-3">
        <div class="card card-admin h-100">
          <img src="../../uploads/galeri/<?= htmlspecialchars($g['gambar']) ?>"
               style="height:140px;object-fit:cover;" class="card-img-top"
               onerror="this.src='https://placehold.co/300x200'">
          <div class="card-body p-2">
            <p class="small fw-semibold mb-1"><?= htmlspecialchars($g['judul']) ?></p>
            <span class="badge bg-secondary mb-2"><?= htmlspecialchars($g['kategori']) ?></span>
            <div class="d-flex gap-1">
              <a href="edit.php?id=<?= $g['id'] ?>" class="btn btn-sm btn-outline-warning flex-fill"><i class="bi bi-pencil-square"></i></a>
              <a href="hapus.php?id=<?= $g['id'] ?>" class="btn btn-sm btn-outline-danger flex-fill"
                 onclick="return confirm('Yakin ingin menghapus foto ini?');"><i class="bi bi-trash"></i></a>
            </div>
          </div>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
<?php require_once __DIR__ . '/../includes/bottom.php'; ?>
