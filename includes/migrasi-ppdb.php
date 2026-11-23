<?php
/**
 * Migrasi ringan tabel `pendaftaran_ppdb`:
 *  - Menambah kolom `alasan` (alasan penolakan dari admin) bila belum ada — idempoten.
 * Dipakai oleh halaman ppdb.php (depan) dan admin/ppdb/detail.php,
 * sehingga kolom selalu tersedia tanpa menjalankan SQL manual.
 */

// Cek dulu via information_schema agar tidak menjalankan ALTER gagal tiap request
try {
    $kolomAda = (int)$pdo->query("SELECT COUNT(*) FROM information_schema.COLUMNS
                                  WHERE TABLE_SCHEMA = DATABASE()
                                    AND TABLE_NAME = 'pendaftaran_ppdb'
                                    AND COLUMN_NAME = 'alasan'")->fetchColumn();
} catch (Throwable $e) {
    $kolomAda = 1; // gagal cek — jangan coba ALTER agar tidak membanjiri error
}

if ($kolomAda < 1) {
    try {
        $pdo->exec("ALTER TABLE pendaftaran_ppdb ADD COLUMN alasan TEXT NULL");
    } catch (Throwable $e) {
        // kolom sudah ada (Duplicate column) atau izin DB terbatas — diabaikan
    }
}
