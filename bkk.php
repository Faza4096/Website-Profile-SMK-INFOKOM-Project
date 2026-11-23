<?php
$pageTitle = 'Program Mitra (BKK) - SMK INFOKOM BOGOR';
$pageDesc = 'Program Mitra SMK INFOKOM BOGOR: lowongan kerja, magang, dan PKL dari perusahaan mitra untuk alumni serta peserta didik.';
require_once __DIR__ . '/includes/header.php';

$lowonganAktif = $pdo->query("SELECT COUNT(*) FROM bkk_lowongan WHERE status='buka'")->fetchColumn();
$mitraCount    = $pdo->query("SELECT COUNT(*) FROM bkk_mitra")->fetchColumn();
$pelamarCount  = $pdo->query("SELECT COUNT(*) FROM bkk_pelamar")->fetchColumn();
$lowonganTerbaru = $pdo->query("SELECT * FROM bkk_lowongan WHERE status='buka' ORDER BY created_at DESC LIMIT 3")->fetchAll();
?>

<div class="page-header bkk-hero">
  <div class="container">
    <div class="page-breadcrumb"><a href="index.php">Beranda</a> <i class="bi bi-chevron-right"></i> Program Mitra</div>
    <h1 class="page-title">Bursa Kerja Khusus (BKK)</h1>
    <p class="page-title-sub mt-2">Jembatan antara alumni SMK INFOKOM BOGOR dengan dunia industri &mdash; informasi lowongan kerja, magang, dan perusahaan mitra.</p>
  </div>
</div>

<section class="py-5">
  <div class="container">
    <div class="row g-4 mb-5">
      <div class="col-md-4">
        <div class="info-tile tile-teal text-center">
          <div class="icon-circle mx-auto"><i class="bi bi-briefcase-fill"></i></div>
          <h3 class="mb-1"><?= (int)$lowonganAktif ?></h3>
          <p class="text-muted-ink small mb-0">Lowongan aktif saat ini</p>
        </div>
      </div>
      <div class="col-md-4">
        <div class="info-tile tile-amber text-center">
          <div class="icon-circle mx-auto"><i class="bi bi-buildings-fill"></i></div>
          <h3 class="mb-1"><?= (int)$mitraCount ?></h3>
          <p class="text-muted-ink small mb-0">Perusahaan mitra industri</p>
        </div>
      </div>
      <div class="col-md-4">
        <div class="info-tile text-center">
          <div class="icon-circle mx-auto"><i class="bi bi-people-fill"></i></div>
          <h3 class="mb-1"><?= (int)$pelamarCount ?></h3>
          <p class="text-muted-ink small mb-0">Alumni mendaftar via BKK</p>
        </div>
      </div>
    </div>

    <div class="row align-items-center g-5 mb-5">
      <div class="col-lg-7">
        <span class="kicker kicker-teal">Tentang BKK</span>
        <h2 class="mb-3">Apa itu Bursa Kerja Khusus?</h2>
        <p class="text-muted-ink">BKK (Bursa Kerja Khusus) adalah unit sekolah yang bertugas menghimpun dan menyalurkan informasi lowongan kerja, magang, dan kesempatan berkarier bagi alumni SMK INFOKOM BOGOR. Seluruh lowongan berasal dari perusahaan mitra yang telah bekerja sama dengan sekolah, sehingga informasi yang disebar lebih terpercaya dan relevan dengan kompetensi lulusan.</p>
        <p class="text-muted-ink mb-4">Alumni dapat mendaftar secara online melalui halaman <a href="bkk-daftar.php"><strong>Pendaftaran Alumni</strong></a> pada lowongan yang sedang dibuka.</p>
        <div class="d-flex flex-wrap gap-2">
          <a href="bkk-lowongan.php" class="btn btn-teal">Lihat Lowongan</a>
          <a href="bkk-mitra.php" class="btn btn-outline-teal">Perusahaan Mitra</a>
        </div>
      </div>
      <div class="col-lg-5">
        <div class="card-editorial p-4">
          <h6 class="mb-3"><i class="bi bi-signpost-split-fill me-2" style="color:var(--teal-deep);"></i>Alur Pendaftaran via BKK</h6>
          <div class="ppdb-steps">
            <div class="d-flex gap-3 mb-3">
              <span class="step-num">1</span>
              <div><strong>Pilih Lowongan</strong><p class="small text-muted-ink mb-0">Cek info lowongan yang masih buka.</p></div>
            </div>
            <div class="d-flex gap-3 mb-3">
              <span class="step-num">2</span>
              <div><strong>Isi Formulir</strong><p class="small text-muted-ink mb-0">Lengkapi data diri dan unggah CV.</p></div>
            </div>
            <div class="d-flex gap-3 mb-3">
              <span class="step-num">3</span>
              <div><strong>Verifikasi BKK</strong><p class="small text-muted-ink mb-0">BKK memproses dan meneruskan ke perusahaan.</p></div>
            </div>
            <div class="d-flex gap-3">
              <span class="step-num">4</span>
              <div><strong>Proses Seleksi</strong><p class="small text-muted-ink mb-0">Perusahaan menghubungi Anda untuk tes/wawancara.</p></div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Lowongan terbaru -->
<section class="py-5 bg-sky">
  <div class="container">
    <div class="section-head text-center">
      <span class="kicker kicker-teal">Terbaru</span>
      <h2>Lowongan Terbuka</h2>
      <p>Beberapa posisi yang sedang dibuka oleh perusahaan mitra.</p>
    </div>
    <?php if ($lowonganTerbaru): ?>
    <div class="row g-4">
      <?php foreach ($lowonganTerbaru as $job): ?>
      <div class="col-md-4">
        <div class="bkk-card">
          <div class="d-flex justify-content-between align-items-start mb-1">
            <div class="bkk-logo"><?= strtoupper(mb_substr($job['perusahaan'], 0, 2)) ?></div>
            <span class="badge-bkk"><?= htmlspecialchars($job['tipe_pekerjaan']) ?></span>
          </div>
          <h6 class="mt-2"><?= htmlspecialchars($job['posisi']) ?></h6>
          <p class="perusahaan mb-0"><?= htmlspecialchars($job['perusahaan']) ?></p>
          <div class="bkk-meta">
            <span><i class="bi bi-geo-alt"></i><?= htmlspecialchars($job['lokasi'] ?: '-') ?></span>
            <?php if ($job['gaji']): ?><span><i class="bi bi-cash-coin"></i><?= htmlspecialchars($job['gaji']) ?></span><?php endif; ?>
          </div>
          <p class="deskripsi mb-3"><?= htmlspecialchars(mb_strimwidth(strip_tags($job['deskripsi'] ?? ''), 0, 110, '...')) ?></p>
          <a href="bkk-daftar.php?lowongan=<?= $job['id'] ?>" class="btn btn-sm btn-teal align-self-start">Daftar Sekarang</a>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
    <div class="text-center mt-4">
      <a href="bkk-lowongan.php" class="btn btn-outline-teal">Lihat Semua Lowongan</a>
    </div>
    <?php else: ?>
      <div class="empty-state"><i class="bi bi-briefcase"></i>Belum ada lowongan yang dibuka.</div>
    <?php endif; ?>
  </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
