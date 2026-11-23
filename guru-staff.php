<?php
$pageTitle = 'Guru & Staff - SMK INFOKOM BOGOR';
require_once __DIR__ . '/includes/header.php';

$guru = $pdo->query("SELECT * FROM guru_staff ORDER BY urutan ASC, id ASC")->fetchAll();
?>

<div class="page-header">
  <div class="container">
    <div class="page-breadcrumb"><a href="index.php">Beranda</a> <i class="bi bi-chevron-right"></i> Guru &amp; Staff</div>
    <h1 class="page-title">Guru &amp; Staff</h1>
  </div>
</div>

<div class="container py-5">
  <div class="section-head">
    <span class="kicker">Tenaga Pendidik &amp; Kependidikan</span>
    <h2>Tim SMK INFOKOM BOGOR</h2>
    <p>Diampu oleh guru dan staff berpengalaman untuk mendukung proses belajar mengajar.</p>
  </div>

  <div class="row g-4">
    <?php if (count($guru) === 0): ?>
      <div class="col-12"><div class="empty-state"><i class="bi bi-people"></i>Data guru &amp; staff belum tersedia.</div></div>
    <?php endif; ?>
    <?php foreach ($guru as $g): ?>
    <div class="col-6 col-md-3">
      <div class="people-card">
        <div class="person-frame person-round">
          <img src="uploads/guru/<?= htmlspecialchars($g['foto'] ?: 'default.jpg') ?>" class="people-photo" alt="<?= htmlspecialchars($g['nama']) ?>"
               onerror="this.src='https://placehold.co/300x400?text=Foto'">
        </div>
        <p class="nama mb-0"><?= htmlspecialchars($g['nama']) ?></p>
        <p class="jabatan mb-0"><?= htmlspecialchars($g['jabatan']) ?></p>
        <?php if (!empty($g['bidang'])): ?><p class="jabatan small"><?= htmlspecialchars($g['bidang']) ?></p><?php endif; ?>
      </div>
    </div>
    <?php endforeach; ?>
  </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
