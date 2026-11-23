<?php
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/../includes/koneksi.php';

// --- Upload / hapus poster-brosur PPDB (ditampilkan di halaman ppdb.php) ---
$pesanPoster = '';
$dirPoster   = __DIR__ . '/../uploads/ppdb/';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['poster_ppdb'])) {
    $aksiPoster = $_POST['poster_ppdb'];

    if ($aksiPoster === 'hapus') {
        // Hapus SEMUA gambar poster/brosur di folder uploads/ppdb/
        foreach ((glob($dirPoster . '*.{jpg,jpeg,png,webp}', GLOB_BRACE) ?: []) as $pathPoster) {
            unlink($pathPoster);
        }
        $pesanPoster = 'Poster/brosur PPDB berhasil dihapus.';
    } elseif ($aksiPoster === 'upload') {
        if (empty($_FILES['file_poster']['name'])) {
            $pesanPoster = 'Pilih file gambar terlebih dahulu.';
        } else {
            $ext = strtolower(pathinfo($_FILES['file_poster']['name'], PATHINFO_EXTENSION));
            $allowed = ['jpg', 'jpeg', 'png', 'webp'];
            if (!in_array($ext, $allowed)) {
                $pesanPoster = 'Format gambar tidak didukung (gunakan jpg/jpeg/png/webp).';
            } elseif ($_FILES['file_poster']['size'] > 2 * 1024 * 1024) {
                $pesanPoster = 'Ukuran gambar maksimal 2MB.';
            } else {
                // Ganti poster lama agar hanya ada satu gambar aktif
                foreach ((glob($dirPoster . '*.{jpg,jpeg,png,webp}', GLOB_BRACE) ?: []) as $pathPoster) {
                    unlink($pathPoster);
                }
                $namaFile = 'poster_' . time() . '.' . $ext;
                move_uploaded_file($_FILES['file_poster']['tmp_name'], $dirPoster . $namaFile);
                $pesanPoster = 'Poster/brosur PPDB berhasil diunggah.';
            }
        }
    }
}
// Poster aktif saat ini (logika sama dengan halaman ppdb.php)
$posterAktif = null;
foreach ((glob($dirPoster . '*.{jpg,jpeg,png,webp}', GLOB_BRACE) ?: []) as $pathPoster) {
    $posterAktif = basename($pathPoster);
    break;
}

/** Hitung aman: kembalikan 0 bila tabel belum ada. */
$hitung = function (string $sql) use ($pdo): int {
    try {
        return (int)$pdo->query($sql)->fetchColumn();
    } catch (Throwable $e) {
        return 0;
    }
};

$pendaftarBaru = $hitung("SELECT COUNT(*) FROM pendaftaran_ppdb WHERE status='menunggu'");
$lowonganAktif = $hitung("SELECT COUNT(*) FROM bkk_lowongan WHERE status='buka'");
$pelamarBaru   = $hitung("SELECT COUNT(*) FROM bkk_pelamar WHERE status='baru'");
$pesanBaru     = $hitung("SELECT COUNT(*) FROM pesan_kontak WHERE status='belum_dibaca'");

// --- Berita terbaru (panel pengganti angka statistik lama) ---
$beritaTerbaru = $pdo->query("SELECT id, judul, slug, penulis, status, created_at
                              FROM berita ORDER BY created_at DESC LIMIT 6")->fetchAll();

// --- Pendaftar PPDB terbaru ---
$ppdbTerbaru = $pdo->query("SELECT p.id, p.nomor_pendaftaran, p.nama_lengkap, p.status, p.created_at,
                                   j.nama_jurusan
                            FROM pendaftaran_ppdb p
                            LEFT JOIN jurusan j ON p.jurusan_pilihan = j.id
                            ORDER BY p.created_at DESC LIMIT 6")->fetchAll();

$badgeStatus = ['menunggu'=>'secondary','diverifikasi'=>'info','diterima'=>'success','ditolak'=>'danger'];
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Dashboard Admin</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Sora:wght@600;700&family=Plus+Jakarta+Sans:wght@400;500;600&display=swap" rel="stylesheet">
<style>
  body { font-family: 'Plus Jakarta Sans', sans-serif; background:#F3F1EC; color:#0F2A4A; }
  .bg-ink { background-color:#0F2A4A; }
  h4 { font-family:'Sora',sans-serif; font-weight:600; }
  .alert-signal { background:#FCEFD8; border:1px solid #E8A33D; color:#8a5a10; border-radius:6px; }
  .admin-panel-card { border:1px solid rgba(20,44,70,0.12); border-radius:6px; background:#fff; }
  .admin-panel-card h5 { font-family:'Sora',sans-serif; font-weight:600; font-size:1rem; }
  /* Quick access: kotak jalan pintas ke menu penting */
  .quick-tile {
    display:flex; align-items:center; gap:.75rem;
    border:1px solid rgba(20,44,70,0.12); border-radius:6px; background:#fff;
    padding:.85rem 1rem; height:100%;
    color:#0F2A4A; text-decoration:none; font-weight:600; font-size:.9rem;
    transition: transform .15s ease, box-shadow .15s ease, border-color .15s ease;
    position:relative;
  }
  .quick-tile:hover { transform:translateY(-2px); border-color:#2563EB; box-shadow:0 6px 16px rgba(15,42,70,.08); color:#2563EB; }
  .quick-tile .q-ico {
    width:38px; height:38px; border-radius:10px; flex-shrink:0;
    background:rgba(37,99,235,.1); color:#2563EB;
    display:flex; align-items:center; justify-content:center; font-size:1.1rem;
  }
  .quick-tile .q-badge {
    position:absolute; top:.45rem; right:.55rem;
    min-width:20px; padding:.05rem .4rem; border-radius:999px;
    background:#F5A524; color:#0B2E52; font-size:.66rem; font-weight:800; text-align:center;
  }
  .feed-item { border-bottom:1px solid rgba(20,44,70,.08); }
  .feed-item:last-child { border-bottom:0; }
</style>
</head>
<body>
<div class="d-flex">
  <?php include __DIR__ . '/includes/sidebar.php'; ?>

  <div class="flex-grow-1 p-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
      <h4 class="mb-0">Dashboard</h4>
      <span class="text-muted">Halo, <strong><?= htmlspecialchars($_SESSION['admin_nama']) ?></strong></span>
    </div>

    <?php if ($pendaftarBaru > 0): ?>
      <div class="alert-signal p-3 mb-3 d-flex justify-content-between align-items-center">
        <span><i class="bi bi-bell-fill"></i> Ada <strong><?= (int)$pendaftarBaru ?></strong> pendaftar PPDB baru yang menunggu verifikasi.</span>
        <a href="ppdb/list.php" class="btn btn-sm btn-outline-dark">Lihat</a>
      </div>
    <?php endif; ?>

    <?php if ($pelamarBaru > 0): ?>
      <div class="p-3 mb-4 d-flex justify-content-between align-items-center" style="background:#E0F5F2; border:1px solid #14B8A6; border-radius:6px;">
        <span><i class="bi bi-briefcase-fill" style="color:#0F766E;"></i> Ada <strong><?= (int)$pelamarBaru ?></strong> lamaran alumni BKK baru yang belum diproses &middot; <strong><?= (int)$lowonganAktif ?></strong> lowongan aktif.</span>
        <a href="bkk/pelamar/list.php" class="btn btn-sm btn-outline-dark">Lihat</a>
      </div>
    <?php endif; ?>

    <!-- ==== Quick Access: jalan pintas ke menu yang paling sering dipakai ==== -->
    <div class="admin-panel-card p-4 mb-4">
      <h5 class="mb-3"><i class="bi bi-lightning-charge-fill me-2" style="color:#F5A524;"></i>Quick Access</h5>
      <div class="row g-3">
        <div class="col-6 col-md-4 col-xl-3">
          <a class="quick-tile" href="ppdb/list.php"><span class="q-ico"><i class="bi bi-person-lines-fill"></i></span>Data Pendaftar PPDB</a>
        </div>
        <div class="col-6 col-md-4 col-xl-3">
          <a class="quick-tile" href="berita/tambah.php"><span class="q-ico"><i class="bi bi-pencil-square"></i></span>Tulis Berita</a>
        </div>
        <div class="col-6 col-md-4 col-xl-3">
          <a class="quick-tile" href="pengumuman/list.php"><span class="q-ico"><i class="bi bi-megaphone"></i></span>Pengumuman</a>
        </div>
        <div class="col-6 col-md-4 col-xl-3">
          <a class="quick-tile" href="kontak/list.php">
            <span class="q-ico"><i class="bi bi-envelope-arrow-down"></i></span>Pesan Kontak
            <?php if ($pesanBaru > 0): ?><span class="q-badge"><?= $pesanBaru ?></span><?php endif; ?>
          </a>
        </div>
        <div class="col-6 col-md-4 col-xl-3">
          <a class="quick-tile" href="bkk/lowongan/list.php"><span class="q-ico"><i class="bi bi-briefcase"></i></span>Daftar Lowongan</a>
        </div>
        <div class="col-6 col-md-4 col-xl-3">
          <a class="quick-tile" href="guru/list.php"><span class="q-ico"><i class="bi bi-person-badge"></i></span>Guru &amp; Staff</a>
        </div>
        <div class="col-6 col-md-4 col-xl-3">
          <a class="quick-tile" href="galeri/list.php"><span class="q-ico"><i class="bi bi-images"></i></span>Galeri</a>
        </div>
        <div class="col-6 col-md-4 col-xl-3">
          <a class="quick-tile" href="../" target="_blank" rel="noopener"><span class="q-ico"><i class="bi bi-globe2"></i></span>Lihat Situs</a>
        </div>
      </div>
    </div>

    <?php if ($pesanPoster): ?><div class="alert alert-info py-2 mb-3" style="max-width:640px;"><?= htmlspecialchars($pesanPoster) ?></div><?php endif; ?>

    <div class="row g-3 mb-4 align-items-stretch">
      <!-- ==== Berita terbaru ==== -->
      <div class="col-lg-7">
        <div class="admin-panel-card p-4 h-100">
          <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="mb-0"><i class="bi bi-newspaper me-2" style="color:#2563EB;"></i>Berita Terbaru</h5>
            <a href="berita/list.php" class="btn btn-sm btn-outline-secondary">Kelola</a>
          </div>
          <?php if (!$beritaTerbaru): ?>
            <p class="text-muted small mb-0">Belum ada berita. Mulai tulis berita lewat menu Berita.</p>
          <?php else: ?>
            <?php foreach ($beritaTerbaru as $b): ?>
            <div class="feed-item d-flex justify-content-between align-items-center py-2 gap-2">
              <div class="pe-2">
                <a href="berita/edit.php?id=<?= (int)$b['id'] ?>" class="text-decoration-none fw-semibold d-block"><?= htmlspecialchars(mb_strimwidth($b['judul'], 0, 70, '...')) ?></a>
                <small class="text-muted"><?= date('d M Y', strtotime($b['created_at'])) ?> &middot; <?= htmlspecialchars($b['penulis']) ?></small>
              </div>
              <span class="badge <?= $b['status']==='publish' ? 'bg-success' : 'bg-secondary' ?>"><?= $b['status']==='publish' ? 'Publish' : 'Draft' ?></span>
            </div>
            <?php endforeach; ?>
          <?php endif; ?>
        </div>
      </div>

      <!-- ==== Pendaftar PPDB terbaru ==== -->
      <div class="col-lg-5">
        <div class="admin-panel-card p-4 h-100">
          <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="mb-0"><i class="bi bi-person-lines-fill me-2" style="color:#2563EB;"></i>Pendaftar PPDB Terbaru</h5>
            <a href="ppdb/list.php" class="btn btn-sm btn-outline-secondary">Semua</a>
          </div>
          <?php if (!$ppdbTerbaru): ?>
            <p class="text-muted small mb-0">Belum ada pendaftar PPDB.</p>
          <?php else: ?>
            <?php foreach ($ppdbTerbaru as $p): ?>
            <div class="feed-item d-flex justify-content-between align-items-center py-2 gap-2">
              <div class="pe-2">
                <a href="ppdb/detail.php?id=<?= (int)$p['id'] ?>" class="text-decoration-none fw-semibold d-block"><?= htmlspecialchars(mb_strimwidth($p['nama_lengkap'], 0, 40, '...')) ?></a>
                <small class="text-muted"><code><?= htmlspecialchars($p['nomor_pendaftaran']) ?></code> &middot; <?= htmlspecialchars($p['nama_jurusan'] ?? 'Jurusan belum dipilih') ?></small>
              </div>
              <span class="badge bg-<?= $badgeStatus[$p['status']] ?? 'secondary' ?>"><?= htmlspecialchars(ucfirst($p['status'])) ?></span>
            </div>
            <?php endforeach; ?>
          <?php endif; ?>
        </div>
      </div>
    </div>

    <!-- ==== Poster / brosur PPDB ==== -->
    <div class="row g-3 mb-4">
      <div class="col-12">
        <div class="admin-panel-card p-4">
          <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="mb-0"><i class="bi bi-image-alt me-2" style="color:#2563EB;"></i>Poster / Brosur PPDB</h5>
          </div>

          <div class="row align-items-center">
            <div class="col-md-3 mb-3 mb-md-0">
              <?php if ($posterAktif): ?>
                <img src="../uploads/ppdb/<?= htmlspecialchars($posterAktif) ?>" alt="Poster PPDB"
                     class="rounded w-100" style="max-height:220px; object-fit:contain; border:1px solid rgba(20,44,70,0.12); background:#fff;">
              <?php else: ?>
                <div class="text-center text-muted small py-4" style="border:1px dashed rgba(20,44,70,0.25); border-radius:6px;">
                  <i class="bi bi-image fs-3 d-block mb-1"></i>Belum ada poster
                </div>
              <?php endif; ?>
            </div>
            <div class="col-md-9">
              <form method="POST" enctype="multipart/form-data" class="d-flex flex-column flex-md-row gap-2 align-items-md-center">
                <input type="hidden" name="poster_ppdb" value="upload">
                <input type="file" name="file_poster" class="form-control" style="max-width:340px;" accept=".jpg,.jpeg,.png,.webp" required>
                <button type="submit" class="btn btn-primary text-white"><i class="bi bi-upload"></i> Unggah Poster</button>
              </form>
              <?php if ($posterAktif): ?>
              <form method="POST" class="mt-2" onsubmit="return confirm('Yakin ingin menghapus poster PPDB saat ini?');">
                <input type="hidden" name="poster_ppdb" value="hapus">
                <button type="submit" class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i> Hapus poster saat ini</button>
              </form>
              <?php endif; ?>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
</body>
</html>
