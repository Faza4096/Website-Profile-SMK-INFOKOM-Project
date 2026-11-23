-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Sep 24, 2026 at 03:52 PM
-- Server version: 8.4.3
-- PHP Version: 8.3.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_smkinfokom`
--

-- --------------------------------------------------------

--
-- Table structure for table `agenda`
--

CREATE TABLE `agenda` (
  `id` int NOT NULL,
  `judul` varchar(200) COLLATE utf8mb4_general_ci NOT NULL,
  `isi` text COLLATE utf8mb4_general_ci,
  `tanggal` date NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `agenda`
--

INSERT INTO `agenda` (`id`, `judul`, `isi`, `tanggal`, `created_at`) VALUES
(1, 'Shalat Dhuha Bersama', 'Pelaksanaan shalat Dhuha di jumat pagi', '2026-09-25', '2026-09-24 15:32:20'),
(2, 'Pembiasaan diri', 'Pembiasaan diri karakter siswa/siswi SMK INFOKOM setiap hari agar bisa jauh lebih disiplin', '2026-09-25', '2026-09-24 15:34:54');

-- --------------------------------------------------------

--
-- Table structure for table `berita`
--

CREATE TABLE `berita` (
  `id` int NOT NULL,
  `judul` varchar(200) COLLATE utf8mb4_general_ci NOT NULL,
  `slug` varchar(220) COLLATE utf8mb4_general_ci NOT NULL,
  `gambar` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `konten` text COLLATE utf8mb4_general_ci NOT NULL,
  `penulis` varchar(100) COLLATE utf8mb4_general_ci DEFAULT 'Admin',
  `status` enum('draft','publish') COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'publish',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `berita`
--

INSERT INTO `berita` (`id`, `judul`, `slug`, `gambar`, `konten`, `penulis`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Penerimaan Peserta Didik Baru Tahun Ajaran 2025/2026', 'ppdb-2025-2026', 'berita_1790260947.jpg', 'SMK INFOKOM BOGOR membuka pendaftaran peserta didik baru. Silakan daftar melalui halaman PPDB Online.', 'SMK INFOKOM', 'publish', '2026-09-13 06:19:47', '2026-09-24 14:42:27'),
(3, 'Sumatif Tengah Semester (STS) Tahun Ajaran 2025-2026', 'sumatif-tengah-semester-sts-tahun-ajaran-2025-2026-96045', 'berita_1790260935.jpg', 'Pelaksanaan Sumatif  Tengah Semester (STS) yang dilaksanakan di sekolah, peserta diwajibkan memakai komputer untuk ujian soal', 'SMK INFOKOM', 'publish', '2026-09-13 08:32:40', '2026-09-24 14:42:15');

-- --------------------------------------------------------

--
-- Table structure for table `bkk_lowongan`
--

CREATE TABLE `bkk_lowongan` (
  `id` int NOT NULL,
  `posisi` varchar(150) NOT NULL,
  `perusahaan` varchar(150) NOT NULL,
  `lokasi` varchar(150) DEFAULT NULL,
  `tipe_pekerjaan` enum('Full-time','Part-time','Kontrak','Magang','Freelance') NOT NULL DEFAULT 'Full-time',
  `gaji` varchar(100) DEFAULT NULL,
  `deskripsi` text,
  `kualifikasi` text,
  `kuota` int DEFAULT NULL,
  `batas_daftar` date DEFAULT NULL,
  `kontak` varchar(150) DEFAULT NULL,
  `status` enum('buka','tutup') NOT NULL DEFAULT 'buka',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `bkk_lowongan`
--

INSERT INTO `bkk_lowongan` (`id`, `posisi`, `perusahaan`, `lokasi`, `tipe_pekerjaan`, `gaji`, `deskripsi`, `kualifikasi`, `kuota`, `batas_daftar`, `kontak`, `status`, `created_at`) VALUES
(1, 'Junior Web Developer', 'PT Cyber Media Indonesia', 'Kota Bogor', 'Full-time', 'Rp 4.500.000 - Rp 6.000.000', 'Mengembangkan dan memelihara aplikasi web berbasis PHP untuk kebutuhan internal perusahaan.', 'Lulusan SMK jurusan RPL/TKJ, menguasai PHP dasar, HTML, CSS, dan JavaScript.', 3, NULL, NULL, 'buka', '2026-09-24 11:12:22'),
(2, 'Teknisi Jaringan', 'CV Netmedia Solusi', 'Kota Depok', 'Kontrak', 'Rp 3.800.000', 'Instalasi, konfigurasi, dan troubleshooting jaringan klien perusahaan.', 'Lulusan SMK jurusan TKJ/SIJA, memahami perangkat Mikrotik, mampu bekerja di lapangan.', 5, NULL, NULL, 'buka', '2026-09-24 11:12:22');

-- --------------------------------------------------------

--
-- Table structure for table `bkk_mitra`
--

CREATE TABLE `bkk_mitra` (
  `id` int NOT NULL,
  `nama_perusahaan` varchar(150) NOT NULL,
  `bidang` varchar(150) DEFAULT NULL,
  `alamat` varchar(255) DEFAULT NULL,
  `logo` varchar(255) DEFAULT NULL,
  `keterangan` text,
  `urutan` int DEFAULT '0',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `bkk_mitra`
--

INSERT INTO `bkk_mitra` (`id`, `nama_perusahaan`, `bidang`, `alamat`, `logo`, `keterangan`, `urutan`, `created_at`) VALUES
(1, 'PT Cyber Media Indonesia', 'Pengembangan Perangkat Lunak', NULL, NULL, NULL, 1, '2026-09-24 11:12:23'),
(2, 'CV Netmedia Solusi', 'Jaringan & Infrastruktur IT', NULL, NULL, NULL, 2, '2026-09-24 11:12:23'),
(3, 'PT Kreatif Digital Nusantara', 'Multimedia & Desain Grafis', NULL, NULL, NULL, 3, '2026-09-24 11:12:23');

-- --------------------------------------------------------

--
-- Table structure for table `bkk_pelamar`
--

CREATE TABLE `bkk_pelamar` (
  `id` int NOT NULL,
  `kode_lamaran` varchar(30) NOT NULL,
  `lowongan_id` int NOT NULL,
  `nama_lengkap` varchar(100) NOT NULL,
  `tahun_lulus` year NOT NULL,
  `jurusan` varchar(100) NOT NULL,
  `no_hp` varchar(20) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `alamat` text,
  `cv_file` varchar(255) DEFAULT NULL,
  `status` enum('baru','diproses','lolos','tidak_lolos') NOT NULL DEFAULT 'baru',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `bkk_pelamar`
--

INSERT INTO `bkk_pelamar` (`id`, `kode_lamaran`, `lowongan_id`, `nama_lengkap`, `tahun_lulus`, `jurusan`, `no_hp`, `email`, `alamat`, `cv_file`, `status`, `created_at`) VALUES
(1, 'BKK-2026-C1060', 1, 'faza', '2024', 'RPL', '0901281928121', 'faza4096@gmail.com', 'jalan sinbar', 'bkk_cv_1790259262_6ab5303ec0cf6.pdf', 'baru', '2026-09-24 14:14:22');

-- --------------------------------------------------------

--
-- Table structure for table `fasilitas`
--

CREATE TABLE `fasilitas` (
  `id` int NOT NULL,
  `nama` varchar(150) NOT NULL,
  `deskripsi` text,
  `gambar` varchar(255) DEFAULT NULL,
  `urutan` int DEFAULT '0',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `fasilitas`
--

INSERT INTO `fasilitas` (`id`, `nama`, `deskripsi`, `gambar`, `urutan`, `created_at`) VALUES
(1, 'Laboratorium Komputer', 'Dilengkapi perangkat komputer terbaru untuk praktik pemrograman dan jaringan.', 'fasilitas_1790252408.jpg', 1, '2026-09-22 11:50:22'),
(4, 'Studio Broadcast', 'Ruang khusus yang dilengkapi dengan teknologi audio dan video untuk memproduksi serta menyiarkan konten media', 'fasilitas_1790252742.jpg', 4, '2026-09-22 11:50:22');

-- --------------------------------------------------------

--
-- Table structure for table `galeri`
--

CREATE TABLE `galeri` (
  `id` int NOT NULL,
  `judul` varchar(150) COLLATE utf8mb4_general_ci NOT NULL,
  `gambar` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `kategori` varchar(50) COLLATE utf8mb4_general_ci DEFAULT 'Kegiatan',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `galeri`
--

INSERT INTO `galeri` (`id`, `judul`, `gambar`, `kategori`, `created_at`) VALUES
(2, 'Pelaksanaan Kegiatan Upacara Bendera pada Senin 21 September 2026', 'galeri_1790261031_6ab537275f327.jpeg', 'Kegiatan', '2026-09-24 14:43:51');

-- --------------------------------------------------------

--
-- Table structure for table `guru_staff`
--

CREATE TABLE `guru_staff` (
  `id` int NOT NULL,
  `nama` varchar(100) NOT NULL,
  `nip` varchar(50) DEFAULT NULL,
  `jabatan` varchar(100) NOT NULL,
  `bidang` varchar(100) DEFAULT NULL,
  `foto` varchar(255) DEFAULT NULL,
  `urutan` int DEFAULT '0',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `guru_staff`
--

INSERT INTO `guru_staff` (`id`, `nama`, `nip`, `jabatan`, `bidang`, `foto`, `urutan`, `created_at`) VALUES
(2, 'Aditya Nugraha, ST.', NULL, 'Kepala Program Keahlian Jurusan TKJ', 'Administrasi Infrastruktur Jaringan (AIJ)', 'guru_1790254173.png', 0, '2026-09-24 12:49:33'),
(3, 'Ridwan Hala, S.Kom.,Gr.', NULL, '⁠Kepala Program Keahlian RPL', 'Robotik', 'guru_1790254393.png', 1, '2026-09-24 12:53:13'),
(4, 'Muhammad Ibnu Arif, S.IKom.', NULL, '⁠Kepala Program Keahlian DKV', 'Produksi Desain Komunikasi Visual', 'guru_1790254487.png', 2, '2026-09-24 12:54:13'),
(5, 'Erli Suherli, A.Md.Kom.', NULL, 'Kepala Program Keahlian PSPT', 'Dasar-Dasar Program Keahlian Broadcasting dan Perfilman', 'guru_1790254581.png', 3, '2026-09-24 12:56:21'),
(6, 'Ahmad Hotib, S.Pd.I', NULL, 'Pembina OSIS', 'Pendidikan Agama Islam', 'guru_1790254804.png', 4, '2026-09-24 13:00:04'),
(7, 'Abdul Azis, M.Pd.', NULL, 'Pembina OSIS', 'Pendidikan Agama Islam', 'guru_1790254921.png', 5, '2026-09-24 13:02:01'),
(8, 'Vera Yuni Astuti SP. M.IKom. Gr.', NULL, 'Staf Kurikulum', 'Program Keahlian Kewirausahaan', 'guru_1790255058.png', 6, '2026-09-24 13:04:18'),
(9, 'M. Fauzan Arifin, S.IKom.,Gr.', NULL, 'Staf Kurikulum', 'Editing Audio dan Video', 'guru_1790255192.png', 7, '2026-09-24 13:06:09'),
(10, 'Dra. Popon Puspitasari', NULL, 'Guru Bimbingan & Konseling', 'Pendidikan Agama Islam', 'guru_1790255280.png', 8, '2026-09-24 13:08:00'),
(11, 'Richo Santana, S.Kom', NULL, 'Guru Bimbingan & Konseling', 'Pemrograman Web dan Mobile', 'guru_1790255767.png', 9, '2026-09-24 13:16:07'),
(12, 'Dian Hardianti, S.Kom.,Gr.', NULL, '⁠Guru Bimbingan & Konseling', 'Koding dan Kecerdasan Artifisial', 'guru_1790256386.png', 10, '2026-09-24 13:26:26'),
(13, 'Yeni Yuliawati, S.Pd.,Gr.', NULL, 'Staf Teller (Bendahara)', 'Bahasa Indonesia', 'guru_1790256486.png', 11, '2026-09-24 13:28:06'),
(14, 'Rita Widyastuti', NULL, '⁠Staf Teller (Bendahara)', NULL, 'guru_1790256671.png', 12, '2026-09-24 13:31:11'),
(15, 'Eva Farida Rahayu, S.Pd.,Gr', NULL, '⁠Staf Teller (Bendahara)', 'Bahasa Indonesia', 'guru_1790256815.png', 13, '2026-09-24 13:33:35'),
(16, 'Jayadi, S.Pd.,Gr.', NULL, '⁠Staf Tata Usaha', 'Pendidikan Jasmani, Olah Raga & Kesehatan', 'guru_1790256919.png', 14, '2026-09-24 13:35:19'),
(17, 'Yadi Setiadi, S.Sos.,Gr.', NULL, 'Staf Tata Usaha', 'Bahasa Sunda', 'guru_1790257031.png', 15, '2026-09-24 13:37:11'),
(18, 'Siti Nuraeni', NULL, 'Staf Tata Usaha', NULL, 'guru_1790257304.png', 16, '2026-09-24 13:41:44'),
(19, 'Reza Prafitriansyah, S.Kom.,Gr.', NULL, 'Staf Perpustakaan', 'Dasar-Dasar Program Keahlian Desain Komunikasi Visual', 'guru_1790257483.png', 17, '2026-09-24 13:44:43'),
(20, 'Dra. Diana Octaria', NULL, 'Projek Ilmu Pengetahuan Alam dan Sosial', NULL, 'guru_1790257640.png', 18, '2026-09-24 13:47:20'),
(21, 'Nabila Ahmadia Pratama, S.Pd.', NULL, 'Bahasa Indonesia', NULL, 'guru_1790257712.png', 19, '2026-09-24 13:48:32'),
(22, 'Erwin Hasiholan Gultom, S.Kom.', NULL, 'Pemrograman Berorientasi Objek', NULL, 'guru_1790257790.png', 19, '2026-09-24 13:49:50'),
(23, 'Widyatama Nuranisa, SM', NULL, 'Kreativitas, Inovasi, dan Kewirausahaan', NULL, 'guru_1790257920.png', 20, '2026-09-24 13:52:00'),
(24, 'Dara Purwanita, S.Pd.,Gr.', NULL, 'Bahasa Inggris', NULL, 'guru_1790258031.png', 21, '2026-09-24 13:53:51');

-- --------------------------------------------------------

--
-- Table structure for table `jurusan`
--

CREATE TABLE `jurusan` (
  `id` int NOT NULL,
  `nama_jurusan` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `singkatan` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `deskripsi` text COLLATE utf8mb4_general_ci,
  `visi` text COLLATE utf8mb4_general_ci,
  `misi` text COLLATE utf8mb4_general_ci,
  `tujuan` text COLLATE utf8mb4_general_ci,
  `gambar` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `jurusan`
--

INSERT INTO `jurusan` (`id`, `nama_jurusan`, `singkatan`, `deskripsi`, `gambar`, `created_at`) VALUES
(1, 'Rekayasa Perangkat Lunak', 'RPL', 'RPL adalah sebuah jurusan yang mempelajari dan mendalami semua cara-cara pengembangan perangkat lunak termasuk pembuatan, pemeliharaan, manajemen organisasi pengembangan perangkat lunak dan manajemen kualitas.', 'jurusan_1790252459.png', '2026-09-13 06:19:47'),
(2, 'Teknik Komputer dan Jaringan', 'TKJ', 'TKJ merupakan sebuah kejuruan yang mempelajari tentang cara merakit komputer, mengenal dan mempelajari komponen hardware apa saja yang ada di dalam komputer, merakit komputer serta fokus mempelajari jaringan dasar.', 'jurusan_1790252466.png', '2026-09-13 06:19:47'),
(3, 'Desain Komunikasi Visual', 'DKV', 'DKV adalah kejuruan berbasis ilmu desain yang mempelajari cara menyampaikan pesan, informasi, atau gagasan melalui elemen visual yang kreatif, efektif, dan komunikatif', 'jurusan_1790252473.png', '2026-09-13 06:19:47'),
(4, 'Produksi Siaran Program Televisi', 'PSPT', 'PSPT merupakan jurusan keahlian yang mempelajari keterampilan teknis dan kreatif dalam dunia penyiaran, pertelevisian, serta media digital.', 'jurusan_1790252481.png', '2026-09-13 06:19:47');

-- --------------------------------------------------------

--
-- Table structure for table `kegiatan_akademik`
--

CREATE TABLE `kegiatan_akademik` (
  `id` int NOT NULL,
  `judul` varchar(200) NOT NULL,
  `deskripsi` text,
  `tanggal` date DEFAULT NULL,
  `gambar` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `kegiatan_akademik`
--

INSERT INTO `kegiatan_akademik` (`id`, `judul`, `deskripsi`, `tanggal`, `gambar`, `created_at`) VALUES
(2, 'Uji Kompetensi Keahlian (UKK)', 'Ujian praktik yang menguji kompetensi siswa sesuai standar industri sebelum kelulusan.', NULL, 'kegiatan_1790262761.png', '2026-09-22 11:50:23'),
(3, 'Praktik Kerja Lapangan (PKL)', 'Mulai 9 November seluruh siswa kelas XII mengikuti program PKL. PKL adalah Program magang siswa tingkat akhir di perusahaan mitra industri selama satu semester.', '2026-11-09', 'kegiatan_1790262747.png', '2026-09-24 11:12:22');

-- --------------------------------------------------------

--
-- Table structure for table `kurikulum`
--

CREATE TABLE `kurikulum` (
  `id` int NOT NULL,
  `judul` varchar(200) NOT NULL,
  `deskripsi` text,
  `file_dokumen` varchar(255) DEFAULT NULL,
  `gambar` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `kurikulum`
--

INSERT INTO `kurikulum` (`id`, `judul`, `deskripsi`, `file_dokumen`, `gambar`, `created_at`) VALUES
(2, 'Kurikulum Merdeka - Program Keahlian TIK', 'SMK INFOKOM BOGOR menerapkan Kurikulum Merdeka yang memadukan pembelajaran berbasis proyek dengan kompetensi industri, agar lulusan siap kerja maupun melanjutkan pendidikan.', NULL, 'kurikulum_1790263376.png', '2026-09-24 11:12:22');

-- --------------------------------------------------------

--
-- Table structure for table `pendaftaran_ppdb`
--

CREATE TABLE `pendaftaran_ppdb` (
  `id` int NOT NULL,
  `nomor_pendaftaran` varchar(30) NOT NULL,
  `nama_lengkap` varchar(100) NOT NULL,
  `nisn` varchar(20) DEFAULT NULL,
  `jenis_kelamin` enum('Laki-laki','Perempuan') NOT NULL,
  `tempat_lahir` varchar(100) DEFAULT NULL,
  `tanggal_lahir` date DEFAULT NULL,
  `asal_sekolah` varchar(150) NOT NULL,
  `jurusan_pilihan` int DEFAULT NULL,
  `nama_orangtua` varchar(100) DEFAULT NULL,
  `no_hp` varchar(20) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `alamat` text,
  `berkas_dokumen` varchar(255) DEFAULT NULL,
  `status` enum('menunggu','diverifikasi','ditolak','diterima') NOT NULL DEFAULT 'menunggu',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pengumuman`
--

CREATE TABLE `pengumuman` (
  `id` int NOT NULL,
  `judul` varchar(200) COLLATE utf8mb4_general_ci NOT NULL,
  `isi` text COLLATE utf8mb4_general_ci,
  `tanggal` date NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pengumuman`
--

INSERT INTO `pengumuman` (`id`, `judul`, `isi`, `tanggal`, `created_at`) VALUES
(1, 'Shalat Dhuha bersama kelas XI dan X', 'besok persiapkan alat shalat seperti sajadah dan mukena bagi perempuan, pastikan sudah berwudhu dari rumah', '2026-09-25', '2026-09-24 14:59:56'),
(2, 'Tryout TKA kelas XII', 'pelaksanaan tryout tka dari univ gundhadarma dan mediatama yang dilaksanakan hari rabu 23 september 2026', '2026-09-23', '2026-09-24 15:01:37');

-- --------------------------------------------------------

--
-- Table structure for table `prestasi`
--

CREATE TABLE `prestasi` (
  `id` int NOT NULL,
  `judul` varchar(200) NOT NULL,
  `deskripsi` text,
  `tingkat` enum('Sekolah','Kota/Kabupaten','Provinsi','Nasional','Internasional') NOT NULL DEFAULT 'Kota/Kabupaten',
  `tahun` year NOT NULL,
  `gambar` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `prestasi`
--

INSERT INTO `prestasi` (`id`, `judul`, `deskripsi`, `tingkat`, `tahun`, `gambar`, `created_at`) VALUES
(1, 'Juara Lomba Web Desain', 'Alhamdulillah Wasyukurillah ya Rabb..\r\nPrestasi kembali diukir oleh M. Shaleh (XII RPL) dalam Lomba Web Desain yang diadakan di Universitas Negeri Jakarta (UNJ) sebagai juara 1 dan Glenaldin Halim sebagai Juara Harapan ke-2, dan juga Fathia Rahmania dan Rakha Prasetya yg masuk 6 besar', 'Kota/Kabupaten', '2018', 'prestasi_1790264791.png', '2026-09-22 11:50:22'),
(2, 'Juara ke-1  IT SOFTWARE & Juara ke-3 Web Desain', 'Selamat Kami Ucapkan kepada Sholeh yang menjadi Juara ke-1 IT SOFTWARE dan Raka yang menjadi Juara ke-3 WEB DESIGN.\r\nDalam kegiatan perlombaan LKS Tingkat Kota Bogor dan akan mewakili Kota Bogor ditingkat Provinsi', 'Kota/Kabupaten', '2018', 'prestasi_1790264391.png', '2026-09-24 11:12:21');

-- --------------------------------------------------------

--
-- Table structure for table `profil`
--

CREATE TABLE `profil` (
  `id` int NOT NULL,
  `jenis` enum('sejarah','visi_misi','sambutan','struktur','fasilitas') COLLATE utf8mb4_general_ci NOT NULL,
  `judul` varchar(150) COLLATE utf8mb4_general_ci NOT NULL,
  `isi` text COLLATE utf8mb4_general_ci NOT NULL,
  `gambar` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `profil`
--

INSERT INTO `profil` (`id`, `jenis`, `judul`, `isi`, `gambar`, `updated_at`) VALUES
(1, 'sejarah', 'Sejarah Sekolah', 'Sekolah ini resmi didirikan pada tanggal 10 Mei 2005 oleh Yayasan Telekomunikasi Nasional (Yatelnas). Kehadiran SMK Infokom dipicu oleh tingginya kebutuhan tenaga kerja lokal yang melek teknologi informasi pada awal abad ke-21. \r\n\r\nTak lama setelah didirikan, sekolah ini resmi mengantongi Izin Operasional dari Dinas Pendidikan Kota Bogor melalui surat keputusan nomor 421/.5/86 DISDIK Tahun 2005 tertanggal 18 Juli 2005\r\n\r\nSecara legalitas formal, Yayasan Yatelnas disahkan melalui Akta Pendirian Nomor 137 oleh Notaris Rinasari Dwi Juli, S.H., pada 10 Juni 2010. Yayasan ini diprakarsai oleh sejumlah tokoh akademisi dan praktisi seperti Ir. Gun Gun Gunawan, M.Si., Ir. Aan Chandra, Wieke Erwina, M.Si., Moelyati Effendi, MS., Dra. Moerfiah, MS., dan Dedi Kusdinar SH, MM. Sebelum berfokus penuh pada pendidikan menengah, yayasan ini sempat mengelola unit Akademi Telekomunikasi Nasional periode 2003–2014 sebelum akhirnya dialihkan.', 'profil_sejarah_1790250636.jpg', '2026-09-24 12:02:09'),
(2, 'visi_misi', 'Visi & Misi', 'Visi\r\nTerwujudnya SMK Infokom Bogor sebagai SMK yang peduli dan berbudaya lingkungan hidup dengan tamatan yang kompeten dan mampu bersaing di tingkat Nasional, maupun Internasional.\r\n\r\nMisi \r\n1. Membangun mental dan sikap peserta didik yang beriman dan bertaqwa kepada Tuhan yang Maha Esa.\r\n2. Mengembangkan kurikulum dan menyelenggarakan pembelajaran berstandar Nasional.\r\n3. Melalui kemitraan, keterbukaan dan pelayanan prima, SMK Infokom Kota Bogor :\r\n * Meningkatkan kualitas dan kuantitas tamatan yang siap kerja.\r\n * Menyiapkan wirausahawan yang handal dan mampu bersaing.\r\n * Menyiapkan tenaga terampil yang memiliki etos kerja dan kepribadian.', 'profil_visi_misi_1790252047.jpg', '2026-09-24 12:14:07'),
(3, 'sambutan', 'Sambutan Kepala Sekolah', 'Selamat datang di Website resmi SMK INFOKOM Kota Bogor. Di era digital saat ini, website sekolah merupakan pintu gerbang informasi terdepan sebagai sarana untuk menyebarluaskan berbagai publikasi terkait kemajuan pendidikan dan pencapaian sekolah terkini. Sebagai media pembelajaran, terlebih di masa pandemi masih berlangsung, Website sekolah juga diarahkan dapat mendatangkan kemanfaatan sebagai sarana pembelajaran dengan memuat blog, e-learning, karya dan media pembelajaran yang dibuat oleh pendidik serta para siswa.\r\n\r\nDi sisi lain kami sungguh berharap Website ini juga dapat menjadi sarana silaturahmi dan komunikasi antara sekolah dengan para alumni di berbagai tempat, sebagai sebuah keluarga besar yang solid serta dapat berkontribusi dalam memajukan pendidikan di SMK INFOKOM tercinta. Kritik, saran dan masukan dari berbagai stake holder sekolah tentu sangat kami harapkan.Terima kasih atas kerjasamanya, mari terus berkarya menuju SMK INFOKOM yang lebih berkualitas dan berjaya dalam mencerdaskan kehidupan anak bangsa Indonesia. SMK, Bisa...! SMK INFOKOM, Luar Biasa…!', 'profil_sambutan_1790245931.png', '2026-09-24 10:32:11'),
(4, 'fasilitas', 'Fasilitas Sekolah', 'Laboratorium Komputer, Laboratorium Jaringan, Perpustakaan, Ruang Multimedia, Lapangan Olahraga, dan fasilitas penunjang lainnya.', NULL, '2026-09-13 06:19:47');

-- --------------------------------------------------------

--
-- Table structure for table `statistik`
--

CREATE TABLE `statistik` (
  `id` int NOT NULL,
  `label` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `jumlah` int NOT NULL DEFAULT '0',
  `icon` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `statistik`
--

INSERT INTO `statistik` (`id`, `label`, `jumlah`, `icon`) VALUES
(1, 'Siswa Aktif', 1250, 'bi-mortarboard-fill'),
(2, 'Guru & Staff', 75, 'bi-person-badge-fill'),
(3, 'Jurusan', 4, 'bi-journal-bookmark-fill'),
(4, 'Prestasi', 120, 'bi-trophy-fill');

-- --------------------------------------------------------

--
-- Table structure for table `struktur_organisasi`
--

CREATE TABLE `struktur_organisasi` (
  `id` int NOT NULL,
  `jabatan` varchar(100) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `foto` varchar(255) DEFAULT NULL,
  `urutan` int DEFAULT '0',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `struktur_organisasi`
--

INSERT INTO `struktur_organisasi` (`id`, `jabatan`, `nama`, `foto`, `urutan`, `created_at`) VALUES
(1, 'Kepala Sekolah', 'Ir. Hj. Liliek Rahmaningsih, M.M.Pd.', 'struktur_1790249126.png', 1, '2026-09-22 11:50:21'),
(2, 'Wakil Kepala Sekolah Kurikulum', 'Mila Yaelasari, M.Pd.', 'struktur_1790249713.png', 2, '2026-09-22 11:50:21'),
(3, 'Wakil Kepala Sekolah Kesiswaan', 'Dea Rijalul Fikri, S.Kom.,Gr.', 'struktur_1790249842.png', 3, '2026-09-22 11:50:21'),
(4, '⁠Wakil Kepala Sekolah Bidang Hubungan Industri dan Masyarakat', 'Radiani Srirestuti Dewi, SS.,Gr', 'struktur_1790250046.png', 4, '2026-09-22 11:50:21'),
(9, 'Wakil Kepala Sekolah Bidang Pengembangan Sumber Daya Manusia', 'Heny Handayani, S.Pd.', 'struktur_1790250091.png', 5, '2026-09-24 11:41:31'),
(10, 'Wakil Kepala Sekolah Bidang Sarana dan Prasarana', 'Agus Tiyono, M.Pd.', 'struktur_1790250148.png', 6, '2026-09-24 11:42:28'),
(12, 'Ketua Manajemen Mutu', 'Aditya Nugraha, ST.', 'struktur_1790258362.png', 6, '2026-09-24 13:59:22'),
(13, '⁠Kepala Tata Usaha', 'Cahyadi Rozi, ST.', 'struktur_1790258397.png', 7, '2026-09-24 13:59:57'),
(14, '⁠Kepala Perpustakaan', 'Drs. Puji Marhaen Waluyo', 'struktur_1790258450.png', 8, '2026-09-24 14:00:50'),
(15, 'Kepala Bendahara', 'Putri Riandini, S.Hut., M.Pd.,Gr.', 'struktur_1790258505.png', 9, '2026-09-24 14:01:45');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int NOT NULL,
  `username` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `nama_lengkap` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `level` enum('superadmin','admin') COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'admin',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `nama_lengkap`, `level`, `created_at`) VALUES
(1, 'admin', '$2b$12$W95THwpRffjtYC6aDjHUNO7BzBMektX.DP27kkJgmELgwmzaG3Rpq', 'Administrator', 'superadmin', '2026-09-13 06:19:46');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `agenda`
--
ALTER TABLE `agenda`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `berita`
--
ALTER TABLE `berita`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`);

--
-- Indexes for table `bkk_lowongan`
--
ALTER TABLE `bkk_lowongan`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `bkk_mitra`
--
ALTER TABLE `bkk_mitra`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `bkk_pelamar`
--
ALTER TABLE `bkk_pelamar`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `kode_lamaran` (`kode_lamaran`),
  ADD KEY `lowongan_id` (`lowongan_id`);

--
-- Indexes for table `fasilitas`
--
ALTER TABLE `fasilitas`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `galeri`
--
ALTER TABLE `galeri`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `guru_staff`
--
ALTER TABLE `guru_staff`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `jurusan`
--
ALTER TABLE `jurusan`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `kegiatan_akademik`
--
ALTER TABLE `kegiatan_akademik`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `kurikulum`
--
ALTER TABLE `kurikulum`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pendaftaran_ppdb`
--
ALTER TABLE `pendaftaran_ppdb`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nomor_pendaftaran` (`nomor_pendaftaran`),
  ADD KEY `jurusan_pilihan` (`jurusan_pilihan`);

--
-- Indexes for table `pengumuman`
--
ALTER TABLE `pengumuman`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `prestasi`
--
ALTER TABLE `prestasi`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `profil`
--
ALTER TABLE `profil`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `jenis` (`jenis`);

--
-- Indexes for table `statistik`
--
ALTER TABLE `statistik`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `struktur_organisasi`
--
ALTER TABLE `struktur_organisasi`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `agenda`
--
ALTER TABLE `agenda`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `berita`
--
ALTER TABLE `berita`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `bkk_lowongan`
--
ALTER TABLE `bkk_lowongan`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `bkk_mitra`
--
ALTER TABLE `bkk_mitra`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `bkk_pelamar`
--
ALTER TABLE `bkk_pelamar`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `fasilitas`
--
ALTER TABLE `fasilitas`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `galeri`
--
ALTER TABLE `galeri`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `guru_staff`
--
ALTER TABLE `guru_staff`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `jurusan`
--
ALTER TABLE `jurusan`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `kegiatan_akademik`
--
ALTER TABLE `kegiatan_akademik`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `kurikulum`
--
ALTER TABLE `kurikulum`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `pendaftaran_ppdb`
--
ALTER TABLE `pendaftaran_ppdb`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pengumuman`
--
ALTER TABLE `pengumuman`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `prestasi`
--
ALTER TABLE `prestasi`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `profil`
--
ALTER TABLE `profil`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `statistik`
--
ALTER TABLE `statistik`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `struktur_organisasi`
--
ALTER TABLE `struktur_organisasi`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `bkk_pelamar`
--
ALTER TABLE `bkk_pelamar`
  ADD CONSTRAINT `bkk_pelamar_ibfk_1` FOREIGN KEY (`lowongan_id`) REFERENCES `bkk_lowongan` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `pendaftaran_ppdb`
--
ALTER TABLE `pendaftaran_ppdb`
  ADD CONSTRAINT `pendaftaran_ppdb_ibfk_1` FOREIGN KEY (`jurusan_pilihan`) REFERENCES `jurusan` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
