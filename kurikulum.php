<?php
$pageTitle = 'Kurikulum - SMK INFOKOM BOGOR';
require_once __DIR__ . '/includes/header.php';

$kurikulum = $pdo->query("SELECT * FROM kurikulum ORDER BY created_at DESC")->fetchAll();

$jurusanKur = [];
try {
    $jurusanKur = $pdo->query("SELECT * FROM jurusan ORDER BY id ASC")->fetchAll();
} catch (Throwable $e) {
    $jurusanKur = [];
}

// Struktur pembelajaran umum (berlaku untuk semua program keahlian)
$strukturPembelajaran = [
    ['icon' => 'bi-journal-bookmark', 'judul' => 'Pembelajaran di Kelas', 'isi' => 'Mata pelajaran umum (Bahasa Indonesia, Matematika, Bahasa Inggris, PPKN, Agama) dan mata pelajaran keahlian diajar dengan metode ceramah, diskusi, dan praktik langsung di ruang kelas.'],
    ['icon' => 'bi-laptop', 'judul' => 'Praktik di Laboratorium', 'isi' => 'Mata pelajaran keahlian didominasi praktik di laboratorium komputer/jaringan dan studio desain, dengan rasio praktik lebih besar dibanding teori.'],
    ['icon' => 'bi-building', 'judul' => 'Praktik Kerja Lapangan (PKL)', 'isi' => 'Pada semester akhir siswa melaksanakan PKL di perusahaan mitra untuk mengaplikasikan kompetensi di dunia kerja nyata. Lihat menu Akademik -> PKL.'],
    ['icon' => 'bi-award', 'judul' => 'Uji Kompetensi Keahlian (UKK)', 'isi' => 'Sebelum lulus, siswa mengikuti uji kompetensi bersertifikat sesuai bidang keahlian untuk memastikan kesiapan kerja.'],
];

// Mata pelajaran per program keahlian (disesuaikan kompetensi keahlian masing-masing)
$mapelPerJurusan = [
    'RPL' => [
        'Mata Pelajaran Keahlian' => ['Dasar-dasar Pemrograman', 'Pemrograman Berorientasi Objek', 'Pemrograman Web Mobile', 'Basis Data', 'Rekayasa Perangkat Lunak', 'Pemrograman Perangkat Bergerak'],
        'Mata Pelajaran Umum' => ['Bahasa Indonesia', 'Matematika', 'Bahasa Inggris', 'PPKN', 'Pendidikan Agama', 'PJOK', 'Seni Budaya'],
    ],
    'TKJ' => [
        'Mata Pelajaran Keahlian' => ['Administrasi Sistem Jaringan', 'Administrasi Server Linux', 'Konfigurasi Jaringan', 'Teknologi Jaringan Kabel dan Nirkabel', 'Keamanan Jaringan', 'Perakitan Komputer'],
        'Mata Pelajaran Umum' => ['Bahasa Indonesia', 'Matematika', 'Bahasa Inggris', 'PPKN', 'Pendidikan Agama', 'PJOK', 'Sejarah Indonesia'],
    ],
    'DKV' => [
        'Mata Pelajaran Keahlian' => ['Dasar-dasar Desain Grafis', 'Tipografi', 'Ilustrasi', 'Fotografi', 'Animasi 2D', 'Multimedia Interaktif', 'Desain Publikasi Cetak'],
        'Mata Pelajaran Umum' => ['Bahasa Indonesia', 'Matematika', 'Bahasa Inggris', 'PPKN', 'Pendidikan Agama', 'PJOK', 'Seni Budaya'],
    ],
    'MM' => [
        'Mata Pelajaran Keahlian' => ['Desain Grafis', 'Produksi Animasi', 'Videografi', 'Fotografi', 'Pengolahan Audio', 'Multimedia Interaktif'],
        'Mata Pelajaran Umum' => ['Bahasa Indonesia', 'Matematika', 'Bahasa Inggris', 'PPKN', 'Pendidikan Agama', 'PJOK', 'Sejarah Indonesia'],
    ],
    'PSPT' => [
        'Mata Pelajaran Keahlian' => ['Dasar-dasar Penyiaran Televisi', 'Produksi Program Televisi', 'Teknik Kamera dan Pencahayaan', 'Pengolahan Audio Video', 'Manajemen Produksi Siaran', 'Jurnalistik Penyiaran'],
        'Mata Pelajaran Umum' => ['Bahasa Indonesia', 'Matematika', 'Bahasa Inggris', 'PPKN', 'Pendidikan Agama', 'PJOK', 'Seni Budaya'],
    ],
];
?>

<div class="page-header">
  <div class="container">
    <div class="page-breadcrumb"><a href="index.php">Beranda</a> <i class="bi bi-chevron-right"></i> <a href="akademik.php">Akademik</a> <i class="bi bi-chevron-right"></i> Kurikulum</div>
    <h1 class="page-title">Kurikulum</h1>
  </div>
</div>

<div class="container py-5">
  <div class="section-head">
    <span class="kicker">Akademik</span>
    <h2>Kurikulum yang Diterapkan</h2>
  </div>

  <?php if (count($kurikulum) === 0): ?>
    <div class="empty-state"><i class="bi bi-journal-bookmark"></i>Informasi kurikulum belum tersedia.</div>
  <?php endif; ?>

  <?php foreach ($kurikulum as $k): ?>
  <div class="card-editorial mb-4">
    <div class="row g-0">
      <?php if (!empty($k['gambar'])): ?>
      <div class="col-md-4">
        <img src="uploads/kurikulum/<?= htmlspecialchars($k['gambar']) ?>" class="h-100 w-100" style="object-fit:cover;" alt="">
      </div>
      <?php endif; ?>
      <div class="<?= !empty($k['gambar']) ? 'col-md-8' : 'col-12' ?>">
        <div class="card-body p-4">
          <h5 class="mb-2"><?= htmlspecialchars($k['judul']) ?></h5>
          <p class="text-muted-ink" style="white-space:pre-line;"><?= htmlspecialchars($k['deskripsi']) ?></p>
          <?php if (!empty($k['file_dokumen'])): ?>
            <a href="uploads/kurikulum/<?= htmlspecialchars($k['file_dokumen']) ?>" target="_blank" class="btn btn-sm btn-outline-ink"><i class="bi bi-file-earmark-arrow-down"></i> Unduh Dokumen</a>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </div>
  <?php endforeach; ?>

  <!-- ==== Struktur Pembelajaran ==== -->
  <div class="section-head mt-5">
    <span class="kicker">Bagaimana Belajar di SMK INFOKOM</span>
    <h2>Struktur Pembelajaran</h2>
  </div>
  <div class="row g-4 mb-2">
    <?php foreach ($strukturPembelajaran as $sp): ?>
    <div class="col-md-6 col-lg-3">
      <div class="info-tile h-100">
        <div class="icon-circle"><i class="bi <?= $sp['icon'] ?>"></i></div>
        <h6 class="mb-2"><?= htmlspecialchars($sp['judul']) ?></h6>
        <p class="small text-muted-ink mb-0"><?= htmlspecialchars($sp['isi']) ?></p>
      </div>
    </div>
    <?php endforeach; ?>
  </div>

  <!-- ==== Mata Pelajaran per Program Keahlian ==== -->
  <div class="section-head mt-5">
    <span class="kicker">Program Keahlian</span>
    <h2>Mata Pelajaran per Jurusan</h2>
    <p>Mata pelajaran keahlian disesuaikan dengan kompetensi masing-masing program keahlian.</p>
  </div>

  <?php if (count($jurusanKur) === 0): ?>
    <div class="empty-state"><i class="bi bi-mortarboard"></i>Data jurusan belum tersedia.</div>
  <?php endif; ?>

  <?php foreach ($jurusanKur as $jk): $sing = strtoupper(trim((string)($jk['singkatan'] ?? ''))); ?>
  <div class="card-editorial mb-4">
    <div class="card-body p-4">
      <div class="d-flex align-items-center gap-2 mb-3">
        <span class="badge badge-level"><?= htmlspecialchars($jk['singkatan'] ?: $jk['nama_jurusan']) ?></span>
        <h5 class="mb-0"><?= htmlspecialchars($jk['nama_jurusan']) ?></h5>
      </div>

      <?php if (isset($mapelPerJurusan[$sing])): ?>
      <div class="row g-4">
        <?php foreach ($mapelPerJurusan[$sing] as $kelompok => $daftarMapel): ?>
        <div class="col-md-6">
          <p class="small fw-bold text-uppercase mb-2" style="letter-spacing:0.06em; color:var(--blue);">
            <i class="bi bi-collection-play me-1"></i><?= htmlspecialchars($kelompok) ?>
          </p>
          <ul class="list-unstyled mb-0">
            <?php foreach ($daftarMapel as $m): ?>
            <li class="small text-muted-ink py-1" style="border-bottom:1px solid var(--paper-line);">
              <i class="bi bi-check2-circle me-2" style="color:var(--blue);"></i><?= htmlspecialchars($m) ?>
            </li>
            <?php endforeach; ?>
          </ul>
        </div>
        <?php endforeach; ?>
      </div>
      <?php else: ?>
      <p class="small text-muted-ink mb-0"><i class="bi bi-info-circle me-1"></i>Daftar mata pelajaran jurusan ini belum tersedia.</p>
      <?php endif; ?>
    </div>
  </div>
  <?php endforeach; ?>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
