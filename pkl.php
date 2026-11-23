<?php
$pageTitle = 'Praktik Kerja Lapangan (PKL) - SMK INFOKOM BOGOR';
$pageDesc = 'Praktik Kerja Lapangan (PKL) SMK INFOKOM BOGOR: pengertian, tujuan, jadwal, dan kegiatan PKL siswa di perusahaan mitra industri.';
require_once __DIR__ . '/includes/header.php';

// Pengertian PKL
$pengertianPKL = 'Praktik Kerja Lapangan (PKL) adalah program pembelajaran yang dilaksanakan siswa di dunia usaha atau dunia industri (DU/DI) sebagai perpanjangan mata pelajaran keahlian. Siswa belajar menerapkan keterampilan dan pengetahuan yang diperoleh di sekolah pada kondisi kerja yang sesungguhnya, sehingga lulusan memiliki pengalaman kerja, kesiapan mental, dan kompetensi yang sesuai tuntutan industri.';

// Tujuan PKL
$tujuanPKL = [
    ['icon' => 'bi-briefcase', 'judul' => 'Pengalaman Kerja Nyata', 'isi' => 'Siswa terbiasa dengan situasi dan budaya kerja profesional di perusahaan mitra sebelum masuk dunia kerja.'],
    ['icon' => 'bi-arrow-up-right-circle', 'judul' => 'Penerapan Ilmu', 'isi' => 'Menghubungkan teori di kelas dengan praktik langsung sesuai kompetensi keahlian masing-masing siswa.'],
    ['icon' => 'bi-person-check', 'judul' => 'Kesiapan Mental & Disiplin', 'isi' => 'Membangun sikap disiplin, tanggung jawab, kerja sama, dan komunikasi profesional ala dunia industri.'],
    ['icon' => 'bi-diagram-3', 'judul' => 'Jaringan Industri', 'isi' => 'Membuka peluang relasi dan rekomendasi kerja bagi alumni, sekaligus menjalin kerja sama sekolah dengan DU/DI.'],
];

// Kegiatan PKL (alur dari persiapan sampai pelaporan)
$kegiatanPKL = [
    ['no' => 1, 'judul' => 'Persiapan & Penempatan', 'isi' => 'Sosialisasi PKL, pemilihan perusahaan mitra, penandatanganan perjanjian kerja sama (PKS), dan pembagian siswa ke tempat PKL sesuai bidang keahlian.'],
    ['no' => 2, 'judul' => 'Orientasi di Tempat PKL', 'isi' => 'Pengenalan lingkungan kerja, struktur organisasi perusahaan, peraturan, dan keselamatan kerja (K3) oleh pembimbing industri.'],
    ['no' => 3, 'judul' => 'Pelaksanaan Praktik Kerja', 'isi' => 'Siswa mengerjakan tugas sesuai divisi yang ditempati (mis. pemrograman di RPL, jaringan/server di TKJ, produksi desain di DKV) dengan supervisi pembimbing industri.'],
    ['no' => 4, 'judul' => 'Monitoring dari Sekolah', 'isi' => 'Guru pembimbing melakukan kunjungan/pengawasan secara berkala untuk memastikan aktivitas PKL berjalan lancar.'],
    ['no' => 5, 'judul' => 'Evaluasi & Pelaporan', 'isi' => 'Penilaian dari pembimbing industri dan guru, penulisan laporan PKL, lalu presentasi hasil praktik di sekolah.'],
];

// Jadwal PKL (ringkas)
$jadwalPKL = [
    ['tahap' => 'Penempatan & PKS', 'waktu' => 'Sebelum semester akhir'],
    ['tahap' => 'Pelaksanaan PKL', 'waktu' => '± 3-4 bulan (semester akhir kelas XII)'],
    ['tahap' => 'Laporan & Presentasi', 'waktu' => 'Akhir masa PKL'],
];
?>

<div class="page-header">
  <div class="container">
    <div class="page-breadcrumb"><a href="index.php">Beranda</a> <i class="bi bi-chevron-right"></i> <a href="akademik.php">Akademik</a> <i class="bi bi-chevron-right"></i> PKL</div>
    <h1 class="page-title">Praktik Kerja Lapangan (PKL)</h1>
    <p class="page-title-sub mt-2">Program pengalaman kerja siswa di perusahaan mitra sebagai bekal siap kerja.</p>
  </div>
</div>

<!-- ==== Pengertian PKL ==== -->
<section class="py-5">
  <div class="container">
    <div class="row align-items-center g-5">
      <div class="col-lg-7">
        <span class="kicker">Mengenal PKL</span>
        <h2 class="mb-3">Apa itu Praktik Kerja Lapangan?</h2>
        <p class="text-muted-ink"><?= htmlspecialchars($pengertianPKL) ?></p>
        <div class="d-flex flex-wrap gap-2 mt-3">
          <a href="bkk-lowongan.php?tipe=PKL" class="btn btn-ink"><i class="bi bi-clipboard-check me-1"></i> Lihat Lowongan PKL dari Mitra</a>
          <a href="bkk-mitra.php" class="btn btn-outline-ink">Perusahaan Mitra</a>
        </div>
      </div>
      <div class="col-lg-5">
        <div class="card-editorial p-4">
          <h6 class="mb-3"><i class="bi bi-calendar-event me-2" style="color:var(--blue);"></i>Jadwal Pelaksanaan</h6>
          <?php foreach ($jadwalPKL as $j): ?>
          <div class="d-flex justify-content-between align-items-center py-2" style="border-bottom:1px solid var(--paper-line);">
            <span class="small fw-semibold"><?= htmlspecialchars($j['tahap']) ?></span>
            <span class="small text-muted-ink"><?= htmlspecialchars($j['waktu']) ?></span>
          </div>
          <?php endforeach; ?>
          <p class="small text-muted-ink mt-3 mb-0">Jadwal pasti diumumkan melalui halaman Pengumuman.</p>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ==== Tujuan PKL ==== -->
<section class="py-5 bg-sky">
  <div class="container">
    <div class="section-head text-center">
      <span class="kicker">Tujuan</span>
      <h2>Untuk Apa PKL Dilaksanakan?</h2>
    </div>
    <div class="row g-4">
      <?php foreach ($tujuanPKL as $t): ?>
      <div class="col-md-6 col-lg-3">
        <div class="info-tile h-100">
          <div class="icon-circle"><i class="bi <?= $t['icon'] ?>"></i></div>
          <h6 class="mb-2"><?= htmlspecialchars($t['judul']) ?></h6>
          <p class="small text-muted-ink mb-0"><?= htmlspecialchars($t['isi']) ?></p>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- ==== Kegiatan PKL ==== -->
<section class="py-5">
  <div class="container">
    <div class="section-head text-center">
      <span class="kicker">Kegiatan</span>
      <h2>Alur &amp; Kegiatan Selama PKL</h2>
      <p>Lima tahapan kegiatan yang dilalui siswa dari persiapan hingga pelaporan.</p>
    </div>
    <div class="row justify-content-center">
      <div class="col-lg-8">
        <div class="timeline">
          <?php foreach ($kegiatanPKL as $k): ?>
          <div class="timeline-item">
            <div class="timeline-date">Tahap <?= $k['no'] ?></div>
            <h6 class="mt-1 mb-1"><?= htmlspecialchars($k['judul']) ?></h6>
            <p class="small text-muted-ink mb-0"><?= htmlspecialchars($k['isi']) ?></p>
          </div>
          <?php endforeach; ?>
        </div>
      </div>
    </div>
  </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
