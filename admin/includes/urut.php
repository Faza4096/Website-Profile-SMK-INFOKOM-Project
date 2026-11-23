<?php
/**
 * Helper urutan baris untuk daftar CRUD admin (tombol panah naik/turun).
 * Berlaku untuk tabel yang kolom `urutan`-nya dipakai halaman publik:
 * struktur organisasi, guru & staff, fasilitas, dan mitra BKK.
 *
 * Pemanggil: urut_pindah($pdo, 'struktur_organisasi', $id, 'naik');
 */
function urut_pindah(PDO $pdo, string $tabel, int $id, string $arah): void
{
    // Whitelist — nama tabel di-interpolasi, jadi tidak boleh dari input bebas
    $dikenal = ['struktur_organisasi', 'guru_staff', 'fasilitas', 'bkk_mitra'];
    if (!in_array($tabel, $dikenal, true) || $id <= 0 || !in_array($arah, ['naik', 'turun'], true)) {
        return;
    }

    $daftarId = $pdo->query("SELECT id FROM `$tabel` ORDER BY urutan ASC, id ASC")->fetchAll(PDO::FETCH_COLUMN);
    $pos = array_search($id, $daftarId, true);
    if ($pos === false) {
        return;
    }

    $tuju = $arah === 'naik' ? $pos - 1 : $pos + 1;
    if ($tuju < 0 || $tuju >= count($daftarId)) {
        return; // sudah di batas atas/bawah — tidak ada efek samping
    }

    [$daftarId[$pos], $daftarId[$tuju]] = [$daftarId[$tuju], $daftarId[$pos]];

    // Renumbering seragam 1..N agar urutan selalu rapi setelah pindah
    $stmt = $pdo->prepare("UPDATE `$tabel` SET urutan = ? WHERE id = ?");
    foreach ($daftarId as $i => $idBaris) {
        $stmt->execute([$i + 1, $idBaris]);
    }
}
