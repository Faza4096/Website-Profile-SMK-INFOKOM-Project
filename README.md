# Website SMK INFOKOM BOGOR

## Identitas Visual
Desain memakai tema **"Campus Blue"** — aksen biru khas SMK Infokom (sekolah TIK), tampilan bersih dan profesional khas website sekolah modern:
- Warna: biru institusi pekat (`#0B2E52`), latar biru muda (`#F6F9FC`), aksen biru brand (`#1E66E5`), aksen amber CTA (`#F5A524`), aksen cyan BKK (`#06B6D4`)
- Tipografi: `Sora` untuk judul, `Plus Jakarta Sans` untuk isi
- Topbar info kontak + navbar pill dengan dropdown berikon, hero gradient dengan pola titik, kartu rounded dengan hover halus, tombol pill, timeline berbadge, tombol back-to-top
- Hero simetris dengan klaster kartu statistik glassmorphism + slideshow latar beranda (foto diatur di admin: Konten Profil -> Latar Belakang Beranda, berganti otomatis tiap 6 detik)
- Halaman BKK memakai aksen cyan (keluarga biru) agar terlihat khas sebagai "gerbang karier"

## Struktur Folder
```
smkinfokom/
├── database.sql          # Skema lengkap (instalasi baru)
├── db_update.sql         # Migrasi tambahan (jalankan ini saja jika sudah punya data)
├── db_ekstrakurikuler.sql # Tabel ekstrakurikuler + data contoh
├── index.php, profil.php, akademik.php, kurikulum.php, kegiatan.php
├── guru-staff.php, prestasi.php, pengumuman.php, agenda.php
├── berita.php, berita-detail.php, galeri.php, kontak.php
├── ekstrakurikuler.php   # Kegiatan ekstrakurikuler (data dari tabel ekstrakurikuler)
├── pkl.php               # Tentang PKL: pengertian, tujuan, jadwal, alur kegiatan
├── ppdb.php               # Form pendaftaran online sungguhan (bukan link WhatsApp) + banner poster/brosur dari uploads/ppdb/
├── proses-ppdb.php        # Handler simpan pendaftaran ke database
├── proses-kontak.php
├── bkk.php                # Tentang BKK (Bursa Kerja Khusus) + statistik + alur
├── bkk-lowongan.php       # Info lowongan kerja/magang untuk alumni (filter tipe)
├── bkk-mitra.php          # Perusahaan mitra industri BKK
├── bkk-daftar.php         # Form pendaftaran lamaran alumni (pilih lowongan + upload CV)
├── proses-bkk.php         # Handler simpan lamaran ke database (kode lamaran otomatis)
├── includes/ (koneksi.php, header.php, footer.php)
├── assets/ (css/style.css, js/main.js, img/)
├── uploads/ (berita, galeri, jurusan, profil, guru, struktur, fasilitas, prestasi, kurikulum, kegiatan, ppdb, bkk, hero, ekstrakurikuler)
└── admin/
    ├── login.php, logout.php, index.php (dashboard, dengan notifikasi BKK)
    ├── includes/ (auth.php, sidebar.php, top.php, bottom.php — layout bersama)
    ├── berita/, jurusan/, galeri/, profil/          (modul lama)
    ├── guru/, struktur/, fasilitas/                 (Profil Sekolah)
    ├── akademik/kurikulum/, akademik/kegiatan/       (Akademik)
    ├── pengumuman/, agenda/, prestasi/, ekstrakurikuler/ (Informasi)
    ├── ppdb/ (list.php, detail.php, hapus.php)       (Data pendaftar PPDB)
    └── bkk/                                          (Modul BKK)
        ├── lowongan/ (list, tambah, edit, hapus)     — kelola lowongan kerja
        ├── mitra/    (list, tambah, edit, hapus)     — kelola perusahaan mitra
        └── pelamar/  (list, detail, hapus)           — data lamaran alumni + ubah status
```

## Instalasi
1. Salin folder `smkinfokom` ke `htdocs`/`www`.
2. **Jika instalasi baru**: import `database.sql`.
   **Jika sudah punya data (upgrade)**: cukup jalankan `db_update.sql` di phpMyAdmin — data lama Anda (berita, jurusan, dsb) tidak akan terhapus, dan tabel BKK baru otomatis dibuat beserta contoh datanya.
3. Jalankan `db_ekstrakurikuler.sql` (tabel ekstrakurikuler + contoh data).
4. Sesuaikan kredensial di `includes/koneksi.php`.
5. Ganti `assets/img/logo.png` dengan logo sekolah Anda (dipakai di topbar/navbar, footer, halaman login, dan dashboard admin — otomatis muncul di semua tempat begitu file ini diganti).
6. Pastikan folder `uploads/bkk/`, `uploads/ekstrakurikuler/`, dan `uploads/ppdb/` ada dan writable (poster PPDB cukup upload gambar `poster.jpg` dari Dashboard admin).
6. Akses publik: `http://localhost/smkinfokom/`
   Akses admin: `http://localhost/smkinfokom/admin/login.php` (`admin` / `admin123`, segera ganti — lihat `admin/generate_hash.php`)

## Menu Navbar
- **Profil**: Sejarah, Visi & Misi, Sambutan, Struktur Organisasi, Guru & Staff, Fasilitas Sekolah (paling bawah)
- **Akademik**: Jurusan, Kurikulum (+ struktur pembelajaran & mata pelajaran per jurusan), Kegiatan Akademik, PKL
- **Informasi**: Berita, Pengumuman, Agenda, Prestasi, Ekstrakurikuler, Galeri
- **Program Mitra** (BKK): Tentang Mitra, Lowongan Kerja, Program Magang, Program PKL
- **PPDB**: form pendaftaran online (tersimpan ke database, dengan nomor pendaftaran otomatis) + banner poster/brosur yang bisa diunggah dari dashboard admin
- **Kontak**

Kolom footer disamakan dengan menu navbar, dan tombol sosial (Facebook, Instagram, YouTube, TikTok) tampil di topbar, footer, dan halaman Kontak.

## Fitur BKK (Bursa Kerja Khusus)
- Lowongan kerja/magang untuk alumni dengan tipe (Full-time, Part-time, Kontrak, Magang, PKL), gaji, kuota, batas daftar, dan status buka/tutup
- Lowongan yang sudah lewat batas daftar otomatis tidak bisa dilamar dan ditandai "Ditutup" di halaman publik
- Direktori perusahaan mitra (logo, bidang usaha, alamat, keterangan kerja sama)
- Form pendaftaran alumni online: pilih lowongan aktif, data alumni, upload CV (PDF/JPG maks 2MB), nomor lamaran otomatis `BKK-TAHUN-XXXXX`
- Admin mengelola lowongan & mitra (CRUD penuh) serta memproses pelamar (status: baru → diproses → lolos/tidak lolos)
- Lowongan terbaru otomatis tampil di beranda

## Modul Admin
Semua modul di atas punya CRUD penuh di dashboard admin (kecuali PPDB & BKK-Pelamar yang hanya lihat/verifikasi/hapus, karena diisi publik):
`admin/guru/`, `admin/struktur/`, `admin/fasilitas/`, `admin/pengumuman/`, `admin/agenda/`, `admin/prestasi/`, `admin/ekstrakurikuler/`, `admin/akademik/kurikulum/`, `admin/akademik/kegiatan/`, `admin/ppdb/`, `admin/bkk/lowongan/`, `admin/bkk/mitra/`, `admin/bkk/pelamar/`

Dashboard admin juga memuat: pengaturan angka statistik beranda, grafik siswa per jurusan, notifikasi pendaftar PPDB & pelamar BKK baru, serta upload/hapus poster-brosur PPDB.

## Keamanan
- Semua query pakai **Prepared Statements (PDO)**
- Password admin di-hash **bcrypt**, session di-regenerate saat login
- Semua halaman admin dilindungi `auth.php`, dan path navigasi admin otomatis terdeteksi (aman diakses dari sub-folder manapun)
- Output HTML di-escape `htmlspecialchars()`, validasi ekstensi file upload
- Form kontak & PPDB divalidasi sebelum disimpan ke database
