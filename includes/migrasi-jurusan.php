<?php
/**
 * Migrasi ringan tabel `jurusan`:
 *  1. Menambah kolom `visi`, `misi`, `tujuan` bila belum ada (idempoten).
 *  2. Mengisi contoh awal — HANYA jika semua baris jurusan masih kosong,
 *     sehingga hasil edit admin tidak pernah tertimpa.
 * Dipakai oleh halaman akademik.php dan halaman admin jurusan (tambah/edit).
 */

// Cek dulu via information_schema agar tidak menjalankan ALTER gagal tiap request
try {
    $kolomAda = (int)$pdo->query("SELECT COUNT(*) FROM information_schema.COLUMNS
                                  WHERE TABLE_SCHEMA = DATABASE()
                                    AND TABLE_NAME = 'jurusan'
                                    AND COLUMN_NAME IN ('visi','misi','tujuan')")->fetchColumn();
} catch (Throwable $e) {
    $kolomAda = 3; // gagal cek — jangan coba ALTER agar tidak membanjiri error
}

if ($kolomAda < 3) {
    foreach (['visi', 'misi', 'tujuan'] as $kolom) {
        try {
            $pdo->exec("ALTER TABLE jurusan ADD COLUMN `$kolom` TEXT NULL");
        } catch (Throwable $e) {
            // kolom sudah ada (Duplicate column) atau izin DB terbatas — diabaikan
        }
    }
}

// Contoh awal visi/misi/tujuan per jurusan (dilewati bila sudah ada isinya)
try {
    $terisi = (int)$pdo->query("SELECT COUNT(*) FROM jurusan
                                WHERE COALESCE(visi,'')<>'' OR COALESCE(misi,'')<>'' OR COALESCE(tujuan,'')<>''")->fetchColumn();
    if ($terisi === 0) {
        $contoh = [
            'RPL' => [
                'visi'   => 'Menjadi program keahlian unggul di bidang pengembangan perangkat lunak yang kompeten, kreatif, dan berkarakter.',
                'misi'   => "1. Menguasai dasar pemrograman dan pengembangan aplikasi.\n2. Melatih siswa membangun perangkat lunak sesuai kebutuhan nyata.\n3. Menanamkan sikap profesional, kerja sama, dan etika digital.\n4. Menyiapkan siswa melanjutkan studi atau terjun ke dunia kerja.",
                'tujuan' => 'Lulusan mampu mengembangkan, menguji, dan memelihara perangkat lunak, serta siap bekerja sebagai programmer, software tester, atau wirausaha di bidang teknologi.',
            ],
            'TKJ' => [
                'visi'   => 'Menjadi program keahlian unggul di bidang komputer dan jaringan yang berkompeten dan siap kerja.',
                'misi'   => "1. Menguasai perakitan dan perawatan komputer.\n2. Mempelajari instalasi dan konfigurasi jaringan dasar.\n3. Membiasakan siswa bekerja secara sistematis, aman, dan disiplin.\n4. Mempersiapkan sertifikasi kompetensi dan dunia kerja.",
                'tujuan' => 'Lulusan mampu merakit dan merawat komputer, mengelola jaringan skala kecil hingga menengah, serta bekerja sebagai teknisi jaringan atau wirausaha di bidang TIK.',
            ],
            'DKV' => [
                'visi'   => 'Menjadi program keahlian unggul dalam desain komunikasi visual yang kreatif, inovatif, dan berkarakter.',
                'misi'   => "1. Menguasai dasar desain grafis dan komposisi visual.\n2. Membuat karya desain untuk media cetak dan digital.\n3. Melatih kreativitas, komunikasi, dan presentasi karya.\n4. Menumbuhkan jiwa kreatif dan kewirausahaan di bidang desain.",
                'tujuan' => 'Lulusan mampu menghasilkan karya desain komunikasi visual yang profesional, serta bekerja sebagai desainer grafis, content creator, atau wirausaha kreatif.',
            ],
            'PSPT' => [
                'visi'   => 'Menjadi program keahlian unggul di bidang produksi siaran televisi dan media digital.',
                'misi'   => "1. Menguasai teknik kamera, pencahayaan, dan audio.\n2. Mempelajari produksi konten siaran dan media digital.\n3. Melatih kreativitas dan kerja sama dalam tim produksi.\n4. Membekali siswa dengan etika dan profesionalisme penyiaran.",
                'tujuan' => 'Lulusan mampu memproduksi konten siaran dan video profesional, serta bekerja di stasiun televisi, rumah produksi, atau media digital.',
            ],
        ];

        $stmtCari = $pdo->prepare("SELECT id FROM jurusan WHERE UPPER(TRIM(COALESCE(singkatan,''))) = :singkatan LIMIT 1");
        $stmtIsi  = $pdo->prepare("UPDATE jurusan SET visi = :visi, misi = :misi, tujuan = :tujuan
                                   WHERE id = :id AND COALESCE(visi,'')='' AND COALESCE(misi,'')='' AND COALESCE(tujuan,'')=''");
        foreach ($contoh as $singkatan => $isi) {
            $stmtCari->execute([':singkatan' => $singkatan]);
            $idJurusan = (int)$stmtCari->fetchColumn();
            if ($idJurusan > 0) {
                $stmtIsi->execute([
                    ':visi'   => $isi['visi'],
                    ':misi'   => $isi['misi'],
                    ':tujuan' => $isi['tujuan'],
                    ':id'     => $idJurusan,
                ]);
            }
        }
    }
} catch (Throwable $e) {
    // pengisian contoh gagal (permission DB) — admin tetap bisa mengisi manual lewat panel
}
