<?php
$pageTitle = 'Kalender Pendidikan - SMK INFOKOM BOGOR';
$pageDesc = 'Kalender pendidikan SMK INFOKOM BOGOR tahun pelajaran 2026/2027: jadwal ujian, libur, MPLS, PPDB, dan agenda penting sekolah.';
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/kalender.php';

// Kalender Pendidikan — tabel dibuat & diisi contoh otomatis saat pertama kali dibuka
kalender_pendidikan_siapkan($pdo);
try {
    $kalender = $pdo->query("SELECT * FROM kalender_pendidikan ORDER BY tanggal_mulai ASC, id ASC")->fetchAll();
} catch (Throwable $e) {
    $kalender = [];
}
$badgeKalender = ['Kegiatan'=>'badge-level','Akademik'=>'badge-level','Ujian'=>'badge-kuning','Libur'=>'badge-abu','PPDB'=>'badge-signal'];
$tahunPelajaranAktif = $kalender ? $kalender[0]['tahun_pelajaran'] : '2026/2027';
?>

<div class="page-header">
  <div class="container">
    <div class="page-breadcrumb"><a href="index.php">Beranda</a> <i class="bi bi-chevron-right"></i> <a href="akademik.php">Akademik</a> <i class="bi bi-chevron-right"></i> Kalender Pendidikan</div>
    <h1 class="page-title">Kalender Pendidikan</h1>
  </div>
</div>

<div class="container py-5">
  <div class="section-head">
    <span class="kicker">Jadwal Akademik</span>
    <h2>Kalender Pendidikan <span class="highlight" style="color:var(--blue);"><?= htmlspecialchars($tahunPelajaranAktif) ?></span></h2>
    <p>Rangkaian kegiatan akademik tahun pelajaran berjalan — ujian, libur, dan agenda penting sekolah.</p>
  </div>

  <?php if (!$kalender): ?>
    <div class="empty-state"><i class="bi bi-calendar3"></i>Kalender pendidikan belum tersedia.</div>
  <?php else: ?>
  <div class="card-editorial p-4">
    <div class="table-responsive">
      <table class="table align-middle mb-0">
        <thead>
          <tr>
            <th style="width:210px;">Tanggal</th>
            <th>Kegiatan</th>
            <th style="width:120px;">Kategori</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($kalender as $k): ?>
          <tr>
            <td class="small">
              <?php $tanggalSelesai = $k['tanggal_selesai'] ? date('d M Y', strtotime($k['tanggal_selesai'])) : null; ?>
              <i class="bi bi-calendar-event me-1" style="color:var(--blue);"></i>
              <?= date('d M Y', strtotime($k['tanggal_mulai'])) ?>
              <?= $tanggalSelesai ? '&ndash; ' . $tanggalSelesai : '' ?>
            </td>
            <td>
              <span class="fw-semibold"><?= htmlspecialchars($k['kegiatan']) ?></span>
              <?php if (!empty($k['keterangan'])): ?>
                <p class="small text-muted-ink mb-0"><?= htmlspecialchars($k['keterangan']) ?></p>
              <?php endif; ?>
            </td>
            <td><span class="badge <?= $badgeKalender[$k['kategori']] ?? 'badge-level' ?>"><?= htmlspecialchars($k['kategori']) ?></span></td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>
  <?php endif; ?>

  <div class="text-center mt-5 pt-2">
    <a href="akademik.php" class="btn btn-outline-ink"><i class="bi bi-mortarboard me-1"></i> Lihat Program Keahlian</a>
  </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
