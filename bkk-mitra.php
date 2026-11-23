<?php
$pageTitle = 'Mitra Industri - Program Mitra SMK INFOKOM BOGOR';
$pageDesc = 'Daftar mitra industri SMK INFOKOM BOGOR yang bekerja sama dalam penyaluran lulusan, magang, dan PKL.';
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/migrasi-mitra.php';

mitra_migrasi_jenis($pdo);
$mitra = $pdo->query("SELECT * FROM bkk_mitra WHERE jenis='industri' ORDER BY urutan ASC, id ASC")->fetchAll();
?>

<div class="page-header bkk-hero">
  <div class="container">
    <div class="page-breadcrumb"><a href="index.php">Beranda</a> <i class="bi bi-chevron-right"></i> Program Mitra <i class="bi bi-chevron-right"></i> Mitra Industri</div>
    <h1 class="page-title">Mitra Industri</h1>
    <p class="page-title-sub mt-2">Perusahaan industri yang bekerja sama dengan BKK SMK INFOKOM BOGOR dalam penyaluran lulusan.</p>
  </div>
</div>

<div class="container py-5">
  <!-- Pindah antar dua halaman mitra: Industri & Universitas -->
  <div class="d-flex flex-wrap gap-2 mb-4">
    <a href="bkk-mitra.php" class="btn btn-sm btn-teal"><i class="bi bi-building me-1"></i> Mitra Industri</a>
    <a href="mitra-universitas.php" class="btn btn-sm btn-outline-teal"><i class="bi bi-bank me-1"></i> Mitra Universitas</a>
  </div>

  <?php if (count($mitra) === 0): ?>
    <div class="empty-state"><i class="bi bi-buildings"></i>Belum ada data mitra industri.</div>
  <?php endif; ?>

  <div class="row g-4">
    <?php foreach ($mitra as $idxMitra => $m): $midMitra = 'modalMitra' . (int)$m['id']; ?>
    <div class="col-md-6 col-lg-4">
      <div class="mitra-card mitra-card-link" role="button" tabindex="0"
           data-bs-toggle="modal" data-bs-target="#<?= $midMitra ?>"
           aria-label="Lihat detail <?= htmlspecialchars($m['nama_perusahaan']) ?>">
        <div class="mitra-logo">
          <?php if (!empty($m['logo'])): ?>
            <img src="uploads/bkk/<?= htmlspecialchars($m['logo']) ?>" alt="Logo <?= htmlspecialchars($m['nama_perusahaan']) ?>"
                 onerror="this.style.display='none'; this.parentElement.innerHTML='<?= htmlspecialchars(strtoupper(mb_substr($m['nama_perusahaan'], 0, 2))) ?>';">
          <?php else: ?>
            <?= strtoupper(mb_substr($m['nama_perusahaan'], 0, 2)) ?>
          <?php endif; ?>
        </div>
        <h6 class="mb-1"><?= htmlspecialchars($m['nama_perusahaan']) ?></h6>
        <p class="bidang mb-0"><?= htmlspecialchars($m['bidang'] ?: 'Mitra Industri') ?></p>
        <span class="mitra-cta">Lihat detail <i class="bi bi-arrow-right"></i></span>
      </div>
    </div>

    <!-- Modal detail mitra -->
    <div class="modal fade" id="<?= $midMitra ?>" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <h6 class="modal-title"><i class="bi bi-building me-2"></i><?= htmlspecialchars($m['nama_perusahaan']) ?></h6>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
          </div>
          <div class="modal-body p-4">
            <div class="row align-items-center g-4">
              <div class="col-md-4 text-center">
                <div class="mitra-modal-logo">
                  <?php if (!empty($m['logo'])): ?>
                    <img src="uploads/bkk/<?= htmlspecialchars($m['logo']) ?>" alt="Logo <?= htmlspecialchars($m['nama_perusahaan']) ?>"
                         onerror="this.style.display='none'; this.parentElement.innerHTML='<?= htmlspecialchars(strtoupper(mb_substr($m['nama_perusahaan'], 0, 2))) ?>';">
                  <?php else: ?>
                    <?= strtoupper(mb_substr($m['nama_perusahaan'], 0, 2)) ?>
                  <?php endif; ?>
                </div>
              </div>
              <div class="col-md-8">
                <?php if (!empty($m['bidang'])): ?>
                  <span class="badge-bkk mb-2 d-inline-block"><?= htmlspecialchars($m['bidang']) ?></span>
                <?php endif; ?>
                <?php if (!empty($m['alamat'])): ?>
                  <p class="small text-muted-ink mb-2"><i class="bi bi-geo-alt-fill me-2" style="color:var(--teal-deep);"></i><?= htmlspecialchars($m['alamat']) ?></p>
                <?php endif; ?>
                <?php if (!empty($m['keterangan'])): ?>
                  <p class="small text-muted-ink mb-0" style="line-height:1.7;"><?= nl2br(htmlspecialchars($m['keterangan'])) ?></p>
                <?php else: ?>
                  <p class="small text-muted-ink mb-0">Mitra industri BKK SMK INFOKOM BOGOR dalam penyaluran lulusan, magang, dan PKL.</p>
                <?php endif; ?>
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <a href="bkk-lowongan.php" class="btn btn-sm btn-teal"><i class="bi bi-briefcase me-1"></i> Lowongan dari Mitra</a>
            <button type="button" class="btn btn-sm btn-outline-ink" data-bs-dismiss="modal">Tutup</button>
          </div>
        </div>
      </div>
    </div>
    <?php endforeach; ?>
  </div>

  <div class="text-center mt-5 pt-2">
    <p class="text-muted-ink mb-2">Perusahaan Anda tertarik bekerja sama dengan sekolah kami?</p>
    <a href="kontak.php" class="btn btn-teal">Hubungi Sekolah</a>
  </div>
</div>

<script>
// Kartu mitra role="button": dukung Enter/Spasi untuk aksesibilitas keyboard
(function () {
  document.querySelectorAll('.mitra-card-link').forEach(function (kartu) {
    kartu.addEventListener('keydown', function (e) {
      if (e.key === 'Enter' || e.key === ' ' || e.key === 'Spacebar') {
        e.preventDefault();
        kartu.click();
      }
    });
  });
})();
</script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
