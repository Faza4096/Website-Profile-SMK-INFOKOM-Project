<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../../includes/koneksi.php';

// ---------------------------------------------------------
// Tabel latar beranda (hero) dibuat otomatis — aman dipakai
// tanpa menjalankan migrasi SQL secara manual.
// ---------------------------------------------------------
try {
    $pdo->exec("CREATE TABLE IF NOT EXISTS hero_bg (
        id INT AUTO_INCREMENT PRIMARY KEY,
        gambar VARCHAR(255) NOT NULL,
        urutan INT DEFAULT 0,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB");
} catch (Throwable $e) {
    // biarkan; error akan muncul saat query berikutnya
}

// ---------------------------------------------------------
// Aksi kelola latar beranda
// ---------------------------------------------------------
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['aksi_hero'] ?? '') === 'upload') {
    $berkas = $_FILES['gambar'] ?? [];
    $jumlah = is_array($berkas['name'] ?? null) ? count($berkas['name']) : 0;
    $dir    = __DIR__ . '/../../uploads/hero';
    if (!is_dir($dir)) { @mkdir($dir, 0755, true); }

    $maks  = (int)($pdo->query("SELECT COALESCE(MAX(urutan),0) FROM hero_bg")->fetchColumn() ?? 0);
    $masuk = 0;
    $tolak = [];

    for ($i = 0; $i < $jumlah; $i++) {
        $nama  = trim($berkas['name'][$i] ?? '');
        $error = $berkas['error'][$i] ?? UPLOAD_ERR_NO_FILE;
        if ($nama === '' || $error === UPLOAD_ERR_NO_FILE) { continue; }
        if ($error !== UPLOAD_ERR_OK) { $tolak[] = $nama . ' (gagal diunggah)'; continue; }

        $ext = strtolower(pathinfo($nama, PATHINFO_EXTENSION));
        if (!in_array($ext, ['jpg', 'jpeg', 'png', 'webp'])) {
            $tolak[] = $nama . ' (format harus jpg/jpeg/png/webp)';
            continue;
        }
        if (($berkas['size'][$i] ?? 0) > 5 * 1024 * 1024) {
            $tolak[] = $nama . ' (maksimal 5MB)';
            continue;
        }

        $fileBaru = 'hero_' . time() . '_' . uniqid() . '.' . $ext;
        if (move_uploaded_file($berkas['tmp_name'][$i], $dir . '/' . $fileBaru)) {
            $maks++;
            $stmt = $pdo->prepare("INSERT INTO hero_bg (gambar, urutan) VALUES (?, ?)");
            $stmt->execute([$fileBaru, $maks]);
            $masuk++;
        } else {
            $tolak[] = $nama . ' (gagal disimpan)';
        }
    }

    if ($masuk > 0 && !$tolak) {
        header('Location: list.php?sukses=' . urlencode($masuk . ' foto latar berhasil diupload'));
        exit;
    }
    if ($masuk > 0) {
        header('Location: list.php?sukses=' . urlencode($masuk . ' foto masuk, ditolak: ' . implode('; ', $tolak)));
        exit;
    }
    header('Location: list.php?error=' . urlencode($tolak ? implode('; ', $tolak) : 'Tidak ada foto yang diunggah.'));
    exit;
}

if (isset($_GET['hero_aksi'])) {
    $aksi = $_GET['hero_aksi'];
    $id   = (int)($_GET['id'] ?? 0);

    if ($aksi === 'hapus') {
        $stmt = $pdo->prepare("SELECT gambar FROM hero_bg WHERE id = ?");
        $stmt->execute([$id]);
        if ($row = $stmt->fetch()) {
            $pdo->prepare("DELETE FROM hero_bg WHERE id = ?")->execute([$id]);
            $file = __DIR__ . '/../../uploads/hero/' . $row['gambar'];
            if (is_file($file)) { @unlink($file); }
            header('Location: list.php?sukses=' . urlencode('Foto latar dihapus.'));
            exit;
        }
    } elseif ($aksi === 'naik' || $aksi === 'turun') {
        $urut = $pdo->query("SELECT id FROM hero_bg ORDER BY urutan ASC, id ASC")->fetchAll(PDO::FETCH_COLUMN);
        $pos  = array_search($id, $urut, true);
        $tuju = $aksi === 'naik' ? $pos - 1 : $pos + 1;
        if ($pos !== false && $tuju >= 0 && $tuju < count($urut)) {
            [$urut[$pos], $urut[$tuju]] = [$urut[$tuju], $urut[$pos]];
            $stmt = $pdo->prepare("UPDATE hero_bg SET urutan = ? WHERE id = ?");
            foreach ($urut as $i => $idBaris) { $stmt->execute([$i + 1, $idBaris]); }
            header('Location: list.php?sukses=' . urlencode('Urutan latar diperbarui.'));
            exit;
        }
    }
    header('Location: list.php?error=' . urlencode('Aksi latar tidak dikenal.'));
    exit;
}

$profil = $pdo->query("SELECT * FROM profil ORDER BY FIELD(jenis,'sejarah','visi_misi','sambutan','struktur','fasilitas')")->fetchAll();

$labelJenis = [
    'sejarah'   => 'Sejarah Sekolah',
    'visi_misi' => 'Visi & Misi',
    'sambutan'  => 'Sambutan Kepala Sekolah',
    'struktur'  => 'Struktur Organisasi',
    'fasilitas' => 'Fasilitas Sekolah',
];

// Daftar foto latar beranda (hero slideshow)
$heroList = [];
try {
    $heroList = $pdo->query("SELECT * FROM hero_bg ORDER BY urutan ASC, id ASC")->fetchAll();
} catch (Throwable $e) {
    $heroList = [];
}

$pageTitleAdmin = 'Kelola Profil Sekolah';
require_once __DIR__ . '/../includes/top.php';
?>

    <h4 class="mb-4">Kelola Profil Sekolah</h4>

    <?php if (isset($_GET['sukses'])): ?>
      <div class="alert alert-success"><?= htmlspecialchars($_GET['sukses']) ?></div>
    <?php endif; ?>
    <?php if (isset($_GET['error'])): ?>
      <div class="alert alert-danger"><?= htmlspecialchars($_GET['error']) ?></div>
    <?php endif; ?>

    <div class="card card-admin">
      <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
          <thead>
            <tr>
              <th>#</th>
              <th>Kategori</th>
              <th>Judul</th>
              <th>Ringkasan Isi</th>
              <th class="text-end">Aksi</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($profil as $i => $p): ?>
            <tr>
              <td><?= $i + 1 ?></td>
              <td><span class="badge bg-primary"><?= htmlspecialchars($labelJenis[$p['jenis']] ?? $p['jenis']) ?></span></td>
              <td><?= htmlspecialchars($p['judul']) ?></td>
              <td class="text-muted small"><?= htmlspecialchars(mb_strimwidth($p['isi'], 0, 80, '...')) ?></td>
              <td class="text-end">
                <a href="edit.php?id=<?= $p['id'] ?>" class="btn btn-sm btn-outline-warning"><i class="bi bi-pencil-square"></i> Edit</a>
              </td>
            </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
    <p class="text-muted small mt-3">Kategori profil bersifat tetap (tidak bisa tambah/hapus baris baru), Anda hanya perlu mengedit isinya sesuai kebutuhan sekolah.</p>

    <!-- ============ Latar belakang beranda (hero slideshow) ============ -->
    <hr class="my-4">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-2">
      <h5 class="mb-0"><i class="bi bi-easel-fill"></i> Latar Belakang Beranda</h5>
      <span class="text-muted small"><i class="bi bi-arrow-repeat"></i> Gambar berganti otomatis tiap 6 detik di halaman beranda</span>
    </div>
    <p class="text-muted small mb-3">Upload foto latar (disarankan lanskap, minimal 1600px lebar, maksimal 5MB per file). Bila tidak ada foto sama sekali, beranda memakai foto sekolah bawaan.</p>

    <div class="card card-admin p-4 mb-4">
      <form method="POST" enctype="multipart/form-data" class="row g-2 align-items-end">
        <input type="hidden" name="aksi_hero" value="upload">
        <div class="col-md-8">
          <label class="form-label fw-semibold">Foto Latar (boleh pilih lebih dari satu)</label>
          <input type="file" name="gambar[]" class="form-control" accept=".jpg,.jpeg,.png,.webp" multiple required>
        </div>
        <div class="col-md-4">
          <button type="submit" class="btn btn-primary text-white w-100"><i class="bi bi-upload"></i> Upload Latar</button>
        </div>
      </form>
    </div>

    <?php if (!$heroList): ?>
      <div class="empty-state"><i class="bi bi-image-alt"></i>Belum ada foto latar — beranda memakai foto sekolah bawaan.</div>
    <?php else: ?>
      <div class="row g-3">
        <?php foreach ($heroList as $iHero => $h): ?>
        <div class="col-6 col-md-3 col-xl-2">
          <div class="card card-admin h-100">
            <img src="../../uploads/hero/<?= htmlspecialchars($h['gambar']) ?>"
                 style="height:110px;object-fit:cover;" class="card-img-top"
                 onerror="this.src='https://placehold.co/300x200?text=Gagal+Muat'">
            <div class="card-body p-2 text-center">
              <div class="small fw-semibold mb-2">Tampil ke-<?= $iHero + 1 ?></div>
              <div class="d-flex justify-content-center gap-1">
                <a href="list.php?hero_aksi=naik&id=<?= $h['id'] ?>" class="btn btn-sm btn-outline-secondary"
                   title="Naik urutan" <?= $iHero === 0 ? 'aria-disabled="true"' : '' ?>><i class="bi bi-arrow-up"></i></a>
                <a href="list.php?hero_aksi=turun&id=<?= $h['id'] ?>" class="btn btn-sm btn-outline-secondary"
                   title="Turun urutan" <?= $iHero === count($heroList) - 1 ? 'aria-disabled="true"' : '' ?>><i class="bi bi-arrow-down"></i></a>
                <a href="list.php?hero_aksi=hapus&id=<?= $h['id'] ?>" class="btn btn-sm btn-outline-danger"
                   title="Hapus foto" onclick="return confirm('Yakin ingin menghapus foto latar ini?');"><i class="bi bi-trash"></i></a>
              </div>
            </div>
          </div>
        </div>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>
<?php require_once __DIR__ . '/../includes/bottom.php'; ?>
