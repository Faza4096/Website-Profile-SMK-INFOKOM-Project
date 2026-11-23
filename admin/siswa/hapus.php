<?php
// Modul "Kelola Data Siswa" dihapus dari panel admin — akses langsung dialihkan ke dashboard.
header('Location: ../index.php');
exit;

require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../../includes/koneksi.php';
$id = (int)($_GET['id'] ?? 0);
$stmt = $pdo->prepare("DELETE FROM siswa WHERE id = ?");
$stmt->execute([$id]);
header('Location: list.php?sukses=Data siswa berhasil dihapus'); exit;
