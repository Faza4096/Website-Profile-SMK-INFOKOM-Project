<?php
$pageTitle = 'Pengumuman - SMK INFOKOM BOGOR';
require_once __DIR__ . '/includes/header.php';

$pengumuman = $pdo->query("SELECT * FROM pengumuman ORDER BY tanggal DESC")->fetchAll();
?>

<div class="page-header">
  <div class="container">
    <div class="page-breadcrumb"><a href="index.php">Beranda</a> <i class="bi bi-chevron-right"></i> Pengumuman</div>
    <h1 class="page-title">Pengumuman</h1>
  </div>
</div>

<div class="container py-5">
  <div class="section-head">
    <span class="kicker">Informasi Resmi</span>
    <h2>Daftar Pengumuman</h2>
  </div>

  <div class="timeline">
    <?php if (count($pengumuman) === 0): ?>
      <div class="empty-state"><i class="bi bi-megaphone"></i>Belum ada pengumuman.</div>
    <?php endif; ?>
    <?php foreach ($pengumuman as $p): ?>
    <div class="timeline-item">
      <div class="timeline-date"><?= date('d M Y', strtotime($p['tanggal'])) ?></div>
      <h6 class="mt-1 mb-1"><?= htmlspecialchars($p['judul']) ?></h6>
      <p class="small text-muted-ink mb-0"><?= nl2br(htmlspecialchars($p['isi'])) ?></p>
    </div>
    <?php endforeach; ?>
  </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
