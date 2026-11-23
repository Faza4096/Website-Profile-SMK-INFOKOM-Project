<?php
/**
 * Kalender Pendidikan (Tahun Pelajaran 2026/2027).
 * Tabel dibuat otomatis bila belum ada, lalu diisi contoh jadwal umum —
 * HANYA saat tabel masih kosong, sehingga editan admin tidak tertimpa.
 * Dipakai oleh halaman akademik.php dan halaman admin akademik/kalender/.
 */

function kalender_pendidikan_siapkan(PDO $pdo): void
{
    try {
        $pdo->exec("CREATE TABLE IF NOT EXISTS kalender_pendidikan (
            id INT AUTO_INCREMENT PRIMARY KEY,
            tahun_pelajaran VARCHAR(20) NOT NULL DEFAULT '2026/2027',
            kegiatan VARCHAR(150) NOT NULL,
            kategori ENUM('Kegiatan','Akademik','Ujian','Libur','PPDB') NOT NULL DEFAULT 'Kegiatan',
            tanggal_mulai DATE NOT NULL,
            tanggal_selesai DATE DEFAULT NULL,
            keterangan VARCHAR(255) DEFAULT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
    } catch (Throwable $e) {
        return; // izin DB terbatas — biarkan halaman memakai blok try/catch
    }

    // Contoh awal kalender 2026/2027 (dilewati bila admin sudah mengisi data)
    try {
        $kosong = (int)$pdo->query("SELECT COUNT(*) FROM kalender_pendidikan")->fetchColumn() === 0;
        if ($kosong) {
            $contoh = [
                // [kegiatan, kategori, mulai, selesai, keterangan]
                ['Awal Tahun Pelajaran — Hari Pertama Masuk Sekolah', 'Akademik', '2026-07-13', null,        'Seragam lengkap, masuk pagi sesuai jadwal wali kelas.'],
                ['Masa Pengenalan Lingkungan Sekolah (MPLS) Kelas X', 'Kegiatan', '2026-07-13', '2026-07-15', 'Pengenalan akademik, ekstrakurikuler, dan tata tertib sekolah.'],
                ['Penilaian Tengah Semester Ganjil (PTS)',            'Ujian',    '2026-09-21', '2026-09-26', 'PTS seluruh tingkatan — jadwal rilis melalui wali kelas.'],
                ['Penilaian Akhir Semester Ganjil (PAS)',             'Ujian',    '2026-12-14', '2026-12-19', 'PAS seluruh tingkatan sebelum libur semester.'],
                ['Libur Semester Ganjil',                              'Libur',    '2026-12-21', '2027-01-02', 'Libur semester, sekolah kembali belajar 4 Januari 2027.'],
                ['Awal Semester Genap',                                'Akademik', '2027-01-04', null,        'Kegiatan belajar mengajar semester genap dimulai.'],
                ['Penilaian Tengah Semester Genap (PTS)',             'Ujian',    '2027-03-08', '2027-03-13', 'PTS seluruh tingkatan semester genap.'],
                ['Penerimaan Peserta Didik Baru (PPDB) 2027/2028',    'PPDB',     '2027-02-15', '2027-05-15', 'Pendaftaran online melalui halaman PPDB website sekolah.'],
                ['Uji Kompetensi Keahlian (UKK) Kelas XII',           'Ujian',    '2027-05-03', '2027-05-08', 'Uji praktik kompetensi sesuai keahlian masing-masing.'],
                ['Penilaian Akhir Semester Genap (PAS)',              'Ujian',    '2027-05-31', '2027-06-05', 'PAS seluruh tingkatan akhir tahun pelajaran.'],
                ['Pembagian Rapor & Akhir Tahun Pelajaran',           'Akademik', '2027-06-12', null,        'Rapor kenaikan kelas dibagikan melalui wali kelas.'],
                ['Libur Kenaikan Kelas',                               'Libur',    '2027-06-14', '2027-07-10', 'Tahun pelajaran baru dimulai pertengahan Juli 2027.'],
            ];
            $stmt = $pdo->prepare("INSERT INTO kalender_pendidikan
                                   (tahun_pelajaran, kegiatan, kategori, tanggal_mulai, tanggal_selesai, keterangan)
                                   VALUES (:tp, :kegiatan, :kategori, :mulai, :selesai, :keterangan)");
            foreach ($contoh as $baris) {
                $stmt->execute([
                    ':tp'          => '2026/2027',
                    ':kegiatan'    => $baris[0],
                    ':kategori'    => $baris[1],
                    ':mulai'       => $baris[2],
                    ':selesai'     => $baris[3],
                    ':keterangan'  => $baris[4],
                ]);
            }
        }
    } catch (Throwable $e) {
        // pengisian contoh gagal — admin tetap bisa mengisi manual lewat panel
    }
}
