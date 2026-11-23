<?php
/**
 * Statistik beranda (angka statis — tanpa real-time).
 * Diambil dari tabel `statistik`, yang nilainya diatur lewat panel admin
 * (Dashboard -> "Statistik Beranda"). Tidak ada penghitungan otomatis dari
 * tabel lain, sehingga angka selalu sesuai setelan admin.
 */

function statistik_beranda(PDO $pdo): array
{
    $hasil = [];
    try {
        foreach ($pdo->query('SELECT label, jumlah, icon FROM statistik ORDER BY id ASC') as $baris) {
            $hasil[] = [
                'label'  => $baris['label'],
                'icon'   => $baris['icon'] ?: 'bi-bar-chart-fill',
                'jumlah' => (int)$baris['jumlah'],
            ];
        }
    } catch (Throwable $e) {
        // tabel statistik belum ada — biarkan kosong (beranda tetap jalan)
        $hasil = [];
    }

    return $hasil;
}
