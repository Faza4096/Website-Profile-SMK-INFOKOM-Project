<?php
$pageTitle = 'PPDB Online - SMK INFOKOM BOGOR';
$pageDesc = 'PPDB online SMK INFOKOM BOGOR: pendaftaran peserta didik baru tahun ajaran 2026/2027 dilakukan sepenuhnya online.';
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/migrasi-ppdb.php';

$jurusan = $pdo->query("SELECT * FROM jurusan ORDER BY nama_jurusan ASC")->fetchAll();
$sukses = $_GET['sukses'] ?? null;

// ---- Cari pendaftar berdasarkan kode — dipakai panel sukses & popup cek status ----
$cariPendaftar = function (string $kode) use ($pdo) {
    $stmt = $pdo->prepare("SELECT p.*, j.nama_jurusan FROM pendaftaran_ppdb p
                           LEFT JOIN jurusan j ON p.jurusan_pilihan = j.id
                           WHERE p.nomor_pendaftaran = ? LIMIT 1");
    $stmt->execute([$kode]);
    return $stmt->fetch() ?: false;
};

// Cek status pendaftaran dengan kode (nomor_pendaftaran)
$kodeCek    = strtoupper(trim($_GET['cek'] ?? ''));
$hasilCek   = $kodeCek !== '' ? $cariPendaftar($kodeCek) : null; // null = belum dicari, false = tidak ditemukan
$dataSukses = ($sukses !== null && $sukses !== '') ? $cariPendaftar(strtoupper(trim($sukses))) : null;

// Poster/brosur PPDB: ambil gambar dari uploads/ppdb/ (prioritas poster/brosur, lalu gambar pertama).
// Jika belum ada file, blok ini tidak ditampilkan (tidak ada kotak kosong).
$posterPPDB = null;
foreach (['poster.jpg', 'poster.png', 'poster.webp', 'poster.jpeg', 'brosur.jpg', 'brosur.png', 'brosur.webp', 'brosur.jpeg'] as $fPoster) {
    if (is_file(__DIR__ . '/uploads/ppdb/' . $fPoster)) { $posterPPDB = $fPoster; break; }
}
if ($posterPPDB === null) {
    foreach ((glob(__DIR__ . '/uploads/ppdb/*.{jpg,jpeg,png,webp}', GLOB_BRACE) ?: []) as $pathPoster) {
        $posterPPDB = basename($pathPoster);
        break;
    }
}
?>

<div class="page-header">
  <div class="container">
    <div class="page-breadcrumb"><a href="index.php">Beranda</a> <i class="bi bi-chevron-right"></i> PPDB</div>
    <h1 class="page-title">Penerimaan Peserta Didik Baru</h1>
  </div>
</div>

<section class="py-5">
  <div class="container">

    <?php if ($sukses): ?>
      <div class="card-editorial p-4 mb-5" style="border-left:4px solid var(--blue);">
        <h5 class="mb-2"><i class="bi bi-check-circle-fill" style="color:var(--blue);"></i> Pendaftaran Berhasil Dikirim</h5>
        <p class="mb-1">Kode pendaftaran Anda: <strong class="fs-5" style="letter-spacing:0.06em;"><?= htmlspecialchars($sukses) ?></strong></p>
        <?php if ($dataSukses): ?>
        <!-- Data diri ikut ditampilkan agar peserta bisa memastikan datanya -->
        <table class="table table-borderless mb-2" style="font-size:0.92rem;">
          <tr><th width="180">Nama Lengkap</th><td><?= htmlspecialchars($dataSukses['nama_lengkap']) ?></td></tr>
          <tr><th>Jenis Kelamin</th><td><?= htmlspecialchars($dataSukses['jenis_kelamin']) ?></td></tr>
          <tr><th>No. HP / WhatsApp</th><td><?= htmlspecialchars($dataSukses['no_hp']) ?></td></tr>
          <tr><th>Jurusan Pilihan</th><td><?= htmlspecialchars($dataSukses['nama_jurusan'] ?? '-') ?></td></tr>
          <tr><th>Tanggal Daftar</th><td><?= date('d M Y H:i', strtotime($dataSukses['created_at'])) ?></td></tr>
        </table>
        <?php endif; ?>
        <p class="small text-muted-ink mb-2">Simpan kode ini untuk keperluan verifikasi. Panitia PPDB akan menghubungi Anda melalui nomor HP / email yang didaftarkan.</p>
        <a href="ppdb.php?cek=<?= urlencode($sukses) ?>" class="btn btn-sm btn-signal"><i class="bi bi-search me-1"></i> Cek Status dengan Kode Ini</a>
      </div>
    <?php endif; ?>

    <?php if ($kodeCek !== ''): ?>
    <!-- ==== Popup hasil cek status — menggantikan panel keterangan di atas halaman ==== -->
    <div class="modal fade" id="modalHasilCek" tabindex="-1" aria-labelledby="modalHasilCekLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius:14px; overflow:hidden;">
          <div class="modal-header" style="background:<?= $hasilCek ? 'linear-gradient(120deg, var(--blue-deep) 0%, var(--blue) 100%)' : 'var(--amber, #F5A524)' ?>; color:#fff; border:none;">
            <h6 class="modal-title" id="modalHasilCekLabel">
              <i class="bi <?= $hasilCek ? 'bi-clipboard-check' : 'bi-exclamation-triangle-fill' ?> me-2"></i><?= $hasilCek ? 'Status Pendaftaran' : 'Kode Tidak Ditemukan' ?>
            </h6>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup" style="filter:invert(1);"></button>
          </div>
          <div class="modal-body p-4">
            <?php if ($hasilCek === false): ?>
              <p class="mb-0 small">Kode pendaftaran <strong><?= htmlspecialchars($kodeCek) ?></strong> tidak ditemukan. Periksa kembali kode Anda (format: PPDB-2026-XXXXX).</p>
            <?php else: ?>
              <?php
                $petaStatus = [
                    'menunggu'    => ['label' => 'Menunggu',    'warna' => 'secondary', 'icon' => 'bi-hourglass-split', 'pesan' => 'Pendaftaran Anda sedang menunggu verifikasi oleh panitia.'],
                    'diverifikasi'=> ['label' => 'Diverifikasi','warna' => 'info',      'icon' => 'bi-check-circle',   'pesan' => 'Berkas Anda sudah diverifikasi, menunggu hasil seleksi akhir.'],
                    'diterima'    => ['label' => 'Diterima',    'warna' => 'success',   'icon' => 'bi-award-fill',     'pesan' => 'Selamat! Anda diterima sebagai siswa SMK INFOKOM BOGOR. Silakan hubungi panitia untuk daftar ulang.'],
                    'ditolak'     => ['label' => 'Ditolak',     'warna' => 'danger',    'icon' => 'bi-x-circle-fill',  'pesan' => 'Mohon maaf, pendaftaran Anda tidak diterima pada tahap ini.'],
                ];
                $st = $petaStatus[$hasilCek['status']] ?? $petaStatus['menunggu'];
              ?>
              <div class="mb-3">
                <span class="badge bg-<?= $st['warna'] ?>"><i class="bi <?= $st['icon'] ?> me-1"></i><?= $st['label'] ?></span>
              </div>
              <table class="table table-borderless mb-2" style="font-size:0.92rem;">
                <tr><th width="170">Kode Pendaftaran</th><td><code><?= htmlspecialchars($hasilCek['nomor_pendaftaran']) ?></code></td></tr>
                <tr><th>Nama</th><td><?= htmlspecialchars($hasilCek['nama_lengkap']) ?></td></tr>
                <tr><th>Jurusan Pilihan</th><td><?= htmlspecialchars($hasilCek['nama_jurusan'] ?? '-') ?></td></tr>
                <tr><th>Tanggal Daftar</th><td><?= date('d M Y H:i', strtotime($hasilCek['created_at'])) ?></td></tr>
              </table>
              <p class="small mb-0"><i class="bi <?= $st['icon'] ?> me-1"></i><?= htmlspecialchars($st['pesan']) ?></p>
              <?php if ($hasilCek['status'] === 'ditolak' && trim((string)($hasilCek['alasan'] ?? '')) !== ''): ?>
                <div class="alert alert-danger py-2 px-3 mb-0 mt-3 small" style="border-radius:8px;">
                  <strong><i class="bi bi-chat-left-text me-1"></i>Alasan penolakan:</strong><br>
                  <?= nl2br(htmlspecialchars($hasilCek['alasan'])) ?>
                </div>
              <?php endif; ?>
            <?php endif; ?>
          </div>
          <div class="modal-footer py-2">
            <button type="button" class="btn btn-sm btn-outline-ink" data-bs-dismiss="modal">Tutup</button>
          </div>
        </div>
      </div>
    </div>
    <script>
    (function () {
      // Bootstrap dimuat di footer — tunggu ketersediaannya lalu buka popup,
      // tanpa bergantung pada urutan eksekusi skrip.
      var percobaan = 0;
      (function buka() {
        var el = document.getElementById('modalHasilCek');
        if (el && window.bootstrap) {
          bootstrap.Modal.getOrCreateInstance(el).show();
        } else if (++percobaan < 50) {
          setTimeout(buka, 100);
        }
      })();
    })();
    </script>
    <?php endif; ?>

    <?php if ($posterPPDB): ?>
    <!-- ==== Poster / brosur PPDB — banner lebar penuh di atas formulir ==== -->
    <div class="mb-5 text-center">
      <div class="card-editorial p-3">
        <img src="uploads/ppdb/<?= htmlspecialchars($posterPPDB) ?>" alt="Poster / Brosur PPDB SMK INFOKOM BOGOR"
             class="img-fluid w-100 rounded" style="cursor:zoom-in; max-height:620px; object-fit:contain;"
             data-bs-toggle="modal" data-bs-target="#modalPosterPPDB">
      </div>
      <p class="small text-muted-ink mt-2 mb-0">
        <button class="btn btn-link btn-sm p-0 text-decoration-none" data-bs-toggle="modal" data-bs-target="#modalPosterPPDB">
          <i class="bi bi-zoom-in me-1"></i>Klik poster untuk memperbesar
        </button>
      </p>
    </div>
    <?php endif; ?>

    <div class="row g-5">
      <div class="col-lg-5">
        <span class="kicker">Tahun Ajaran 2026/2027</span>
        <h2 class="mb-3">Daftar Menjadi Bagian dari SMK INFOKOM BOGOR</h2>
        <p class="text-muted-ink">Lengkapi formulir pendaftaran di samping. Seluruh proses pendaftaran dilakukan online melalui halaman ini, tanpa perlu menghubungi pihak manapun terlebih dahulu.</p>

        <div class="ppdb-steps mt-4">
          <div class="d-flex gap-3 mb-3">
            <span class="step-num">1</span>
            <div><strong>Isi Formulir</strong><p class="small text-muted-ink mb-0">Lengkapi data diri dan pilihan jurusan.</p></div>
          </div>
          <div class="d-flex gap-3 mb-3">
            <span class="step-num">2</span>
            <div><strong>Upload Berkas</strong><p class="small text-muted-ink mb-0">Unggah dokumen pendukung (ijazah/SKL, KK).</p></div>
          </div>
          <div class="d-flex gap-3 mb-3">
            <span class="step-num">3</span>
            <div><strong>Verifikasi</strong><p class="small text-muted-ink mb-0">Panitia memverifikasi data dan berkas Anda.</p></div>
          </div>
          <div class="d-flex gap-3">
            <span class="step-num">4</span>
            <div><strong>Pengumuman &amp; Daftar Ulang</strong><p class="small text-muted-ink mb-0">Hasil seleksi dicek sendiri dengan kode pendaftaran di kotak "Cek Status".</p></div>
          </div>
        </div>

        <!-- ==== Cek status — digeser ke dasar kolom kiri, setelah alur pendaftaran ==== -->
        <!-- h-auto: card-editorial default height:100% (untuk grid); tanpa ini kartu setinggi kolom
             dan mendorong konten lain keluar section hingga melewati footer. -->
        <div class="card-editorial p-4 mt-5 h-auto" id="cek-status">
          <h5 class="mb-2"><i class="bi bi-search" style="color:var(--blue);"></i> Cek Status Pendaftaran</h5>
          <p class="small text-muted-ink">Sudah mendaftar? Masukkan kode pendaftaran Anda (format: PPDB-2026-XXXXX) untuk melihat status: menunggu, diverifikasi, diterima, atau ditolak.</p>
          <form method="get" class="d-flex gap-2">
            <input type="text" name="cek" class="form-control" placeholder="PPDB-2026-XXXXX"
                   value="<?= htmlspecialchars($kodeCek) ?>" required>
            <button type="submit" class="btn btn-signal text-nowrap">Cek Status</button>
          </form>
          <p class="small text-muted-ink mt-2 mb-0"><i class="bi bi-info-circle me-1"></i>Kode diberikan otomatis setelah pendaftaran terkirim.</p>
        </div>

      </div>

      <div class="col-lg-7">
        <div class="card-editorial p-4">
          <form action="proses-ppdb.php" method="POST" enctype="multipart/form-data" class="row g-3">
            <div class="col-md-8">
              <label class="form-label small fw-semibold">Nama Lengkap</label>
              <input type="text" name="nama_lengkap" class="form-control" required>
            </div>
            <div class="col-md-4">
              <label class="form-label small fw-semibold">Jenis Kelamin</label>
              <select name="jenis_kelamin" class="form-select" required>
                <option value="Laki-laki">Laki-laki</option>
                <option value="Perempuan">Perempuan</option>
              </select>
            </div>

            <div class="col-md-6">
              <label class="form-label small fw-semibold">NISN</label>
              <input type="text" name="nisn" class="form-control">
            </div>
            <div class="col-md-6">
              <label class="form-label small fw-semibold">Asal Sekolah</label>
              <input type="text" name="asal_sekolah" class="form-control" required>
            </div>

            <div class="col-md-6">
              <label class="form-label small fw-semibold">Tempat Lahir</label>
              <input type="text" name="tempat_lahir" class="form-control">
            </div>
            <div class="col-md-6">
              <label class="form-label small fw-semibold">Tanggal Lahir</label>
              <input type="date" name="tanggal_lahir" class="form-control">
            </div>

            <div class="col-md-6">
              <label class="form-label small fw-semibold">Jurusan Pilihan</label>
              <select name="jurusan_pilihan" class="form-select" required>
                <option value="">-- Pilih Jurusan --</option>
                <?php foreach ($jurusan as $j): ?>
                  <option value="<?= $j['id'] ?>"><?= htmlspecialchars($j['nama_jurusan']) ?> (<?= htmlspecialchars($j['singkatan']) ?>)</option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="col-md-6">
              <label class="form-label small fw-semibold">Nama Orang Tua/Wali</label>
              <input type="text" name="nama_orangtua" class="form-control">
            </div>

            <div class="col-md-6">
              <label class="form-label small fw-semibold">No. HP / WhatsApp</label>
              <input type="text" name="no_hp" class="form-control" required>
            </div>
            <div class="col-md-6">
              <label class="form-label small fw-semibold">Email</label>
              <input type="email" name="email" class="form-control">
            </div>

            <div class="col-12">
              <label class="form-label small fw-semibold">Alamat</label>
              <textarea name="alamat" rows="2" class="form-control"></textarea>
            </div>

            <div class="col-12">
              <label class="form-label small fw-semibold">Upload Berkas (Ijazah/SKL/KK &mdash; PDF/JPG, maks 2MB)</label>
              <input type="file" name="berkas_dokumen" class="form-control" accept=".pdf,.jpg,.jpeg,.png">
            </div>

            <div class="col-12 mt-3">
              <button type="submit" class="btn btn-signal px-4">Kirim Pendaftaran</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</section>

<?php if (!empty($posterPPDB)): ?>
<div class="modal fade" id="modalPosterPPDB" tabindex="-1" aria-labelledby="modalPosterPPDBLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header">
        <h6 class="modal-title" id="modalPosterPPDBLabel">Poster / Brosur PPDB</h6>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
      </div>
      <div class="modal-body p-2 text-center">
        <img src="uploads/ppdb/<?= htmlspecialchars($posterPPDB) ?>" class="img-fluid w-100 rounded" alt="Poster / Brosur PPDB SMK INFOKOM BOGOR">
      </div>
    </div>
  </div>
</div>
<?php endif; ?>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
