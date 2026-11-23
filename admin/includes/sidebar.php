<?php
$curr = basename($_SERVER['PHP_SELF']);
$base = $GLOBALS['ADMIN_BASE'] ?? '/admin/';
$uri  = $_SERVER['REQUEST_URI'];
$in = function(string $needle) use ($uri) { return str_contains($uri, $needle); };

/**
 * Notifikasi jumlah (badge) pada menu — dihitung langsung dari database.
 * Ditampilkan hanya jika tersedia dan > 0, sehingga aman bila tabel belum ada.
 */
$menuBadge = ['ppdb' => 0, 'pelamar' => 0, 'pesan' => 0];
if (isset($pdo) && $pdo instanceof PDO) {
    $hitung = function (string $sql) use ($pdo): int {
        try {
            return (int)$pdo->query($sql)->fetchColumn();
        } catch (Throwable $e) {
            return 0;
        }
    };
    $menuBadge['ppdb']    = $hitung("SELECT COUNT(*) FROM pendaftaran_ppdb WHERE status='menunggu'");
    $menuBadge['pelamar'] = $hitung("SELECT COUNT(*) FROM bkk_pelamar WHERE status='baru'");
    $menuBadge['pesan']   = $hitung("SELECT COUNT(*) FROM pesan_kontak WHERE status='belum_dibaca'");
}
?>
<div class="admin-sidebar d-flex flex-column text-white p-3 sidebar-scroll position-sticky top-0">
  <a class="admin-brand d-flex align-items-center gap-2 mb-4 px-1 text-white text-decoration-none" href="<?= $base ?>index.php">
    <span class="brand-mark" style="width:40px;height:40px;font-size:0.9rem;">
      <img src="<?= $base ?>../assets/img/logo.png" alt="Logo" onerror="this.style.display='none'; this.parentElement.innerHTML='SI';">
    </span>
    <span class="lh-sm">
      <span class="d-block fw-semibold admin-brand-title">SMK INFOKOM</span>
      <small class="d-block text-white-50">Admin Panel</small>
    </span>
  </a>

  <nav class="admin-nav flex-grow-1">
    <a href="<?= $base ?>index.php" class="admin-link <?= $curr=='index.php' && !$in('/profil/') && !$in('/jurusan/') ?'active':'' ?>"><span class="admin-ico"><i class="bi bi-speedometer2"></i></span> Dashboard</a>

    <div class="admin-group-label">Profil Sekolah</div>
    <a href="<?= $base ?>profil/list.php" class="admin-link <?= $in('/profil/')?'active':'' ?>"><span class="admin-ico"><i class="bi bi-building"></i></span> Konten Profil</a>
    <a href="<?= $base ?>struktur/list.php" class="admin-link <?= $in('/struktur/')?'active':'' ?>"><span class="admin-ico"><i class="bi bi-diagram-3"></i></span> Struktur Organisasi</a>
    <a href="<?= $base ?>fasilitas/list.php" class="admin-link <?= $in('/fasilitas/')?'active':'' ?>"><span class="admin-ico"><i class="bi bi-buildings"></i></span> Fasilitas</a>
    <a href="<?= $base ?>guru/list.php" class="admin-link <?= $in('/guru/')?'active':'' ?>"><span class="admin-ico"><i class="bi bi-person-badge"></i></span> Guru &amp; Staff</a>

    <div class="admin-group-label">Akademik</div>
    <a href="<?= $base ?>jurusan/list.php" class="admin-link <?= $in('/jurusan/')?'active':'' ?>"><span class="admin-ico"><i class="bi bi-mortarboard"></i></span> Jurusan</a>
    <a href="<?= $base ?>akademik/kurikulum/list.php" class="admin-link <?= $in('/kurikulum/')?'active':'' ?>"><span class="admin-ico"><i class="bi bi-journal-text"></i></span> Kurikulum</a>
    <a href="<?= $base ?>akademik/kalender/list.php" class="admin-link <?= $in('/kalender/')?'active':'' ?>"><span class="admin-ico"><i class="bi bi-calendar3"></i></span> Kalender Pendidikan</a>

    <div class="admin-group-label">Informasi</div>
    <a href="<?= $base ?>berita/list.php" class="admin-link <?= $in('/berita/')?'active':'' ?>"><span class="admin-ico"><i class="bi bi-newspaper"></i></span> Berita</a>
    <a href="<?= $base ?>pengumuman/list.php" class="admin-link <?= $in('/pengumuman/')?'active':'' ?>"><span class="admin-ico"><i class="bi bi-megaphone"></i></span> Pengumuman</a>
    <a href="<?= $base ?>agenda/list.php" class="admin-link <?= $in('/agenda/')?'active':'' ?>"><span class="admin-ico"><i class="bi bi-calendar-event"></i></span> Agenda</a>
    <a href="<?= $base ?>prestasi/list.php" class="admin-link <?= $in('/prestasi/')?'active':'' ?>"><span class="admin-ico"><i class="bi bi-trophy"></i></span> Prestasi</a>
    <a href="<?= $base ?>ekstrakurikuler/list.php" class="admin-link <?= $in('/ekstrakurikuler/')?'active':'' ?>"><span class="admin-ico"><i class="bi bi-people"></i></span> Ekstrakurikuler</a>
    <a href="<?= $base ?>galeri/list.php" class="admin-link <?= $in('/galeri/')?'active':'' ?>"><span class="admin-ico"><i class="bi bi-images"></i></span> Galeri</a>
    <a href="<?= $base ?>kontak/list.php" class="admin-link <?= $in('/kontak/')?'active':'' ?>"><span class="admin-ico"><i class="bi bi-envelope"></i></span> Pesan Kontak
      <?php if ($menuBadge['pesan'] > 0): ?><span class="admin-badge"><?= $menuBadge['pesan'] ?></span><?php endif; ?>
    </a>

    <div class="admin-group-label">PPDB</div>
    <a href="<?= $base ?>ppdb/list.php" class="admin-link <?= $in('/ppdb/')?'active':'' ?>"><span class="admin-ico"><i class="bi bi-person-lines-fill"></i></span> Data Pendaftar
      <?php if ($menuBadge['ppdb'] > 0): ?><span class="admin-badge"><?= $menuBadge['ppdb'] ?></span><?php endif; ?>
    </a>

    <div class="admin-group-label">BKK</div>
    <a href="<?= $base ?>bkk/lowongan/list.php" class="admin-link <?= $in('/bkk/lowongan/')?'active':'' ?>"><span class="admin-ico"><i class="bi bi-briefcase"></i></span> Daftar Lowongan</a>
    <a href="<?= $base ?>bkk/mitra/list.php" class="admin-link <?= $in('/bkk/mitra/')?'active':'' ?>"><span class="admin-ico"><i class="bi bi-building"></i></span> Mitra Kerja Sama</a>
    <a href="<?= $base ?>bkk/pelamar/list.php" class="admin-link <?= $in('/bkk/pelamar/')?'active':'' ?>"><span class="admin-ico"><i class="bi bi-person-check"></i></span> Daftar Pelamar
      <?php if ($menuBadge['pelamar'] > 0): ?><span class="admin-badge"><?= $menuBadge['pelamar'] ?></span><?php endif; ?>
    </a>
  </nav>

  <div class="admin-foot">
    <a href="<?= $base ?>../" target="_blank" rel="noopener" class="admin-link admin-link-view"><span class="admin-ico"><i class="bi bi-globe2"></i></span> Lihat Situs</a>
    <a href="<?= $base ?>logout.php" class="admin-link admin-link-logout"><span class="admin-ico"><i class="bi bi-box-arrow-right"></i></span> Logout</a>
  </div>
</div>

<style>
.admin-sidebar {
  width: 250px;
  /* Kunci lebar sidebar: konten gambar besar (mis. Galeri) tidak boleh
     memampatkan sidebar saat layar sempit. */
  flex-shrink: 0;
  height: 100vh;
  overflow-y: auto;
  background:
    radial-gradient(circle at 20% 0%, rgba(37, 99, 235, 0.28) 0%, transparent 45%),
    #0B2E52;
  border-right: 1px solid rgba(255, 255, 255, 0.06);
}
.admin-brand-title { font-family: 'Sora', sans-serif; font-size: 0.95rem; letter-spacing: 0.02em; }
/* Pastikan logo terkunci dalam kotaknya (CSS frontend tidak dimuat halaman admin) */
.admin-sidebar .brand-mark {
  overflow: hidden;
  flex-shrink: 0;
  background: rgba(255, 255, 255, 0.12);
  display: flex;
  align-items: center;
  justify-content: center;
  border-radius: 12px;
}
.admin-sidebar .brand-mark img { width: 100%; height: 100%; object-fit: cover; display: block; }

.admin-group-label {
  color: #7C93AD;
  font-size: 0.66rem;
  font-weight: 700;
  text-transform: uppercase;
  letter-spacing: 0.14em;
  margin: 1.15rem 0 0.45rem;
  padding-left: 0.7rem;
  display: flex;
  align-items: center;
  gap: 0.45rem;
}
.admin-group-label::before {
  content: "";
  width: 5px; height: 5px;
  border-radius: 50%;
  background: var(--amber, #F5A524);
  opacity: 0.85;
}

.admin-link {
  display: flex;
  align-items: center;
  gap: 0.65rem;
  color: #C9D2DC;
  text-decoration: none;
  padding: 0.5rem 0.7rem;
  min-height: 40px;
  box-sizing: border-box;
  line-height: 1.4;
  border-radius: 10px;
  margin-bottom: 3px;
  font-size: 0.9rem;
  font-weight: 500;
  position: relative;
  transition: background .18s ease, color .18s ease, transform .18s ease;
}
.admin-link .admin-ico {
  width: 28px; height: 28px;
  border-radius: 8px;
  background: rgba(255, 255, 255, 0.07);
  display: flex; align-items: center; justify-content: center;
  font-size: 0.92rem;
  flex-shrink: 0;
  transition: background .18s ease, color .18s ease;
}
.admin-link:hover { background: rgba(255, 255, 255, 0.08); color: #fff; transform: translateX(2px); }
.admin-link:hover .admin-ico { background: rgba(255, 255, 255, 0.14); color: #fff; }

.admin-link.active {
  background: linear-gradient(90deg, rgba(37, 99, 235, 0.45) 0%, rgba(37, 99, 235, 0.14) 100%);
  color: #fff;
  font-weight: 600;
  box-shadow: inset 0 0 0 1px rgba(147, 197, 253, 0.25);
  /* Ukuran tidak boleh berubah saat aktif: kunci tinggi baris */
  min-height: 40px;
  box-sizing: border-box;
  line-height: 1.4;
}
.admin-link.active::before {
  content: "";
  position: absolute;
  left: -12px; top: 20%;
  width: 4px; height: 60%;
  border-radius: 0 6px 6px 0;
  background: var(--amber, #F5A524);
}
.admin-link.active .admin-ico { background: #2563EB; color: #fff; }

.admin-badge {
  margin-left: auto;
  min-width: 22px;
  padding: 0.1rem 0.45rem;
  border-radius: 999px;
  background: var(--amber, #F5A524);
  color: #0B2E52;
  font-size: 0.7rem;
  font-weight: 800;
  text-align: center;
  line-height: 1.5;
}

.admin-foot {
  border-top: 1px solid rgba(255, 255, 255, 0.1);
  padding-top: 0.6rem;
  margin-top: 0.6rem;
}
.admin-foot .admin-link { margin-bottom: 3px; }
.admin-link-view { color: #9FD3FF; }
.admin-link-view:hover { color: #fff; }
.admin-link-logout { color: #FFC46B; }
.admin-link-logout .admin-ico { background: rgba(245, 165, 36, 0.14); color: #FFC46B; }
.admin-link-logout:hover { background: rgba(245, 165, 36, 0.18); color: #FFD9A3; }

@media (max-width: 767.98px) {
  .admin-sidebar { width: 210px; }
}
</style>
