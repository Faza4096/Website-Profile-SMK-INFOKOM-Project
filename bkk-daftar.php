<?php
$pageTitle = 'Pendaftaran Alumni - BKK SMK INFOKOM BOGOR';
require_once __DIR__ . '/includes/header.php';

$sukses = $_GET['sukses'] ?? null;

$lowongan = $pdo->query("SELECT * FROM bkk_lowongan WHERE status='buka' ORDER BY created_at DESC")->fetchAll();
$lowonganAktif = $pdo->query("SELECT * FROM bkk_lowongan WHERE status='buka' AND (batas_daftar IS NULL OR batas_daftar >= CURDATE()) ORDER BY created_at DESC")->fetchAll();

$preselect = (int)($_GET['lowongan'] ?? 0);
?>

<div class="page-header bkk-hero">
  <div class="container">
    <div class="page-breadcrumb"><a href="index.php">Beranda</a> <i class="bi bi-chevron-right"></i> Program Mitra <i class="bi bi-chevron-right"></i> Pendaftaran Alumni</div>
    <h1 class="page-title">Pendaftaran Alumni</h1>
    <p class="page-title-sub mt-2">Lamar posisi yang tersedia melalui formulir online BKK SMK INFOKOM BOGOR.</p>
  </div>
</div>

<section class="py-5">
  <div class="container">

    <?php if ($sukses): ?>
      <div class="card-editorial p-4 mb-5" style="border-left:4px solid var(--teal-deep);">
        <h5 class="mb-2"><i class="bi bi-check-circle-fill" style="color:var(--teal-deep);"></i> Lamaran Berhasil Dikirim</h5>
        <p class="mb-1">Nomor lamaran Anda: <strong><?= htmlspecialchars($sukses) ?></strong></p>
        <p class="small text-muted-ink mb-0">Simpan nomor ini sebagai bukti pendaftaran. Petugas BKK akan memverifikasi lamaran Anda dan meneruskannya ke perusahaan terkait. Hasil seleksi diumumkan melalui no. HP / email yang Anda daftarkan.</p>
      </div>
    <?php endif; ?>

    <div class="row g-5">
      <div class="col-lg-5">
        <span class="kicker kicker-teal">Syarat &amp; Ketentuan</span>
        <h2 class="mb-3">Lamaran Hanya untuk Lowongan Aktif</h2>
        <p class="text-muted-ink">Formulir ini diperuntukkan bagi alumni SMK INFOKOM BOGOR. Pastikan Anda memilih lowongan yang masih buka, melengkapi data diri, dan mengunggah CV dalam format PDF/JPG (maksimal 2MB).</p>

        <div class="info-tile mt-4">
          <h6 class="mb-3"><i class="bi bi-briefcase-fill me-2" style="color:var(--teal-deep);"></i>Lowongan yang Dapat Dilamar (<?= count($lowonganAktif) ?>)</h6>
          <?php if ($lowonganAktif): ?>
            <ul class="list-unstyled small mb-0">
              <?php foreach ($lowonganAktif as $l): ?>
              <li class="d-flex justify-content-between align-items-center border-bottom py-2">
                <div>
                  <strong><?= htmlspecialchars($l['posisi']) ?></strong>
                  <div class="text-muted-ink"><?= htmlspecialchars($l['perusahaan']) ?></div>
                </div>
                <span class="badge-bkk"><?= htmlspecialchars($l['tipe_pekerjaan']) ?></span>
              </li>
              <?php endforeach; ?>
            </ul>
          <?php else: ?>
            <p class="small text-muted-ink mb-0">Belum ada lowongan aktif. Pantau halaman <a href="bkk-lowongan.php">Info Lowongan</a>.</p>
          <?php endif; ?>
        </div>
      </div>

      <div class="col-lg-7">
        <div class="card-editorial p-4">
          <?php if (count($lowonganAktif) === 0): ?>
            <div class="empty-state mb-0"><i class="bi bi-briefcase"></i>Saat ini belum ada lowongan yang bisa dilamar.</div>
          <?php else: ?>
          <form action="proses-bkk.php" method="POST" enctype="multipart/form-data" class="row g-3">
            <div class="col-12">
              <label class="form-label small fw-semibold">Pilih Lowongan</label>
              <select name="lowongan_id" class="form-select" required>
                <option value="">-- Pilih Lowongan --</option>
                <?php foreach ($lowonganAktif as $l): ?>
                  <option value="<?= $l['id'] ?>" <?= $preselect === (int)$l['id'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($l['posisi']) ?> &mdash; <?= htmlspecialchars($l['perusahaan']) ?>
                  </option>
                <?php endforeach; ?>
              </select>
            </div>

            <div class="col-md-8">
              <label class="form-label small fw-semibold">Nama Lengkap</label>
              <input type="text" name="nama_lengkap" class="form-control" required>
            </div>
            <div class="col-md-4">
              <label class="form-label small fw-semibold">Tahun Lulus</label>
              <input type="number" name="tahun_lulus" class="form-control" min="2000" max="<?= date('Y') ?>" value="<?= date('Y') - 1 ?>" required>
            </div>

            <div class="col-md-6">
              <label class="form-label small fw-semibold">Jurusan Saat Sekolah</label>
              <input type="text" name="jurusan" class="form-control" placeholder="Contoh: Rekayasa Perangkat Lunak" required>
            </div>
            <div class="col-md-6">
              <label class="form-label small fw-semibold">No. HP / WhatsApp</label>
              <input type="text" name="no_hp" class="form-control" required>
            </div>

            <div class="col-md-6">
              <label class="form-label small fw-semibold">Email</label>
              <input type="email" name="email" class="form-control">
            </div>
            <div class="col-md-6">
              <label class="form-label small fw-semibold">Upload CV (PDF/JPG, maks 2MB)</label>
              <input type="file" name="cv_file" class="form-control" accept=".pdf,.jpg,.jpeg,.png">
            </div>

            <div class="col-12">
              <label class="form-label small fw-semibold">Alamat</label>
              <textarea name="alamat" rows="2" class="form-control"></textarea>
            </div>

            <div class="col-12 mt-3">
              <button type="submit" class="btn btn-teal px-4">Kirim Lamaran</button>
            </div>
          </form>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
