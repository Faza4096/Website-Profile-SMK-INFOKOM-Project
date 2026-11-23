<!-- ============ CTA PPDB di atas footer (disembunyikan di halaman PPDB agar tidak dobel) ============ -->
<?php if (($current ?? basename($_SERVER['PHP_SELF'])) !== 'ppdb.php'): ?>
<section class="py-5">
  <div class="container">
    <div class="ppdb-banner p-5 d-flex flex-column flex-md-row align-items-center justify-content-between gap-4">
      <div>
        <span class="badge-bkk badge-amber mb-2 d-inline-block">Pendaftaran Dibuka</span>
        <h3 class="mb-2" style="color:#fff;">Penerimaan Peserta Didik Baru</h3>
        <p class="mb-0" style="color:#C9DED2;">Pendaftaran dilakukan sepenuhnya online melalui formulir di halaman PPDB.</p>
      </div>
      <a href="ppdb.php" class="btn btn-signal px-4 py-2">Daftar Sekarang</a>
    </div>
  </div>
</section>
<?php endif; ?>

<!-- ============ FOOTER ============ -->
<footer class="site-footer pt-5 pb-0">
  <div class="container">
    <div class="row g-4 pb-4">
      <div class="col-lg-4 col-md-6">
        <div class="d-flex align-items-center gap-2 mb-3">
          <span class="brand-mark" style="width:42px;height:42px;">
            <img src="assets/img/logo.png" alt="Logo" onerror="this.style.display='none'; this.parentElement.innerHTML='SI';">
          </span>
          <span class="fw-bold text-white" style="font-family:'Sora',sans-serif;">SMK INFOKOM BOGOR</span>
        </div>
        <p class="small mb-0">Mencetak generasi unggul di bidang teknologi informasi dan komunikasi, siap kerja dan berdaya saing global.</p>
        <p class="small mt-3 mb-0">&copy; <?= date('Y') ?> SMK INFOKOM BOGOR. Hak cipta dilindungi.</p>
      </div>
      <div class="col-lg-2 col-md-3">
        <h6>Profil</h6>
        <ul class="list-unstyled small">
          <li><a href="profil.php?jenis=sejarah" class="footer-link">Sejarah</a></li>
          <li><a href="profil.php?jenis=visi_misi" class="footer-link">Visi &amp; Misi</a></li>
          <li><a href="profil.php?jenis=sambutan" class="footer-link">Sambutan Kepala Sekolah</a></li>
          <li><a href="profil.php?jenis=struktur" class="footer-link">Struktur Organisasi</a></li>
          <li><a href="guru-staff.php" class="footer-link">Guru &amp; Staff</a></li>
          <li><a href="profil.php?jenis=fasilitas" class="footer-link">Fasilitas Sekolah</a></li>
        </ul>
      </div>
      <div class="col-lg-2 col-md-3">
        <h6>Program Mitra</h6>
        <ul class="list-unstyled small">
          <li><a href="bkk-mitra.php" class="footer-link">Mitra Industri</a></li>
          <li><a href="mitra-universitas.php" class="footer-link">Mitra Universitas</a></li>
          <li><a href="bkk-lowongan.php" class="footer-link">Lowongan Kerja</a></li>
          <li><a href="bkk-lowongan.php?tipe=Magang" class="footer-link">Program Magang</a></li>
          <li><a href="bkk-lowongan.php?tipe=PKL" class="footer-link">Program PKL</a></li>
        </ul>
      </div>
      <div class="col-lg-2 col-md-3">
        <h6>Informasi</h6>
        <ul class="list-unstyled small">
          <li><a href="berita.php" class="footer-link">Berita</a></li>
          <li><a href="pengumuman.php" class="footer-link">Pengumuman</a></li>
          <li><a href="agenda.php" class="footer-link">Agenda</a></li>
          <li><a href="prestasi.php" class="footer-link">Prestasi</a></li>
          <li><a href="ekstrakurikuler.php" class="footer-link">Ekstrakurikuler</a></li>
          <li><a href="galeri.php" class="footer-link">Galeri</a></li>
        </ul>
      </div>
      <div class="col-lg-2 col-md-3">
        <h6>Kontak</h6>
        <ul class="list-unstyled small footer-contact mb-3">
          <li><i class="bi bi-geo-alt-fill"></i> Jl. Letjen Ibrahim Adjie No.178, Kota Bogor</li>
          <li><i class="bi bi-telephone-fill"></i> 0251-8348-108</li>
          <li><i class="bi bi-envelope-fill"></i> smk.infokom@gmail.com</li>
        </ul>
        <div class="d-flex gap-2">
          <a href="https://m.facebook.com/SMK-Infokom-105202311143756/?tsid=0.5200740061378188&amp;source=result" class="social-btn" aria-label="Facebook"><i class="bi bi-facebook"></i></a>
          <a href="https://www.instagram.com/smk.infokom_official" class="social-btn" aria-label="Instagram"><i class="bi bi-instagram"></i></a>
          <a href="https://www.youtube.com/channel/UCM1wHznYF69jhzSvrR5bKGQ" class="social-btn" aria-label="YouTube"><i class="bi bi-youtube"></i></a>
          <a href="https://www.tiktok.com/@smk_infokom_kota_bogor" class="social-btn" aria-label="TikTok" target="_blank" rel="noopener"><i class="bi bi-tiktok"></i></a>
        </div>
      </div>
    </div>
  </div>
  <div class="footer-bottom py-3">
    <div class="container d-flex flex-column flex-md-row justify-content-between gap-2">
      <span>Dikelola oleh BKK &amp; Tim Media SMK INFOKOM BOGOR</span>
      <span>SMK INFOKOM BOGOR &middot; NPSN 20212345</span>
    </div>
  </div>
</footer>

<button class="back-to-top" id="backToTop" aria-label="Kembali ke atas"><i class="bi bi-arrow-up"></i></button>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="assets/js/main.js"></script>
</body>
</html>
