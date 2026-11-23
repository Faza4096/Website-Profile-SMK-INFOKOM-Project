<?php
$pageTitle = 'Lowongan Kerja - Program Mitra SMK INFOKOM BOGOR';
$pageDesc = 'Info lowongan kerja, magang, dan PKL dari perusahaan mitra BKK SMK INFOKOM BOGOR untuk alumni dan siswa.';
require_once __DIR__ . '/includes/header.php';

$tipeAktif = $_GET['tipe'] ?? 'Semua';
$daftarTipe = ['Full-time', 'Part-time', 'Kontrak', 'Magang', 'PKL'];

if (in_array($tipeAktif, $daftarTipe, true)) {
    $stmt = $pdo->prepare("SELECT * FROM bkk_lowongan WHERE status='buka' AND tipe_pekerjaan = ? ORDER BY created_at DESC");
    $stmt->execute([$tipeAktif]);
} else {
    $stmt = $pdo->query("SELECT * FROM bkk_lowongan WHERE status='buka' ORDER BY created_at DESC");
}
$lowongan = $stmt->fetchAll();

$hariIni = date('Y-m-d');
?>

<div class="page-header bkk-hero">
  <div class="container">
    <div class="page-breadcrumb"><a href="index.php">Beranda</a> <i class="bi bi-chevron-right"></i> Program Mitra <i class="bi bi-chevron-right"></i> Lowongan</div>
    <h1 class="page-title">Info Lowongan Kerja</h1>
    <p class="page-title-sub mt-2">Lowongan kerja, magang, dan PKL dari perusahaan mitra BKK SMK INFOKOM BOGOR.</p>
  </div>
</div>

<div class="container py-5">
  <div class="d-flex flex-wrap gap-2 justify-content-center mb-5">
    <a href="bkk-lowongan.php" class="btn btn-sm <?= $tipeAktif==='Semua' ? 'btn-teal' : 'btn-outline-teal' ?>">Semua</a>
    <?php foreach ($daftarTipe as $t): ?>
      <a href="bkk-lowongan.php?tipe=<?= urlencode($t) ?>" class="btn btn-sm <?= $tipeAktif===$t ? 'btn-teal' : 'btn-outline-teal' ?>"><?= $t ?></a>
    <?php endforeach; ?>
  </div>

  <div class="row g-4">
    <?php if (count($lowongan) === 0): ?>
      <div class="col-12"><div class="empty-state"><i class="bi bi-briefcase"></i>Belum ada lowongan aktif untuk kategori ini.</div></div>
    <?php endif; ?>

    <?php foreach ($lowongan as $job): ?>
    <div class="col-md-6">
      <div class="bkk-card">
        <div class="d-flex justify-content-between align-items-start mb-1">
          <div class="d-flex align-items-center gap-3">
            <div class="bkk-logo"><?= strtoupper(mb_substr($job['perusahaan'], 0, 2)) ?></div>
            <div>
              <h6 class="mb-0"><?= htmlspecialchars($job['posisi']) ?></h6>
              <p class="perusahaan mb-0"><?= htmlspecialchars($job['perusahaan']) ?></p>
            </div>
          </div>
          <span class="badge-bkk"><?= htmlspecialchars($job['tipe_pekerjaan']) ?></span>
        </div>

        <div class="bkk-meta">
          <span><i class="bi bi-geo-alt"></i><?= htmlspecialchars($job['lokasi'] ?: '-') ?></span>
          <?php if ($job['gaji']): ?><span><i class="bi bi-cash-coin"></i><?= htmlspecialchars($job['gaji']) ?></span><?php endif; ?>
          <?php if ($job['kuota']): ?><span><i class="bi bi-people"></i>Kuota <?= (int)$job['kuota'] ?> orang</span><?php endif; ?>
        </div>

        <?php if ($job['deskripsi']): ?>
          <p class="deskripsi mb-2"><?= nl2br(htmlspecialchars(mb_strimwidth($job['deskripsi'], 0, 220, '...'))) ?></p>
        <?php else: ?>
          <p class="deskripsi mb-2">Deskripsi belum tersedia.</p>
        <?php endif; ?>

        <?php if ($job['kualifikasi']): ?>
          <p class="small text-muted-ink mb-2"><strong>Kualifikasi:</strong> <?= htmlspecialchars(mb_strimwidth($job['kualifikasi'], 0, 160, '...')) ?></p>
        <?php endif; ?>

        <div class="d-flex justify-content-between align-items-center mt-auto pt-2">
          <?php if ($job['batas_daftar']): ?>
            <?php $lewat = $job['batas_daftar'] < $hariIni; ?>
            <span class="bkk-deadline <?= $lewat ? 'terlambat' : '' ?>">
              <i class="bi bi-calendar-x"></i> <?= $lewat ? 'Ditutup' : 'Batas daftar' ?>: <?= date('d M Y', strtotime($job['batas_daftar'])) ?>
            </span>
          <?php else: ?>
            <span class="bkk-deadline"><i class="bi bi-calendar-check"></i> Dibuka sampai kuota penuh</span>
          <?php endif; ?>
          <a href="bkk-daftar.php?lowongan=<?= $job['id'] ?>" class="btn btn-sm btn-teal">Daftar &rarr;</a>
        </div>
      </div>
    </div>
    <?php endforeach; ?>
  </div>

  <div class="text-center mt-5">
    <p class="text-muted-ink mb-2">Belum menemukan lowongan yang cocok?</p>
    <a href="bkk-mitra.php" class="btn btn-outline-teal">Lihat Perusahaan Mitra</a>
  </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
