-- =========================================================
-- Ekstrakurikuler — SMK INFOKOM BOGOR
-- Jalankan file ini pada database db_smkinfokom
-- (phpMyAdmin: pilih database -> Import -> pilih file ini)
-- =========================================================

CREATE TABLE IF NOT EXISTS ekstrakurikuler (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nama VARCHAR(100) NOT NULL,
  deskripsi TEXT,
  pembina VARCHAR(100),
  jadwal VARCHAR(100),
  gambar VARCHAR(255),
  urutan INT DEFAULT 0,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Data contoh (hapus/edit lewat panel admin setelah impor)
INSERT INTO ekstrakurikuler (nama, deskripsi, pembina, jadwal, urutan) VALUES
('Pramuka', 'Membentuk kemandirian, kepemimpinan, dan kepedulian sosial melalui kegiatan kepramukaan.', 'Pak Ahmad Saputra', 'Jumat, 15.00 WIB', 1),
('Palang Merah Remaja (PMR)', 'Pelatihan pertolongan pertama dan kegiatan kemanusiaan bersama PMI Kota Bogor.', 'Bu Dewi Rahmawati', 'Rabu, 15.00 WIB', 2),
('Basket', 'Latihan basket rutin dan pengembangan tim untuk ajang antar-sekolah.', 'Pak Ilham Prasetyo', 'Selasa & Kamis, 15.30 WIB', 3),
('Futsal', 'Latihan dan pembinaan tim futsal putra-putri untuk kompetisi tingkat kota.', 'Pak Yoga Fauzan', 'Senin, 15.30 WIB', 4),
('Seni Tari', 'Pelestarian seni budaya daerah sekaligus wadah kreasi seni siswa.', 'Bu Lestari Oktaviani', 'Kamis, 15.00 WIB', 5),
('Karya Ilmiah Remaja (KIR)', 'Wadah riset dan inovasi siswa di bidang teknologi informasi.', 'Bu Rina Permata', 'Selasa, 15.00 WIB', 6);
