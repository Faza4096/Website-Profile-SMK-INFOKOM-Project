<?php
/**
 * Migrasi ringan tabel `bkk_lowongan`:
 * menambah nilai 'PKL' pada enum `tipe_pekerjaan` bila belum ada, idempoten.
 * Halaman filter lowongan (bkk-lowongan.php?tipe=PKL) dan menu "Program PKL"
 * membutuhkan tipe ini; tanpa kolom yang mendukung, admin tidak bisa menyimpan
 * lowongan berjenis PKL. Data lama tetap aman (semua nilai lama disertakan).
 * Dipakai oleh form admin lowongan (tambah/edit).
 */

function lowongan_migrasi_pkl(PDO $pdo): void
{
    try {
        $tipe = (string)$pdo->query("SELECT COLUMN_TYPE FROM information_schema.COLUMNS
                                     WHERE TABLE_SCHEMA = DATABASE()
                                       AND TABLE_NAME = 'bkk_lowongan'
                                       AND COLUMN_NAME = 'tipe_pekerjaan'")->fetchColumn();
    } catch (Throwable $e) {
        return; // gagal cek — jangan mencoba ALTER agar tidak membanjiri error
    }

    if ($tipe !== '' && stripos($tipe, 'PKL') === false) {
        try {
            $pdo->exec("ALTER TABLE bkk_lowongan
                        MODIFY COLUMN tipe_pekerjaan
                        ENUM('Full-time','Part-time','Kontrak','Magang','PKL')
                        NOT NULL DEFAULT 'Full-time'");
        } catch (Throwable $e) {
            // izin DB terbatas atau sudah berubah — diabaikan
        }
    }
}
