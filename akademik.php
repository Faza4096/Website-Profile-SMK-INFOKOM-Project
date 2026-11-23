<?php
$pageTitle = 'Akademik - SMK INFOKOM BOGOR';
$pageDesc = 'Program keahlian (jurusan) yang tersedia di SMK INFOKOM BOGOR beserta deskripsi kompetensi keahliannya.';
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/migrasi-jurusan.php';

$jurusan = $pdo->query("SELECT * FROM jurusan ORDER BY id ASC")->fetchAll();
?>

<div class="page-header">
  <div class="container">
    <div class="page-breadcrumb"><a href="index.php">Beranda</a> <i class="bi bi-chevron-right"></i> Akademik</div>
    <h1 class="page-title">Akademik &mdash; Program Keahlian</h1>
  </div>
</div>

<div class="container py-5">
    <div class="section-head">
      <span class="kicker">Program Keahlian</span>
      <h2>Jurusan yang Tersedia</h2>
    </div>

  <div class="row g-4">
  <?php if (count($jurusan) === 0): ?>
    <div class="col-12"><div class="empty-state"><i class="bi bi-mortarboard"></i>Data jurusan belum tersedia.</div></div>
  <?php endif; ?>

    <?php foreach ($jurusan as $j): ?>
    <?php
      $visi   = trim((string)($j['visi'] ?? ''));
      $misi   = trim((string)($j['misi'] ?? ''));
      $tujuan = trim((string)($j['tujuan'] ?? ''));
      $adaVmt = ($visi !== '' || $misi !== '' || $tujuan !== '');
      // Poin misi: satu baris = satu poin
      $misiBaris = array_values(array_filter(array_map('trim', preg_split('/\R/', $misi))));
    ?>
    <div class="col-md-6 col-lg-3">
      <!-- Kartu = kolom flex + body flex-grow-1 agar link "Selengkapnya" selalu sejajar di dasar kartu -->
      <div class="card-editorial h-100 d-flex flex-column">
        <img src="uploads/jurusan/<?= htmlspecialchars($j['gambar'] ?: 'default.jpg') ?>" alt="<?= htmlspecialchars($j['nama_jurusan']) ?>"
             onerror="this.src='https://placehold.co/400x220?text=<?= urlencode($j['singkatan'] ?: 'Jurusan') ?>'">
        <div class="card-body d-flex flex-column flex-grow-1">
          <span class="badge badge-level mb-2"><?= htmlspecialchars($j['singkatan']) ?></span>
          <h6 class="mb-2"><?= htmlspecialchars($j['nama_jurusan']) ?></h6>
          <p class="small text-muted-ink mb-0"><?= htmlspecialchars($j['deskripsi']) ?></p>

          <?php if ($adaVmt): ?>
          <!-- Visi, misi & tujuan disembunyikan di kartu — tampil lengkap di modal setelah diklik -->
          <a href="#" class="jurusan-more mt-auto pt-3" data-bs-toggle="modal" data-bs-target="#jurusanModal<?= (int)$j['id'] ?>">
            Selengkapnya <i class="bi bi-arrow-right"></i>
          </a>
          <?php endif; ?>
        </div>
      </div>
    </div>

    <?php if ($adaVmt): ?>
    <!-- Modal detail visi, misi & tujuan jurusan -->
    <div class="modal fade" id="jurusanModal<?= (int)$j['id'] ?>" tabindex="-1" aria-labelledby="jurusanModalLabel<?= (int)$j['id'] ?>" aria-hidden="true">
      <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content jurusan-modal">
          <div class="modal-header">
            <div class="d-flex align-items-center gap-3">
              <img src="uploads/jurusan/<?= htmlspecialchars($j['gambar'] ?: 'default.jpg') ?>" alt="<?= htmlspecialchars($j['singkatan']) ?>"
                   onerror="this.style.display='none'" class="jurusan-modal-logo">
              <div>
                <span class="badge badge-level mb-1"><?= htmlspecialchars($j['singkatan']) ?></span>
                <h5 class="modal-title mb-0" id="jurusanModalLabel<?= (int)$j['id'] ?>"><?= htmlspecialchars($j['nama_jurusan']) ?></h5>
              </div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
          </div>
          <div class="modal-body">
            <p class="small text-muted-ink"><?= htmlspecialchars($j['deskripsi']) ?></p>

            <?php if ($visi !== ''): ?>
            <div class="jurusan-modal-section">
              <h6><i class="bi bi-bullseye"></i> Visi</h6>
              <p class="mb-0"><?= nl2br(htmlspecialchars($visi)) ?></p>
            </div>
            <?php endif; ?>

            <?php if ($misi !== ''): ?>
            <div class="jurusan-modal-section">
              <h6><i class="bi bi-list-check"></i> Misi</h6>
              <?php if (count($misiBaris) > 1): ?>
              <ol class="mb-0 ps-3">
                <?php foreach ($misiBaris as $poin): ?>
                <li><?= htmlspecialchars(preg_replace('/^\d+\.\s*/u', '', $poin)) ?></li>
                <?php endforeach; ?>
              </ol>
              <?php else: ?>
              <p class="mb-0"><?= nl2br(htmlspecialchars($misi)) ?></p>
              <?php endif; ?>
            </div>
            <?php endif; ?>

            <?php if ($tujuan !== ''): ?>
            <div class="jurusan-modal-section mb-0">
              <h6><i class="bi bi-flag"></i> Tujuan</h6>
              <p class="mb-0"><?= nl2br(htmlspecialchars($tujuan)) ?></p>
            </div>
            <?php endif; ?>
          </div>
        </div>
      </div>
    </div>
    <?php endif; ?>
    <?php endforeach; ?>
  </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
