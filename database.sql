-- =========================================================
-- DATABASE: db_smkinfokom
-- Website Profil Sekolah SMK INFOKOM BOGOR
-- =========================================================

CREATE DATABASE IF NOT EXISTS db_smkinfokom
  CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;

USE db_smkinfokom;

-- ---------------------------------------------------------
-- Tabel: users (akun admin)
-- ---------------------------------------------------------
CREATE TABLE users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(50) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL, -- disimpan dengan password_hash()
  nama_lengkap VARCHAR(100) NOT NULL,
  level ENUM('superadmin','admin') NOT NULL DEFAULT 'admin',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Password default = "admin123" (sudah di-hash bcrypt)
INSERT INTO users (username, password, nama_lengkap, level) VALUES
('admin', '$2b$12$W95THwpRffjtYC6aDjHUNO7BzBMektX.DP27kkJgmELgwmzaG3Rpq', 'Administrator', 'superadmin');

-- ---------------------------------------------------------
-- Tabel: profil (Sejarah, Visi Misi, Sambutan, Fasilitas, dll)
-- ---------------------------------------------------------
CREATE TABLE profil (
  id INT AUTO_INCREMENT PRIMARY KEY,
  jenis ENUM('sejarah','visi_misi','sambutan','struktur','fasilitas') NOT NULL UNIQUE,
  judul VARCHAR(150) NOT NULL,
  isi TEXT NOT NULL,
  gambar VARCHAR(255) DEFAULT NULL,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

INSERT INTO profil (jenis, judul, isi, gambar) VALUES
('sejarah', 'Sejarah Sekolah', 'SMK INFOKOM BOGOR berdiri dengan komitmen menyediakan pendidikan kejuruan berkualitas tinggi yang sesuai dengan kebutuhan industri dan perkembangan zaman. Sejak berdiri, kami terus berinovasi dan berkembang untuk menghasilkan lulusan terbaik yang siap kerja dan berkompeten di bidangnya.', NULL),
('visi_misi', 'Visi & Misi', 'Visi: Menjadi SMK unggulan berbasis teknologi informasi dan komunikasi yang menghasilkan lulusan kompeten, berkarakter, dan berdaya saing global.\nMisi: Menyelenggarakan pendidikan kejuruan berkualitas, menjalin kerja sama industri, dan membentuk karakter siswa yang unggul.', NULL),
('sambutan', 'Sambutan Kepala Sekolah', 'Selamat datang di website resmi SMK INFOKOM BOGOR. Kami berkomitmen mencetak generasi unggul yang siap menghadapi tantangan dunia kerja dan industri.', NULL),
('fasilitas', 'Fasilitas Sekolah', 'Laboratorium Komputer, Laboratorium Jaringan, Perpustakaan, Ruang Multimedia, Lapangan Olahraga, dan fasilitas penunjang lainnya.', NULL);

-- ---------------------------------------------------------
-- Tabel: jurusan
-- ---------------------------------------------------------
CREATE TABLE jurusan (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nama_jurusan VARCHAR(100) NOT NULL,
  singkatan VARCHAR(20) DEFAULT NULL,
  deskripsi TEXT,
  visi TEXT,
  misi TEXT,
  tujuan TEXT,
  gambar VARCHAR(255) DEFAULT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

INSERT INTO jurusan (nama_jurusan, singkatan, deskripsi) VALUES
('Rekayasa Perangkat Lunak', 'RPL', 'Kompetensi keahlian pengembangan aplikasi dan perangkat lunak.'),
('Teknik Komputer dan Jaringan', 'TKJ', 'Kompetensi keahlian instalasi dan administrasi jaringan komputer.'),
('Multimedia', 'MM', 'Kompetensi keahlian desain grafis, animasi, dan produksi multimedia.'),
('Sistem Informasi Jaringan dan Aplikasi', 'SIJA', 'Kompetensi keahlian integrasi jaringan dan aplikasi.');

-- ---------------------------------------------------------
-- Tabel: berita
-- ---------------------------------------------------------
CREATE TABLE berita (
  id INT AUTO_INCREMENT PRIMARY KEY,
  judul VARCHAR(200) NOT NULL,
  slug VARCHAR(220) NOT NULL UNIQUE,
  gambar VARCHAR(255) DEFAULT NULL,
  konten TEXT NOT NULL,
  penulis VARCHAR(100) DEFAULT 'Admin',
  status ENUM('draft','publish') NOT NULL DEFAULT 'publish',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

INSERT INTO berita (judul, slug, konten, penulis) VALUES
('Penerimaan Peserta Didik Baru Tahun Ajaran 2025/2026', 'ppdb-2025-2026', 'SMK INFOKOM BOGOR membuka pendaftaran peserta didik baru. Silakan daftar melalui halaman PPDB Online.', 'Admin'),
('Siswa SMK INFOKOM BOGOR Juara 1 Lomba LKS Tingkat Kota', 'juara-lks-tingkat-kota', 'Prestasi membanggakan diraih oleh siswa RPL dalam ajang Lomba Kompetensi Siswa (LKS) tingkat Kota Bogor.', 'Admin');

-- ---------------------------------------------------------
-- Tabel: galeri
-- ---------------------------------------------------------
CREATE TABLE galeri (
  id INT AUTO_INCREMENT PRIMARY KEY,
  judul VARCHAR(150) NOT NULL,
  gambar VARCHAR(255) NOT NULL,
  kategori VARCHAR(50) DEFAULT 'Kegiatan',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- ---------------------------------------------------------
-- Tabel: statistik (Siswa Aktif, Guru & Staff, Jurusan, Prestasi)
-- ---------------------------------------------------------
CREATE TABLE statistik (
  id INT AUTO_INCREMENT PRIMARY KEY,
  label VARCHAR(50) NOT NULL,
  jumlah INT NOT NULL DEFAULT 0,
  icon VARCHAR(50) DEFAULT NULL
) ENGINE=InnoDB;

INSERT INTO statistik (label, jumlah, icon) VALUES
('Siswa Aktif', 1250, 'bi-mortarboard-fill'),
('Guru & Staff', 75, 'bi-person-badge-fill'),
('Jurusan', 4, 'bi-journal-bookmark-fill'),
('Prestasi', 120, 'bi-trophy-fill');

-- ---------------------------------------------------------
-- Tabel: pengumuman & agenda (opsional, untuk grid "Informasi Terbaru")
-- ---------------------------------------------------------
CREATE TABLE pengumuman (
  id INT AUTO_INCREMENT PRIMARY KEY,
  judul VARCHAR(200) NOT NULL,
  isi TEXT,
  tanggal DATE NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE agenda (
  id INT AUTO_INCREMENT PRIMARY KEY,
  judul VARCHAR(200) NOT NULL,
  isi TEXT,
  tanggal DATE NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- ---------------------------------------------------------
-- Tabel: pesan_kontak (pesan masuk dari form Kontak)
-- ---------------------------------------------------------
CREATE TABLE pesan_kontak (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nama VARCHAR(100) NOT NULL,
  email VARCHAR(100) NOT NULL,
  pesan TEXT NOT NULL,
  status ENUM('belum_dibaca','sudah_dibaca') NOT NULL DEFAULT 'belum_dibaca',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;
USE db_smkinfokom;

-- ---------------------------------------------------------
-- Guru & Staff
-- ---------------------------------------------------------
CREATE TABLE IF NOT EXISTS guru_staff (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nama VARCHAR(100) NOT NULL,
  nip VARCHAR(50) DEFAULT NULL,
  jabatan VARCHAR(100) NOT NULL,
  bidang VARCHAR(100) DEFAULT NULL,
  foto VARCHAR(255) DEFAULT NULL,
  urutan INT DEFAULT 0,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- ---------------------------------------------------------
-- Struktur Organisasi (jabatan struktural sekolah)
-- ---------------------------------------------------------
CREATE TABLE IF NOT EXISTS struktur_organisasi (
  id INT AUTO_INCREMENT PRIMARY KEY,
  jabatan VARCHAR(100) NOT NULL,
  nama VARCHAR(100) NOT NULL,
  foto VARCHAR(255) DEFAULT NULL,
  urutan INT DEFAULT 0,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

INSERT INTO struktur_organisasi (jabatan, nama, urutan) VALUES
('Kepala Sekolah', 'Diisi oleh Admin', 1),
('Wakil Kepala Sekolah Kurikulum', 'Diisi oleh Admin', 2),
('Wakil Kepala Sekolah Kesiswaan', 'Diisi oleh Admin', 3),
('Kepala Tata Usaha', 'Diisi oleh Admin', 4);

-- ---------------------------------------------------------
-- Fasilitas Sekolah (list, sebelumnya cuma 1 blok teks)
-- ---------------------------------------------------------
CREATE TABLE IF NOT EXISTS fasilitas (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nama VARCHAR(150) NOT NULL,
  deskripsi TEXT,
  gambar VARCHAR(255) DEFAULT NULL,
  urutan INT DEFAULT 0,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

INSERT INTO fasilitas (nama, deskripsi, urutan) VALUES
('Laboratorium Komputer', 'Dilengkapi perangkat komputer terbaru untuk praktik pemrograman dan jaringan.', 1),
('Laboratorium Jaringan', 'Ruang praktik instalasi dan konfigurasi jaringan komputer.', 2),
('Perpustakaan', 'Koleksi buku pelajaran, referensi teknologi, dan ruang baca yang nyaman.', 3),
('Ruang Multimedia', 'Fasilitas produksi audio-visual dan desain grafis.', 4);

-- ---------------------------------------------------------
-- Prestasi
-- ---------------------------------------------------------
CREATE TABLE IF NOT EXISTS prestasi (
  id INT AUTO_INCREMENT PRIMARY KEY,
  judul VARCHAR(200) NOT NULL,
  deskripsi TEXT,
  tingkat ENUM('Sekolah','Kota/Kabupaten','Provinsi','Nasional','Internasional') NOT NULL DEFAULT 'Kota/Kabupaten',
  tahun YEAR NOT NULL,
  gambar VARCHAR(255) DEFAULT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

INSERT INTO prestasi (judul, deskripsi, tingkat, tahun) VALUES
('Juara 1 Lomba Kompetensi Siswa (LKS) Bidang IT Network', 'Diraih oleh siswa jurusan TKJ dalam ajang LKS tingkat kota.', 'Kota/Kabupaten', 2025);

-- ---------------------------------------------------------
-- Kurikulum
-- ---------------------------------------------------------
CREATE TABLE IF NOT EXISTS kurikulum (
  id INT AUTO_INCREMENT PRIMARY KEY,
  judul VARCHAR(200) NOT NULL,
  deskripsi TEXT,
  file_dokumen VARCHAR(255) DEFAULT NULL,
  gambar VARCHAR(255) DEFAULT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

INSERT INTO kurikulum (judul, deskripsi) VALUES
('Kurikulum Merdeka - Program Keahlian TIK', 'SMK INFOKOM BOGOR menerapkan Kurikulum Merdeka yang memadukan pembelajaran berbasis proyek dengan kompetensi industri, agar lulusan siap kerja maupun melanjutkan pendidikan.');

-- ---------------------------------------------------------
-- Kegiatan Akademik (PKL, UKK, Ujian, dsb)
-- ---------------------------------------------------------
CREATE TABLE IF NOT EXISTS kegiatan_akademik (
  id INT AUTO_INCREMENT PRIMARY KEY,
  judul VARCHAR(200) NOT NULL,
  deskripsi TEXT,
  tanggal DATE DEFAULT NULL,
  gambar VARCHAR(255) DEFAULT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

INSERT INTO kegiatan_akademik (judul, deskripsi, tanggal) VALUES
('Praktik Kerja Lapangan (PKL)', 'Program magang siswa tingkat akhir di perusahaan mitra industri selama satu semester.', NULL),
('Uji Kompetensi Keahlian (UKK)', 'Ujian praktik yang menguji kompetensi siswa sesuai standar industri sebelum kelulusan.', NULL);

-- ---------------------------------------------------------
-- Pendaftaran PPDB Online (form pendaftaran nyata, tanpa WA)
-- ---------------------------------------------------------
CREATE TABLE IF NOT EXISTS pendaftaran_ppdb (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nomor_pendaftaran VARCHAR(30) NOT NULL UNIQUE,
  nama_lengkap VARCHAR(100) NOT NULL,
  nisn VARCHAR(20) DEFAULT NULL,
  jenis_kelamin ENUM('Laki-laki','Perempuan') NOT NULL,
  tempat_lahir VARCHAR(100) DEFAULT NULL,
  tanggal_lahir DATE DEFAULT NULL,
  asal_sekolah VARCHAR(150) NOT NULL,
  jurusan_pilihan INT DEFAULT NULL,
  nama_orangtua VARCHAR(100) DEFAULT NULL,
  no_hp VARCHAR(20) NOT NULL,
  email VARCHAR(100) DEFAULT NULL,
  alamat TEXT,
  berkas_dokumen VARCHAR(255) DEFAULT NULL,
  status ENUM('menunggu','diverifikasi','ditolak','diterima') NOT NULL DEFAULT 'menunggu',
  alasan TEXT DEFAULT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (jurusan_pilihan) REFERENCES jurusan(id) ON DELETE SET NULL
) ENGINE=InnoDB;

-- ---------------------------------------------------------
-- BKK - Bursa Kerja Khusus: Lowongan Kerja
-- ---------------------------------------------------------
CREATE TABLE IF NOT EXISTS bkk_lowongan (
  id INT AUTO_INCREMENT PRIMARY KEY,
  posisi VARCHAR(150) NOT NULL,
  perusahaan VARCHAR(150) NOT NULL,
  lokasi VARCHAR(150) DEFAULT NULL,
  tipe_pekerjaan ENUM('Full-time','Part-time','Kontrak','Magang','Freelance') NOT NULL DEFAULT 'Full-time',
  gaji VARCHAR(100) DEFAULT NULL,
  deskripsi TEXT,
  kualifikasi TEXT,
  kuota INT DEFAULT NULL,
  batas_daftar DATE DEFAULT NULL,
  kontak VARCHAR(150) DEFAULT NULL,
  status ENUM('buka','tutup') NOT NULL DEFAULT 'buka',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

INSERT INTO bkk_lowongan (posisi, perusahaan, lokasi, tipe_pekerjaan, gaji, deskripsi, kualifikasi, kuota, status) VALUES
('Junior Web Developer', 'PT Cyber Media Indonesia', 'Kota Bogor', 'Full-time', 'Rp 4.500.000 - Rp 6.000.000', 'Mengembangkan dan memelihara aplikasi web berbasis PHP untuk kebutuhan internal perusahaan.', 'Lulusan SMK jurusan RPL/TKJ, menguasai PHP dasar, HTML, CSS, dan JavaScript.', 3, 'buka'),
('Teknisi Jaringan', 'CV Netmedia Solusi', 'Kota Depok', 'Kontrak', 'Rp 3.800.000', 'Instalasi, konfigurasi, dan troubleshooting jaringan klien perusahaan.', 'Lulusan SMK jurusan TKJ/SIJA, memahami perangkat Mikrotik, mampu bekerja di lapangan.', 5, 'buka');

-- ---------------------------------------------------------
-- BKK - Bursa Kerja Khusus: Perusahaan Mitra (industri)
-- ---------------------------------------------------------
CREATE TABLE IF NOT EXISTS bkk_mitra (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nama_perusahaan VARCHAR(150) NOT NULL,
  bidang VARCHAR(150) DEFAULT NULL,
  alamat VARCHAR(255) DEFAULT NULL,
  logo VARCHAR(255) DEFAULT NULL,
  keterangan TEXT,
  urutan INT DEFAULT 0,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

INSERT INTO bkk_mitra (nama_perusahaan, bidang, urutan) VALUES
('PT Cyber Media Indonesia', 'Pengembangan Perangkat Lunak', 1),
('CV Netmedia Solusi', 'Jaringan & Infrastruktur IT', 2),
('PT Kreatif Digital Nusantara', 'Multimedia & Desain Grafis', 3);

-- ---------------------------------------------------------
-- BKK - Bursa Kerja Khusus: Pendaftaran/Pelamar Alumni
-- ---------------------------------------------------------
CREATE TABLE IF NOT EXISTS bkk_pelamar (
  id INT AUTO_INCREMENT PRIMARY KEY,
  kode_lamaran VARCHAR(30) NOT NULL UNIQUE,
  lowongan_id INT NOT NULL,
  nama_lengkap VARCHAR(100) NOT NULL,
  tahun_lulus YEAR NOT NULL,
  jurusan VARCHAR(100) NOT NULL,
  no_hp VARCHAR(20) NOT NULL,
  email VARCHAR(100) DEFAULT NULL,
  alamat TEXT,
  cv_file VARCHAR(255) DEFAULT NULL,
  status ENUM('baru','diproses','lolos','tidak_lolos') NOT NULL DEFAULT 'baru',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (lowongan_id) REFERENCES bkk_lowongan(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- Tambah kolom gambar guru pada tabel users (opsional, untuk foto admin) -- dilewati agar aman.

-- ---------------------------------------------------------
-- Data Siswa (Kesiswaan) — DIHAPUS
-- Modul "Kelola Data Siswa" sudah dihapus; angka statistik beranda kini statis
-- dan diatur lewat panel admin (Dashboard -> Statistik Beranda).
DROP TABLE IF EXISTS siswa;

-- Data siswa lama (48 baris) dihapus — hanya disimpan sebagai komentar arsip di bawah ini.
/* Data isi tabel siswa ikut dihapus (dikomentari).
INSERT IGNORE INTO siswa (nis, nama, jenis_kelamin, kelas, jurusan_id, status) VALUES
('2023001','Rizky Fauzan','Laki-laki','XII',1,'aktif'),
('2023002','Bagus Saputra','Laki-laki','XII',1,'aktif'),
('2023003','Nadia Rahmawati','Perempuan','XII',1,'aktif'),
('2023004','Aisyah Permata','Perempuan','XII',1,'aktif'),
('2023005','Yoga Nugroho','Laki-laki','XII',2,'aktif'),
('2023006','Ahmad Prasetyo','Laki-laki','XII',2,'aktif'),
('2023007','Dewi Oktaviani','Perempuan','XII',2,'aktif'),
('2023008','Rina Anggraini','Perempuan','XII',2,'aktif'),
('2023009','Andi Fauzan','Laki-laki','XII',3,'aktif'),
('2023010','Ilham Saputra','Laki-laki','XII',3,'aktif'),
('2023011','Lestari Rahmawati','Perempuan','XII',3,'aktif'),
('2023012','Siti Permata','Perempuan','XII',3,'aktif'),
('2023013','Rizky Nugroho','Laki-laki','XII',4,'aktif'),
('2023014','Bagus Prasetyo','Laki-laki','XII',4,'aktif'),
('2023015','Nadia Oktaviani','Perempuan','XII',4,'aktif'),
('2023016','Aisyah Anggraini','Perempuan','XII',4,'aktif'),
('2024017','Yoga Fauzan','Laki-laki','XI',1,'aktif'),
('2024018','Ahmad Saputra','Laki-laki','XI',1,'aktif'),
('2024019','Dewi Rahmawati','Perempuan','XI',1,'aktif'),
('2024020','Rina Permata','Perempuan','XI',1,'aktif'),
('2024021','Andi Nugroho','Laki-laki','XI',2,'aktif'),
('2024022','Ilham Prasetyo','Laki-laki','XI',2,'aktif'),
('2024023','Lestari Oktaviani','Perempuan','XI',2,'aktif'),
('2024024','Siti Anggraini','Perempuan','XI',2,'aktif'),
('2024025','Rizky Fauzan','Laki-laki','XI',3,'aktif'),
('2024026','Bagus Saputra','Laki-laki','XI',3,'aktif'),
('2024027','Nadia Rahmawati','Perempuan','XI',3,'aktif'),
('2024028','Aisyah Permata','Perempuan','XI',3,'aktif'),
('2024029','Yoga Nugroho','Laki-laki','XI',4,'aktif'),
('2024030','Ahmad Prasetyo','Laki-laki','XI',4,'aktif'),
('2024031','Dewi Oktaviani','Perempuan','XI',4,'aktif'),
('2024032','Rina Anggraini','Perempuan','XI',4,'aktif'),
('2025033','Andi Fauzan','Laki-laki','X',1,'aktif'),
('2025034','Ilham Saputra','Laki-laki','X',1,'aktif'),
('2025035','Lestari Rahmawati','Perempuan','X',1,'aktif'),
('2025036','Siti Permata','Perempuan','X',1,'aktif'),
('2025037','Rizky Nugroho','Laki-laki','X',2,'aktif'),
('2025038','Bagus Prasetyo','Laki-laki','X',2,'aktif'),
('2025039','Nadia Oktaviani','Perempuan','X',2,'aktif'),
('2025040','Aisyah Anggraini','Perempuan','X',2,'aktif'),
('2025041','Yoga Fauzan','Laki-laki','X',3,'aktif'),
('2025042','Ahmad Saputra','Laki-laki','X',3,'aktif'),
('2025043','Dewi Rahmawati','Perempuan','X',3,'aktif'),
('2025044','Rina Permata','Perempuan','X',3,'aktif'),
('2025045','Andi Nugroho','Laki-laki','X',4,'aktif'),
('2025046','Ilham Prasetyo','Laki-laki','X',4,'aktif'),
('2025047','Lestari Oktaviani','Perempuan','X',4,'aktif'),
('2025048','Siti Anggraini','Perempuan','X',4,'aktif'); */

-- ---------------------------------------------------------
-- Latar beranda (hero slideshow): foto latar yang berganti-ganti
-- diatur lewat admin -> Konten Profil -> Latar Belakang Beranda
-- ---------------------------------------------------------
CREATE TABLE IF NOT EXISTS hero_bg (
  id INT AUTO_INCREMENT PRIMARY KEY,
  gambar VARCHAR(255) NOT NULL,
  urutan INT DEFAULT 0,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;
