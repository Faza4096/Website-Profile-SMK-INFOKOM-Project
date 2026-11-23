<?php
/**
 * Migrasi ringan tabel `bkk_mitra`:
 * menambah kolom `jenis` (industri | universitas) bila belum ada, idempoten.
 * Semua baris lama otomatis dianggap "industri" (default), sehingga halaman
 * Perusahaan Mitra yang sudah ada tidak berubah isinya.
 * Dipakai oleh halaman bkk-mitra.php, mitra-universitas.php, dan halaman admin.
 */

function mitra_migrasi_jenis(PDO $pdo): void
{
    try {
        $ada = (int)$pdo->query("SELECT COUNT(*) FROM information_schema.COLUMNS
                                 WHERE TABLE_SCHEMA = DATABASE()
                                   AND TABLE_NAME = 'bkk_mitra'
                                   AND COLUMN_NAME = 'jenis'")->fetchColumn();
    } catch (Throwable $e) {
        return; // gagal cek — jangan mencoba ALTER agar tidak membanjiri error
    }

    if ($ada === 0) {
        try {
            $pdo->exec("ALTER TABLE bkk_mitra
                        ADD COLUMN jenis ENUM('industri','universitas')
                        NOT NULL DEFAULT 'industri' AFTER nama_perusahaan");
        } catch (Throwable $e) {
            // sudah ada (race) atau izin DB terbatas — diabaikan
        }
    }
}
