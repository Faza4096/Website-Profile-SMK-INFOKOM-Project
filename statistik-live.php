<?php
/**
 * Endpoint real-time lama — SUDAH TIDAK DIPAKAI.
 * Statistik beranda kini statis dan diatur lewat panel admin
 * (Dashboard -> "Statistik Beranda"). File ini hanya menyimpan
 * respons JSON agar permintaan lama di browser tidak error.
 */
header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-store');
http_response_code(410);

echo json_encode([
    'ok'    => false,
    'pesan' => 'Endpoint real-time sudah dihentikan. Statistik beranda diatur lewat panel admin.',
]);
