<?php
require_once __DIR__ . '/includes/koneksi.php';

$slug = $_GET['slug'] ?? '';

$stmt = $pdo->prepare("SELECT * FROM berita WHERE slug = ? AND status = 'publish' LIMIT 1");
$stmt->execute([$slug]);
$berita = $stmt->fetch();

if (!$berita) {
    header('Location: berita.php');
    exit;
}

$pageTitle = $berita['judul'] . ' - SMK INFOKOM BOGOR';

$stmt2 = $pdo->prepare("SELECT * FROM berita WHERE status='publish' AND id != ? ORDER BY created_at DESC LIMIT 3");
$stmt2->execute([$berita['id']]);
$beritaLain = $stmt2->fetchAll();

require_once __DIR__ . '/includes/header.php';
?>

<div class="page-header">
  <div class="container">
    <div class="page-breadcrumb"><a href="index.php">Beranda</a> <i class="bi bi-chevron-right"></i> <a href="berita.php">Berita</a> <i class="bi bi-chevron-right"></i> <?= htmlspecialchars(mb_strimwidth($berita['judul'], 0, 40, '...')) ?></div>
    <h1 class="page-title">Detail Berita</h1>
  </div>
</div>

<div class="container py-5">
  <div class="row g-4">
    <div class="col-lg-8">
      <article class="card-editorial p-4">
        <img src="uploads/berita/<?= htmlspecialchars($berita['gambar'] ?: 'default.jpg') ?>"
             class="img-fluid rounded mb-4" alt="<?= htmlspecialchars($berita['judul']) ?>"
             onerror="this.src='https://placehold.co/800x400?text=Berita'">

        <h2 class="mb-2"><?= htmlspecialchars($berita['judul']) ?></h2>
        <p class="meta mb-4"><?= date('d M Y', strtotime($berita['created_at'])) ?> &middot; <?= htmlspecialchars($berita['penulis']) ?></p>
        <hr class="section-rule">

        <div class="fs-6" style="white-space:pre-line; line-height:1.8;">
          <?= nl2br(htmlspecialchars($berita['konten'])) ?>
        </div>

        <a href="berita.php" class="btn btn-outline-ink mt-4"><i class="bi bi-arrow-left"></i> Kembali ke Berita</a>
      </article>
    </div>

    <div class="col-lg-4">
      <h5 class="mb-3">Berita Lainnya</h5>
      <?php if (count($beritaLain) === 0): ?>
        <p class="text-muted-ink small">Belum ada berita lain.</p>
      <?php endif; ?>
      <?php foreach ($beritaLain as $b): ?>
      <!-- d-block: pembungkus <a> jadi block agar height:100% kartu tidak menghitung tinggi kolom (kartu jadi setinggi artikel) -->
      <a href="berita-detail.php?slug=<?= urlencode($b['slug']) ?>" class="text-decoration-none text-ink d-block">
        <!-- d-flex: gambar thumbnail sejajar di samping teks (flex-row saja tidak menyetel display) -->
        <div class="card-editorial mb-3 d-flex flex-row align-items-stretch">
          <img src="uploads/berita/<?= htmlspecialchars($b['gambar'] ?: 'default.jpg') ?>"
               style="width:100px;height:80px;object-fit:cover;flex-shrink:0;" alt=""
               onerror="this.src='https://placehold.co/100x80'">
          <div class="card-body py-2 px-3">
            <p class="small fw-semibold mb-1"><?= htmlspecialchars(mb_strimwidth($b['judul'], 0, 60, '...')) ?></p>
            <p class="small text-muted-ink mb-0"><?= date('d M Y', strtotime($b['created_at'])) ?></p>
          </div>
        </div>
      </a>
      <?php endforeach; ?>
    </div>
  </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
