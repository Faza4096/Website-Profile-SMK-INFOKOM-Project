<?php
$pageTitle = 'Berita - SMK INFOKOM BOGOR';
$pageDesc = 'Berita terbaru SMK INFOKOM BOGOR: kabar sekolah, kegiatan akademik, dan pencapaian siswa.';
require_once __DIR__ . '/includes/header.php';

$perPage = 9;
$page = max(1, (int)($_GET['page'] ?? 1));
$offset = ($page - 1) * $perPage;

$total = $pdo->query("SELECT COUNT(*) FROM berita WHERE status='publish'")->fetchColumn();
$totalPage = max(1, ceil($total / $perPage));

$stmt = $pdo->prepare("SELECT * FROM berita WHERE status='publish' ORDER BY created_at DESC LIMIT :limit OFFSET :offset");
$stmt->bindValue(':limit', $perPage, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();
$beritaList = $stmt->fetchAll();
?>

<div class="page-header">
  <div class="container">
    <div class="page-breadcrumb"><a href="index.php">Beranda</a> <i class="bi bi-chevron-right"></i> Berita</div>
    <h1 class="page-title">Berita Terbaru</h1>
  </div>
</div>

<div class="container py-5">
  <div class="row g-4">
    <?php if (count($beritaList) === 0): ?>
      <div class="col-12"><div class="empty-state"><i class="bi bi-newspaper"></i>Belum ada berita yang dipublikasikan.</div></div>
    <?php endif; ?>

    <?php foreach ($beritaList as $b): ?>
    <div class="col-md-4">
      <div class="card-editorial h-100">
        <img src="uploads/berita/<?= htmlspecialchars($b['gambar'] ?: 'default.jpg') ?>" alt="<?= htmlspecialchars($b['judul']) ?>"
             onerror="this.src='https://placehold.co/400x220?text=Berita'">
        <div class="card-body d-flex flex-column">
          <p class="meta mb-2"><?= date('d M Y', strtotime($b['created_at'])) ?> &middot; <?= htmlspecialchars($b['penulis']) ?></p>
          <h6 class="mb-2"><a href="berita-detail.php?slug=<?= urlencode($b['slug']) ?>"><?= htmlspecialchars($b['judul']) ?></a></h6>
        </div>
      </div>
    </div>
    <?php endforeach; ?>
  </div>

  <?php if ($totalPage > 1): ?>
  <nav class="mt-5">
    <ul class="pagination justify-content-center">
      <?php for ($i = 1; $i <= $totalPage; $i++): ?>
        <li class="page-item <?= $i === $page ? 'active' : '' ?>">
          <a class="page-link" href="berita.php?page=<?= $i ?>"><?= $i ?></a>
        </li>
      <?php endfor; ?>
    </ul>
  </nav>
  <?php endif; ?>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
