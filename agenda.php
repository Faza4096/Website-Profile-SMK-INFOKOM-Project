<?php
$pageTitle = 'Agenda - SMK INFOKOM BOGOR';
require_once __DIR__ . '/includes/header.php';

$agenda = $pdo->query("SELECT * FROM agenda ORDER BY tanggal ASC")->fetchAll();
?>

<div class="page-header">
  <div class="container">
    <div class="page-breadcrumb"><a href="index.php">Beranda</a> <i class="bi bi-chevron-right"></i> Agenda</div>
    <h1 class="page-title">Agenda Sekolah</h1>
  </div>
</div>

<div class="container py-5">
  <div class="section-head">
    <span class="kicker">Jadwal Kegiatan</span>
    <h2>Agenda Sekolah</h2>
  </div>

  <div class="timeline">
    <?php if (count($agenda) === 0): ?>
      <div class="empty-state"><i class="bi bi-calendar-event"></i>Belum ada agenda.</div>
    <?php endif; ?>
    <?php foreach ($agenda as $a): ?>
    <div class="timeline-item">
      <div class="timeline-date"><?= date('d M Y', strtotime($a['tanggal'])) ?></div>
      <h6 class="mt-1 mb-1"><?= htmlspecialchars($a['judul']) ?></h6>
      <p class="small text-muted-ink mb-0"><?= nl2br(htmlspecialchars($a['isi'])) ?></p>
    </div>
    <?php endforeach; ?>
  </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
