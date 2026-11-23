<?php
$pageTitle = 'Prestasi - SMK INFOKOM BOGOR';
require_once __DIR__ . '/includes/header.php';

$prestasi = $pdo->query("SELECT * FROM prestasi ORDER BY tahun DESC, created_at DESC")->fetchAll();
?>

<div class="page-header">
  <div class="container">
    <div class="page-breadcrumb"><a href="index.php">Beranda</a> <i class="bi bi-chevron-right"></i> Prestasi</div>
    <h1 class="page-title">Prestasi Siswa &amp; Sekolah</h1>
  </div>
</div>

<div class="container py-5">
  <div class="section-head">
    <span class="kicker">Pencapaian</span>
    <h2>Prestasi Siswa &amp; Sekolah</h2>
  </div>

  <div class="row g-4">
    <?php if (count($prestasi) === 0): ?>
      <div class="col-12"><div class="empty-state"><i class="bi bi-trophy"></i>Belum ada data prestasi.</div></div>
    <?php endif; ?>
    <?php foreach ($prestasi as $p): ?>
    <div class="col-md-6 col-lg-4">
      <div class="card-editorial">
        <img src="uploads/prestasi/<?= htmlspecialchars($p['gambar'] ?: 'default.jpg') ?>" alt="<?= htmlspecialchars($p['judul']) ?>"
             onerror="this.src='https://placehold.co/400x220?text=Prestasi'">
        <div class="card-body">
          <div class="d-flex gap-2 mb-2">
            <span class="badge badge-level"><?= htmlspecialchars($p['tingkat']) ?></span>
            <span class="badge badge-year"><?= htmlspecialchars($p['tahun']) ?></span>
          </div>
          <h6 class="mb-2"><?= htmlspecialchars($p['judul']) ?></h6>
          <p class="small text-muted-ink mb-0"><?= htmlspecialchars($p['deskripsi']) ?></p>
        </div>
      </div>
    </div>
    <?php endforeach; ?>
  </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
