<?php
$pageTitle = 'Ekstrakurikuler - SMK INFOKOM BOGOR';
$pageDesc = 'Kegiatan ekstrakurikuler SMK INFOKOM BOGOR: pramuka, PMR, basket, futsal, seni tari, dan karya ilmiah remaja beserta jadwal dan pembina.';
require_once __DIR__ . '/includes/header.php';

try {
    $ekstra = $pdo->query("SELECT * FROM ekstrakurikuler ORDER BY urutan ASC, id ASC")->fetchAll();
} catch (Throwable $e) {
    $ekstra = []; // tabel belum diimpor — halaman tetap tampil dengan empty-state
}
?>

<div class="page-header">
  <div class="container">
    <div class="page-breadcrumb"><a href="index.php">Beranda</a> <i class="bi bi-chevron-right"></i> Ekstrakurikuler</div>
    <h1 class="page-title">Kegiatan Ekstrakurikuler</h1>
    <p class="page-title-sub mt-2">Wadah pengembangan bakat, minat, dan karakter siswa di luar jam pelajaran.</p>
  </div>
</div>

<div class="container py-5">
  <div class="section-head">
    <span class="kicker">Kesiswaan</span>
    <h2>Pilihan Kegiatan Ekstrakurikuler</h2>
    <p>Siswa dapat memilih kegiatan sesuai minat untuk mengasah kemampuan non-akademik dan memperluas jejaring.</p>
  </div>

  <div class="row g-4">
    <?php if (count($ekstra) === 0): ?>
      <div class="col-12"><div class="empty-state"><i class="bi bi-people"></i>Belum ada data ekstrakurikuler. Impor <code>db_ekstrakurikuler.sql</code> terlebih dahulu.</div></div>
    <?php endif; ?>
    <?php foreach ($ekstra as $e): ?>
    <div class="col-md-6 col-lg-4">
      <div class="card-editorial h-100">
        <?php if (!empty($e['gambar'])): ?>
          <img src="uploads/ekstrakurikuler/<?= htmlspecialchars($e['gambar']) ?>" alt="<?= htmlspecialchars($e['nama']) ?>"
               onerror="this.src='https://placehold.co/400x220?text=Ekstrakurikuler'">
        <?php endif; ?>
        <div class="card-body d-flex flex-column">
          <div class="d-flex gap-2 mb-2">
            <span class="badge badge-level"><?= htmlspecialchars($e['jadwal'] ?: 'Jadwal menyusul') ?></span>
          </div>
          <h6 class="mb-2"><?= htmlspecialchars($e['nama']) ?></h6>
          <?php if (!empty($e['pembina'])): ?>
            <p class="meta mb-2"><i class="bi bi-person-badge me-1"></i><?= htmlspecialchars($e['pembina']) ?></p>
          <?php endif; ?>
          <p class="small text-muted-ink mb-0"><?= htmlspecialchars($e['deskripsi'] ?? '') ?></p>
        </div>
      </div>
    </div>
    <?php endforeach; ?>
  </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
