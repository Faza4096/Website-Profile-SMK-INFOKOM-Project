<?php
$pageTitle = 'Beranda - SMK INFOKOM BOGOR';
$pageDesc = 'SMK INFOKOM BOGOR: sekolah kejuruan bidang TIK di Kota Bogor. Informasi jurusan, berita sekolah, PPDB online, dan Program Mitra magang/PKL untuk alumni.';
require_once __DIR__ . '/includes/header.php';

// Mitra usaha untuk running text (text runner) di bawah hero
$mitraRun = $pdo->query("SELECT * FROM bkk_mitra ORDER BY urutan ASC, id ASC")->fetchAll();

$stmt = $pdo->prepare("SELECT * FROM profil WHERE jenis = ? LIMIT 1");
$stmt->execute(['sejarah']);
$tentang = $stmt->fetch();

// Latar hero berganti-ganti — diatur di admin: Konten Profil -> Latar Belakang Beranda
try {
    $heroBgs = $pdo->query("SELECT gambar FROM hero_bg ORDER BY urutan ASC, id ASC")->fetchAll();
} catch (Throwable $e) {
    $heroBgs = []; // tabel belum ada — pakai foto sekolah bawaan
}

$berita = $pdo->query("SELECT * FROM berita WHERE status='publish' ORDER BY created_at DESC LIMIT 3")->fetchAll();
$galeri = $pdo->query("SELECT * FROM galeri ORDER BY created_at DESC LIMIT 6")->fetchAll();

// Sambutan kepala sekolah (ditampilkan di bawah "Tentang Kami")
$stmtSambutan = $pdo->prepare("SELECT * FROM profil WHERE jenis = ? LIMIT 1");
$stmtSambutan->execute(['sambutan']);
$sambutan = $stmtSambutan->fetch();
$kepsek = $pdo->query("SELECT * FROM struktur_organisasi WHERE jabatan LIKE '%Kepala Sekolah%' AND jabatan NOT LIKE '%Wakil%' ORDER BY urutan ASC, id ASC LIMIT 1")->fetch();
$guru       = $pdo->query("SELECT * FROM guru_staff ORDER BY urutan ASC, id ASC LIMIT 4")->fetchAll();
$bkkJobs    = $pdo->query("SELECT * FROM bkk_lowongan WHERE status='buka' ORDER BY created_at DESC LIMIT 3")->fetchAll();
?>

<!-- ============ HERO (dengan gambar sekolah sebagai latar) ============ -->
<section class="hero-school">
  <?php if ($heroBgs): ?>
  <!-- Slideshow latar dari admin: crossfade tiap 6 detik (lihat skrip di bawah) -->
  <?php foreach ($heroBgs as $iHero => $heroBg): ?>
  <img class="hero-school-bg hero-school-slide<?= $iHero === 0 ? ' is-active' : '' ?>"
       src="uploads/hero/<?= htmlspecialchars($heroBg['gambar']) ?>" alt="" aria-hidden="true"
       onerror="this.remove()">
  <?php endforeach; ?>
  <?php else: ?>
  <!-- Gambar sekolah di belakang halaman depan; fallback ke foto lain, lalu ke gradient jika tidak ada -->
  <img class="hero-school-bg" src="assets/img/sekolah.jpg" alt="" aria-hidden="true"
       onerror="if(!this.dataset.fb){this.dataset.fb='1';this.src='assets/img/tentang-kami.jpeg';}else{this.remove();}">
  <?php endif; ?>
  <div class="hero-school-shade"></div>
  <div class="container">
    <div class="row align-items-center g-5">
      <div class="col-lg-8">
        <span class="hero-kicker"><i class="bi bi-patch-check-fill"></i> SMK INFOKOM BOGOR&middot; Kota Bogor</span>
        <h1>Belajar teknologi, <span class="highlight">siap kerja</span> sejak hari pertama.</h1>
        <p class="lead-copy mt-3">SMK INFOKOM BOGOR membentuk siswa yang kompeten di bidang informatika dan komunikasi, berkarakter, dan siap bersaing di dunia industri maupun perguruan tinggi.</p>
        <div class="d-flex flex-wrap gap-2 mt-4">
          <a href="ppdb.php" class="btn btn-signal px-4">Daftar PPDB</a>
          <a href="profil.php" class="btn btn-outline-light px-4">Profil Sekolah</a>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ============ SAMBUTAN KEPALA SEKOLAH ============ -->
<?php if ($sambutan || $kepsek): ?>
<section class="py-5">
  <div class="container">
    <div class="row align-items-center g-5">
      <div class="col-md-4 col-lg-3">
        <?php
        // Foto sambutan dari profil, fallback ke foto kepsek di struktur organisasi
        $fotoSambutan = !empty($sambutan['gambar'])
            ? 'uploads/profil/' . $sambutan['gambar']
            : (!empty($kepsek['foto']) ? 'uploads/struktur/' . $kepsek['foto'] : '');
        ?>
        <?php if ($fotoSambutan): ?>
        <!-- Foto orang (kepsek): bingkai bulat, sama dengan struktur & guru/staf -->
        <div class="person-frame person-round person-lg">
          <img src="<?= htmlspecialchars($fotoSambutan) ?>" class="people-photo" alt="Kepala Sekolah"
               onerror="this.src='https://placehold.co/300x400?text=Kepala+Sekolah'">
        </div>
        <?php endif; ?>
      </div>
      <div class="col-md-8 col-lg-9">
        <span class="kicker">Sambutan</span>
        <h2 class="mb-3">Sambutan Kepala Sekolah</h2>
        <p class="text-muted-ink">
          <?= nl2br(htmlspecialchars(mb_strimwidth(trim($sambutan['isi'] ?? ''), 0, 380, '...'))) ?>
        </p>
        <?php if (!empty($kepsek['nama'])): ?>
        <p class="fw-bold mb-0"><?= htmlspecialchars($kepsek['nama']) ?></p>
        <p class="small text-muted-ink mb-2">Kepala Sekolah &middot; SMK INFOKOM BOGOR</p>
        <?php endif; ?>
        <a href="profil.php?jenis=sambutan" class="btn btn-outline-ink mt-2">Baca Selengkapnya</a>
      </div>
    </div>
  </div>
</section>
<?php endif; ?>

<!-- ============ TENTANG KAMI ============ -->
<section class="py-5">
  <div class="container">
    <div class="row align-items-center g-5">
      <div class="col-md-5">
        <div class="about-figure">
          <img src="assets/img/tentang-kami.jpeg" alt="Tentang Kami" onerror="this.src='https://placehold.co/600x450?text=SMK+INFOKOM'">
        </div>
      </div>
      <div class="col-md-7">
        <span class="kicker">Tentang Kami</span>
        <h2 class="mb-3">Pendidikan kejuruan yang membumi dan relevan dengan industri</h2>
        <p class="text-muted-ink">
          <?= nl2br(htmlspecialchars(mb_strimwidth($tentang['isi'] ?? 'SMK INFOKOM BOGOR adalah sekolah menengah kejuruan yang berkomitmen menghasilkan lulusan kompeten, berkarakter, dan siap bersaing di dunia kerja maupun industri.', 0, 320, '...'))) ?>
        </p>
        <a href="profil.php" class="btn btn-outline-ink mt-2">Baca Selengkapnya</a>
      </div>
    </div>
  </div>
</section>

<!-- ============ JURUSAN ============ -->
<?php $jurusanHome = $pdo->query("SELECT * FROM jurusan ORDER BY id ASC LIMIT 4")->fetchAll(); ?>
<?php if ($jurusanHome): ?>
<section class="py-5">
  <div class="container">
    <div class="section-head text-center">
      <span class="kicker">Program Keahlian</span>
      <h2>Jurusan yang Tersedia</h2>
      <p>Pilihan kompetensi keahlian yang selaras dengan kebutuhan industri teknologi.</p>
    </div>
    <div class="row g-4">
      <?php foreach ($jurusanHome as $j): ?>
      <div class="col-md-6 col-lg-3">
        <div class="card-editorial h-100">
          <img src="uploads/jurusan/<?= htmlspecialchars($j['gambar'] ?: 'default.jpg') ?>" alt="<?= htmlspecialchars($j['nama_jurusan']) ?>"
               onerror="this.src='https://placehold.co/400x220?text=<?= urlencode($j['singkatan'] ?: 'Jurusan') ?>'">
          <div class="card-body">
            <span class="badge badge-level mb-2"><?= htmlspecialchars($j['singkatan']) ?></span>
            <h6 class="mb-2"><?= htmlspecialchars($j['nama_jurusan']) ?></h6>
            <p class="small text-muted-ink mb-0"><?= htmlspecialchars(mb_strimwidth($j['deskripsi'] ?? '', 0, 90, '...')) ?></p>
          </div>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
    <div class="text-center mt-4">
      <a href="akademik.php" class="btn btn-outline-ink">Lihat Semua Jurusan</a>
    </div>
  </div>
</section>
<?php endif; ?>

<!-- ============ GURU & STAFF UNGGULAN ============ -->
<?php if ($guru): ?>
<section class="py-5">
  <div class="container">
    <div class="section-head text-center">
      <span class="kicker">Tenaga Pendidik</span>
      <h2>Guru &amp; Staff</h2>
      <p>Diampu oleh tenaga pendidik berpengalaman di bidangnya masing-masing.</p>
    </div>
    <div class="row g-4">
      <?php foreach ($guru as $g): ?>
      <div class="col-6 col-md-3">
        <div class="people-card">
          <div class="person-frame person-round">
            <img src="uploads/guru/<?= htmlspecialchars($g['foto'] ?: 'default.jpg') ?>" class="people-photo" alt="<?= htmlspecialchars($g['nama']) ?>"
                 onerror="this.src='https://placehold.co/300x400?text=Foto'">
          </div>
          <p class="nama mb-0"><?= htmlspecialchars($g['nama']) ?></p>
          <p class="jabatan"><?= htmlspecialchars($g['jabatan']) ?></p>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
    <div class="text-center mt-4">
      <a href="guru-staff.php" class="btn btn-outline-ink btn-sm">Lihat Semua Guru &amp; Staff</a>
    </div>
  </div>
</section>
<?php endif; ?>

<!-- ============ BERITA TERBARU ============ -->
<section class="py-5">
  <div class="container">
    <div class="section-head text-center">
      <span class="kicker">Publikasi</span>
      <h2>Berita Terbaru</h2>
    </div>
    <div class="row g-4">
      <?php if (count($berita) === 0): ?>
        <div class="col-12"><div class="empty-state"><i class="bi bi-newspaper"></i>Belum ada berita yang dipublikasikan.</div></div>
      <?php endif; ?>
      <?php foreach ($berita as $b): ?>
      <div class="col-md-4">
        <div class="card-editorial">
          <img src="uploads/berita/<?= htmlspecialchars($b['gambar'] ?: 'default.jpg') ?>" alt="<?= htmlspecialchars($b['judul']) ?>"
               onerror="this.src='https://placehold.co/400x220?text=Berita'">
          <div class="card-body">
            <p class="meta mb-2"><?= date('d M Y', strtotime($b['created_at'])) ?> &middot; <?= htmlspecialchars($b['penulis']) ?></p>
            <h6 class="mb-2"><a href="berita-detail.php?slug=<?= urlencode($b['slug']) ?>"><?= htmlspecialchars($b['judul']) ?></a></h6>
          </div>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
    <div class="text-center mt-4">
      <a href="berita.php" class="btn btn-ink">Lihat Berita Lainnya</a>
    </div>
  </div>
</section>

<!-- ============ GALERI KEGIATAN ============ -->
<section class="py-5">
  <div class="container">
    <div class="section-head text-center">
      <span class="kicker">Dokumentasi</span>
      <h2>Galeri Kegiatan</h2>
    </div>
    <div class="row g-3">
      <?php if (count($galeri) === 0): ?>
        <div class="col-12"><div class="empty-state"><i class="bi bi-images"></i>Belum ada galeri.</div></div>
      <?php endif; ?>
      <?php foreach ($galeri as $g): ?>
      <div class="col-6 col-md-2 gallery-thumb">
        <img src="uploads/galeri/<?= htmlspecialchars($g['gambar']) ?>" alt="<?= htmlspecialchars($g['judul']) ?>"
             onerror="this.src='https://placehold.co/300x200?text=Galeri'">
      </div>
      <?php endforeach; ?>
    </div>
    <div class="text-center mt-4">
      <a href="galeri.php" class="btn btn-outline-ink">Lihat Semua Galeri</a>
    </div>
  </div>
</section>

<!-- ============ LOWONGAN KERJA ALUMNI ============ -->
<?php if ($bkkJobs): ?>
<section class="py-5 bg-sky">
  <div class="container">
    <div class="section-head text-center">
      <span class="kicker kicker-teal">Bursa Kerja Khusus</span>
      <h2>Lowongan Terbaru untuk Alumni</h2>
      <p>Informasi lowongan kerja dari perusahaan mitra BKK SMK INFOKOM BOGOR.</p>
    </div>
    <div class="row g-4">
      <?php foreach ($bkkJobs as $job): ?>
      <div class="col-md-4">
        <div class="bkk-card">
          <div class="d-flex justify-content-between align-items-start mb-1">
            <div class="bkk-logo"><?= strtoupper(mb_substr($job['perusahaan'], 0, 2)) ?></div>
            <span class="badge-bkk"><?= htmlspecialchars($job['tipe_pekerjaan']) ?></span>
          </div>
          <h6 class="mt-2"><?= htmlspecialchars($job['posisi']) ?></h6>
          <p class="perusahaan mb-0"><?= htmlspecialchars($job['perusahaan']) ?></p>
          <div class="bkk-meta">
            <span><i class="bi bi-geo-alt"></i><?= htmlspecialchars($job['lokasi']) ?></span>
          </div>
          <p class="deskripsi mb-3"><?= htmlspecialchars(mb_strimwidth(strip_tags($job['deskripsi']), 0, 110, '...')) ?></p>
          <a href="bkk-lowongan.php" class="btn btn-sm btn-outline-teal align-self-start">Lihat Detail</a>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
    <div class="text-center mt-4">
      <a href="bkk-lowongan.php" class="btn btn-teal">Semua Lowongan BKK</a>
    </div>
  </div>
</section>
<?php endif; ?>

<!-- ============ RUNNING TEXT MITRA USAHA (logo perusahaan) ============ -->
<?php if ($mitraRun): ?>
<section class="mitra-run">
  <div class="container">
    <div class="mitra-run-head">
      <span class="mitra-run-label"><i class="bi bi-handshake-fill"></i> Mitra Usaha</span>
    </div>
    <div class="mitra-run-viewport">
      <div class="mitra-run-track">
        <?php for ($duplikat = 0; $duplikat < 2; $duplikat++): ?>
          <?php foreach ($mitraRun as $m): ?>
          <a class="mitra-run-item" href="bkk-mitra.php"<?= $duplikat ? ' aria-hidden="true" tabindex="-1"' : '' ?> title="<?= htmlspecialchars($m['nama_perusahaan']) ?><?= $m['bidang'] ? ' &middot; ' . htmlspecialchars($m['bidang']) : '' ?>">
            <span class="mitra-run-logo">
              <?php if ($m['logo']): ?>
                <img src="uploads/bkk/<?= htmlspecialchars($m['logo']) ?>" alt="<?= htmlspecialchars($m['nama_perusahaan']) ?>" onerror="this.replaceWith(document.createTextNode(<?= json_encode(strtoupper(mb_substr($m['nama_perusahaan'], 0, 2))) ?>))">
              <?php else: ?>
                <?= strtoupper(mb_substr($m['nama_perusahaan'], 0, 2)) ?>
              <?php endif; ?>
            </span>
            <span class="mitra-run-name"><?= htmlspecialchars($m['nama_perusahaan']) ?></span>
          </a>
          <?php endforeach; ?>
        <?php endfor; ?>
      </div>
    </div>
    <div class="mitra-run-foot">
      <a class="mitra-run-more" href="bkk-mitra.php">Semua Mitra <i class="bi bi-arrow-right"></i></a>
    </div>
  </div>
</section>
<?php endif; ?>

<script>
/* Slideshow latar hero: ganti gambar tiap 6 detik. Aman bila ada gambar yang
   gagal dimuat (terhapus dari DOM) — daftar slide dibaca ulang tiap siklus. */
(function () {
  var idx = 0;
  setInterval(function () {
    var slides = Array.prototype.slice.call(document.querySelectorAll('.hero-school-slide'));
    if (slides.length < 2) return;
    idx = (idx + 1) % slides.length;
    slides.forEach(function (el, i) { el.classList.toggle('is-active', i === idx); });
  }, 6000);
})();
</script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
