<?php
require_once __DIR__ . '/koneksi.php';
$current = basename($_SERVER['PHP_SELF']);
$inGroup = function(array $files) use ($current) { return in_array($current, $files, true); };
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= $pageTitle ?? 'SMK INFOKOM BOGOR' ?></title>
<?php
// --- SEO: deskripsi halaman + Open Graph (set halaman bisa menimpa lewat $pageDesc) ---
$pageDescDefault = 'Website resmi SMK INFOKOM BOGOR - sekolah menengah kejuruan bidang teknologi informasi dan komunikasi di Kota Bogor. Informasi profil sekolah, jurusan, berita, PPDB online, dan Bursa Kerja Khusus (Program Mitra).';
$pageDesc = isset($pageDesc) && trim((string)$pageDesc) !== '' ? trim((string)$pageDesc) : $pageDescDefault;
$pageDesc = mb_strimwidth($pageDesc, 0, 160, '...');
$siteName = 'SMK INFOKOM BOGOR';
?>
<meta name="description" content="<?= htmlspecialchars($pageDesc, ENT_QUOTES, 'UTF-8') ?>">
<meta name="theme-color" content="#0B2E52">
<meta property="og:type" content="website">
<meta property="og:site_name" content="<?= $siteName ?>">
<meta property="og:title" content="<?= htmlspecialchars($pageTitle ?? $siteName, ENT_QUOTES, 'UTF-8') ?>">
<meta property="og:description" content="<?= htmlspecialchars($pageDesc, ENT_QUOTES, 'UTF-8') ?>">
<meta property="og:image" content="assets/img/logo.png">
<meta name="twitter:card" content="summary">
<link rel="icon" type="image/png" href="assets/img/logo.png">

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Sora:wght@500;600;700;800&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<!-- CSS disematkan langsung (server-side include) agar tidak gagal karena cache/path file eksternal -->
<style>
<?php
$cssPath = __DIR__ . '/../assets/css/style.css';
if (file_exists($cssPath)) {
    readfile($cssPath);
} else {
    echo "/* assets/css/style.css tidak ditemukan di server */";
}
?>
</style>
<!-- Cadangan: tetap muat sebagai file terpisah juga (untuk cache browser jangka panjang) -->
<link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

<!-- ============ TOPBAR (info kontak) ============ -->
<div class="topbar">
  <div class="container d-flex justify-content-between align-items-center">
    <div class="d-flex align-items-center gap-3 topbar-contact">
      <span><i class="bi bi-geo-alt-fill me-1"></i> Jl. Letjen Ibrahim Adjie No.178</span>
      <span><i class="bi bi-telephone-fill me-1"></i>0251-8348-108</span>
      <span><i class="bi bi-envelope-fill me-1"></i>smk.infokom@gmail.com</span>
    </div>
    <div class="d-flex align-items-center gap-2 topbar-social">
      <a href="https://m.facebook.com/SMK-Infokom-105202311143756/?tsid=0.5200740061378188&amp;source=result" aria-label="Facebook"><i class="bi bi-facebook"></i></a>
      <a href="https://www.instagram.com/smk.infokom_official" aria-label="Instagram"><i class="bi bi-instagram"></i></a>
      <a href="https://www.youtube.com/channel/UCM1wHznYF69jhzSvrR5bKGQ" aria-label="YouTube"><i class="bi bi-youtube"></i></a>
      <a href="https://www.tiktok.com/@smk_infokom_kota_bogor" aria-label="TikTok" target="_blank" rel="noopener"><i class="bi bi-tiktok"></i></a>
    </div>
  </div>
</div>

<!-- ============ NAVBAR ============ -->
<nav class="navbar navbar-expand-lg site-navbar sticky-top py-2">
  <div class="container">
    <a class="navbar-brand d-flex align-items-center gap-2" href="index.php">
      <span class="brand-mark">
        <img src="assets/img/logo.png" alt="Logo" onerror="this.style.display='none'; this.parentElement.innerHTML='SI';">
      </span>
      <span class="brand-text">SMK INFOKOM BOGOR<br><small>Teknologi Informasi &amp; Komunikasi</small></span>
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMain">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navMain">
      <ul class="navbar-nav ms-auto align-items-lg-center gap-lg-1">
        <li class="nav-item"><a class="nav-link <?= $current=='index.php'?'active':'' ?>" href="index.php">Beranda</a></li>

        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle <?= $inGroup(['profil.php','guru-staff.php'])?'active':'' ?>" href="#" role="button" data-bs-toggle="dropdown">Profil <i class="bi bi-chevron-down dropdown-caret"></i></a>
          <ul class="dropdown-menu dropdown-menu-enhanced">
            <li class="dropdown-head"><span>Profil Sekolah</span></li>
            <li><a class="dropdown-item dropdown-item-enhanced" href="profil.php?jenis=sambutan"><span class="dd-icon"><i class="bi bi-chat-quote"></i></span><span class="dd-text"><strong>Sambutan Kepala Sekolah</strong></span></a></li>
            <li><a class="dropdown-item dropdown-item-enhanced" href="profil.php?jenis=sejarah"><span class="dd-icon"><i class="bi bi-clock-history"></i></span><span class="dd-text"><strong>Sejarah</strong></span></a></li>
            <li><a class="dropdown-item dropdown-item-enhanced" href="profil.php?jenis=visi_misi"><span class="dd-icon"><i class="bi bi-bullseye"></i></span><span class="dd-text"><strong>Visi &amp; Misi</strong></span></a></li>
            <li><a class="dropdown-item dropdown-item-enhanced" href="profil.php?jenis=struktur"><span class="dd-icon"><i class="bi bi-diagram-3"></i></span><span class="dd-text"><strong>Struktur Organisasi</strong></span></a></li>
            <li><a class="dropdown-item dropdown-item-enhanced" href="guru-staff.php"><span class="dd-icon"><i class="bi bi-people"></i></span><span class="dd-text"><strong>Guru &amp; Staff</strong></span></a></li>
            <li><a class="dropdown-item dropdown-item-enhanced" href="profil.php?jenis=fasilitas"><span class="dd-icon"><i class="bi bi-buildings"></i></span><span class="dd-text"><strong>Fasilitas Sekolah</strong></span></a></li>
          </ul>
        </li>

        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle <?= $inGroup(['akademik.php','kurikulum.php','pkl.php','kalender.php'])?'active':'' ?>" href="#" role="button" data-bs-toggle="dropdown">Akademik <i class="bi bi-chevron-down dropdown-caret"></i></a>
          <ul class="dropdown-menu dropdown-menu-enhanced">
            <li class="dropdown-head"><span>Program &amp; Pembelajaran</span></li>
            <li><a class="dropdown-item dropdown-item-enhanced" href="akademik.php"><span class="dd-icon"><i class="bi bi-mortarboard"></i></span><span class="dd-text"><strong>Program Keahlian / Jurusan</strong></span></a></li>
            <li><a class="dropdown-item dropdown-item-enhanced" href="kurikulum.php"><span class="dd-icon"><i class="bi bi-journal-bookmark"></i></span><span class="dd-text"><strong>Kurikulum</strong></span></a></li>
            <li><a class="dropdown-item dropdown-item-enhanced" href="kalender.php"><span class="dd-icon"><i class="bi bi-calendar3"></i></span><span class="dd-text"><strong>Kalender Pendidikan</strong></span></a></li>
            <li><a class="dropdown-item dropdown-item-enhanced" href="pkl.php"><span class="dd-icon"><i class="bi bi-person-workspace"></i></span><span class="dd-text"><strong>PKL</strong></span></a></li>
          </ul>
        </li>

        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle <?= $inGroup(['berita.php','berita-detail.php','pengumuman.php','prestasi.php','agenda.php','galeri.php','ekstrakurikuler.php'])?'active':'' ?>" href="#" role="button" data-bs-toggle="dropdown">Informasi <i class="bi bi-chevron-down dropdown-caret"></i></a>
          <ul class="dropdown-menu dropdown-menu-enhanced">
            <li class="dropdown-head"><span>Informasi Sekolah</span></li>
            <li><a class="dropdown-item dropdown-item-enhanced" href="berita.php"><span class="dd-icon"><i class="bi bi-newspaper"></i></span><span class="dd-text"><strong>Berita</strong></span></a></li>
            <li><a class="dropdown-item dropdown-item-enhanced" href="pengumuman.php"><span class="dd-icon"><i class="bi bi-megaphone"></i></span><span class="dd-text"><strong>Pengumuman</strong></span></a></li>
            <li><a class="dropdown-item dropdown-item-enhanced" href="agenda.php"><span class="dd-icon"><i class="bi bi-calendar-event"></i></span><span class="dd-text"><strong>Agenda</strong></span></a></li>
            <li><a class="dropdown-item dropdown-item-enhanced" href="prestasi.php"><span class="dd-icon"><i class="bi bi-trophy"></i></span><span class="dd-text"><strong>Prestasi</strong></span></a></li>
            <li><a class="dropdown-item dropdown-item-enhanced" href="ekstrakurikuler.php"><span class="dd-icon"><i class="bi bi-people"></i></span><span class="dd-text"><strong>Ekstrakurikuler</strong></span></a></li>
            <li><a class="dropdown-item dropdown-item-enhanced" href="galeri.php"><span class="dd-icon"><i class="bi bi-images"></i></span><span class="dd-text"><strong>Galeri</strong></span></a></li>
          </ul>
        </li>

        <!-- BKK: Bursa Kerja Khusus -->
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle <?= $inGroup(['bkk.php','bkk-lowongan.php','bkk-daftar.php','bkk-mitra.php','mitra-universitas.php'])?'active':'' ?>" href="#" role="button" data-bs-toggle="dropdown">Program Mitra <i class="bi bi-chevron-down dropdown-caret"></i></a>
          <ul class="dropdown-menu dropdown-menu-enhanced dropdown-menu-end">
            <li class="dropdown-head"><span>Program Mitra</span></li>
            <li><a class="dropdown-item dropdown-item-enhanced" href="bkk-mitra.php"><span class="dd-icon"><i class="bi bi-building"></i></span><span class="dd-text"><strong>Mitra Industri</strong></span></a></li>
            <li><a class="dropdown-item dropdown-item-enhanced" href="mitra-universitas.php"><span class="dd-icon"><i class="bi bi-bank"></i></span><span class="dd-text"><strong>Mitra Universitas</strong></span></a></li>
            <li><a class="dropdown-item dropdown-item-enhanced" href="bkk-lowongan.php"><span class="dd-icon"><i class="bi bi-briefcase"></i></span><span class="dd-text"><strong>Lowongan Kerja</strong></span></a></li>
            <li><a class="dropdown-item dropdown-item-enhanced" href="bkk-lowongan.php?tipe=Magang"><span class="dd-icon"><i class="bi bi-mortarboard"></i></span><span class="dd-text"><strong>Program Magang</strong></span></a></li>
            <li><a class="dropdown-item dropdown-item-enhanced" href="bkk-lowongan.php?tipe=PKL"><span class="dd-icon"><i class="bi bi-clipboard-check"></i></span><span class="dd-text"><strong>Program PKL</strong></span></a></li>
          </ul>
        </li>

        <li class="nav-item"><a class="nav-link <?= $current=='kontak.php'?'active':'' ?>" href="kontak.php">Kontak</a></li>
        <li class="nav-item nav-cta"><a class="nav-link <?= $inGroup(['ppdb.php'])?'active':'' ?>" href="ppdb.php">PPDB</a></li>
      </ul>
      <!-- Sengaja TIDAK ADA tombol "Login Admin" di navbar maupun halaman publik manapun -->
    </div>
  </div>
</nav>
