<?php
$pageTitle = 'Galeri - SMK INFOKOM BOGOR';
require_once __DIR__ . '/includes/header.php';

$kategoriAktif = $_GET['kategori'] ?? 'Semua';
$kategoriList = $pdo->query("SELECT DISTINCT kategori FROM galeri ORDER BY kategori ASC")->fetchAll(PDO::FETCH_COLUMN);

if ($kategoriAktif !== 'Semua' && in_array($kategoriAktif, $kategoriList, true)) {
    $stmt = $pdo->prepare("SELECT * FROM galeri WHERE kategori = ? ORDER BY created_at DESC");
    $stmt->execute([$kategoriAktif]);
} else {
    $stmt = $pdo->query("SELECT * FROM galeri ORDER BY created_at DESC");
}
$galeri = $stmt->fetchAll();
?>

<div class="page-header">
  <div class="container">
    <div class="page-breadcrumb"><a href="index.php">Beranda</a> <i class="bi bi-chevron-right"></i> Galeri</div>
    <h1 class="page-title">Galeri Kegiatan</h1>
  </div>
</div>

<div class="container py-5">
  <div class="d-flex flex-wrap gap-2 justify-content-center mb-5">
    <a href="galeri.php?kategori=Semua" class="btn btn-sm <?= $kategoriAktif==='Semua' ? 'btn-ink' : 'btn-outline-ink' ?>">Semua</a>
    <?php foreach ($kategoriList as $k): ?>
      <a href="galeri.php?kategori=<?= urlencode($k) ?>" class="btn btn-sm <?= $kategoriAktif===$k ? 'btn-ink' : 'btn-outline-ink' ?>">
        <?= htmlspecialchars($k) ?>
      </a>
    <?php endforeach; ?>
  </div>

  <div class="row g-3">
    <?php if (count($galeri) === 0): ?>
      <div class="col-12"><div class="empty-state"><i class="bi bi-images"></i>Belum ada foto galeri untuk kategori ini.</div></div>
    <?php endif; ?>

    <?php foreach ($galeri as $g): ?>
    <div class="col-6 col-md-4 col-lg-3">
      <div class="gallery-thumb" data-bs-toggle="modal" data-bs-target="#modal-<?= $g['id'] ?>" style="cursor:pointer;">
        <img src="uploads/galeri/<?= htmlspecialchars($g['gambar']) ?>" alt="<?= htmlspecialchars($g['judul']) ?>"
             style="height:180px;" onerror="this.src='https://placehold.co/300x200?text=Galeri'">
      </div>
      <p class="small text-muted-ink mt-1 mb-0"><?= htmlspecialchars($g['judul']) ?></p>

      <div class="modal fade" id="modal-<?= $g['id'] ?>" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-lg">
          <div class="modal-content">
            <div class="modal-header">
              <h6 class="modal-title"><?= htmlspecialchars($g['judul']) ?></h6>
              <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-0">
              <img src="uploads/galeri/<?= htmlspecialchars($g['gambar']) ?>" class="img-fluid w-100" alt="">
            </div>
          </div>
        </div>
      </div>
    </div>
    <?php endforeach; ?>
  </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
