<?php
$pageTitle = 'Kontak - SMK INFOKOM BOGOR';
$pageDesc = 'Kontak SMK INFOKOM BOGOR: alamat Jl. Letjen Ibrahim Adjie No.178 Kota Bogor, telepon 0251-8348-108, dan formulir pesan online.';
require_once __DIR__ . '/includes/header.php';
?>

<div class="page-header">
  <div class="container">
    <div class="page-breadcrumb"><a href="index.php">Beranda</a> <i class="bi bi-chevron-right"></i> Kontak</div>
    <h1 class="page-title">Kontak Kami</h1>
  </div>
</div>

<div class="container py-5">
  <div class="row g-4">
    <div class="col-lg-5">
      <div class="card-editorial p-4 h-100">
        <h5 class="text-ink mb-4">Informasi Kontak</h5>

        <div class="d-flex mb-3">
          <i class="bi bi-geo-alt-fill fs-5 me-3" style="color:var(--blue);"></i>
          <div>
            <strong>Alamat</strong>
            <p class="text-muted small mb-0">Jl. Letjen Ibrahim Adjie No.178, RT.03/RW.08, Sindangbarang, Kec. Bogor Bar., Kota Bogor, Jawa Barat 16117</p>
          </div>
        </div>

        <div class="d-flex mb-3">
          <i class="bi bi-telephone-fill fs-5 me-3" style="color:var(--blue);"></i>
          <div>
            <strong>Telepon</strong>
            <p class="text-muted small mb-0">0251-8348-108</p>
            <p class="text-muted small mb-0">0878-7307-1400 (Humas SMK INFOKOM)</p>
          </div>
        </div>

        <div class="d-flex mb-3">
          <i class="bi bi-envelope-fill fs-5 me-3" style="color:var(--blue);"></i>
          <div>
            <strong>Email</strong>
            <p class="text-muted small mb-0">smk.infokom@gmail.com</p>
          </div>
        </div>

        <div class="d-flex mb-4">
          <i class="bi bi-clock-fill fs-5 me-3" style="color:var(--blue);"></i>
          <div>
            <strong>Jam Operasional</strong>
            <p class="text-muted small mb-0">Senin - Jumat: 07.00 - 17.00 WIB</p>
          </div>
        </div>

        <strong class="mb-2 d-block">Ikuti Kami</strong>
        <div class="d-flex gap-2">
          <a href="https://m.facebook.com/SMK-Infokom-105202311143756/?tsid=0.5200740061378188&amp;source=result" class="social-btn" style="background:var(--blue-deep);"><i class="bi bi-facebook"></i></a>
          <a href="https://www.instagram.com/smk.infokom_official" class="social-btn" style="background:var(--blue-deep);"><i class="bi bi-instagram"></i></a>
          <a href="https://www.youtube.com/channel/UCM1wHznYF69jhzSvrR5bKGQ" class="social-btn" style="background:var(--blue-deep);" aria-label="YouTube"><i class="bi bi-youtube"></i></a>
          <a href="https://www.tiktok.com/@smk_infokom_kota_bogor" class="social-btn" style="background:var(--blue-deep);" aria-label="TikTok" target="_blank" rel="noopener"><i class="bi bi-tiktok"></i></a>
        </div>
      </div>
    </div>

    <div class="col-lg-7">
      <div class="card-editorial h-100 overflow-hidden">
        <iframe
          src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2356.730553041629!2d106.76593950878123!3d-6.58051932614142!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e69c4f906e40827%3A0x6b93a4462670547c!2sSMK%20Infokom%20Kota%20Bogor!5e0!3m2!1sid!2sid!4v1790259498314!5m2!1sid!2sid"
          width="100%" height="100%" style="border:0; min-height:420px;"
          allowfullscreen loading="lazy" referrerpolicy="no-referrer-when-downgrade">
        </iframe>
      </div>
    </div>
  </div>

  <!-- Form Kontak (opsional, kirim ke email admin) -->
  <div class="card-editorial p-4 mt-4">
    <h5 class="text-ink mb-3">Kirim Pesan</h5>

    <?php if (isset($_GET['sukses'])): ?>
      <div class="alert alert-success">Pesan Anda berhasil dikirim. Terima kasih!</div>
    <?php elseif (isset($_GET['gagal'])): ?>
      <div class="alert alert-danger">Mohon lengkapi semua kolom dengan benar.</div>
    <?php endif; ?>

    <form action="proses-kontak.php" method="POST" class="row g-3">
      <div class="col-md-6">
        <label class="form-label small fw-semibold">Nama</label>
        <input type="text" name="nama" class="form-control" required>
      </div>
      <div class="col-md-6">
        <label class="form-label small fw-semibold">Email</label>
        <input type="email" name="email" class="form-control" required>
      </div>
      <div class="col-12">
        <label class="form-label small fw-semibold">Pesan</label>
        <textarea name="pesan" rows="4" class="form-control" required></textarea>
      </div>
      <div class="col-12">
        <button type="submit" class="btn btn-primary px-4">Kirim Pesan</button>
      </div>
    </form>
  </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
