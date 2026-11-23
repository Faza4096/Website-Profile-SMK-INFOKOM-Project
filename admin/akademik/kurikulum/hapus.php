<?php
require_once __DIR__ . '/../../includes/auth.php';
require_once __DIR__ . '/../../../includes/koneksi.php';
$id = (int)($_GET['id'] ?? 0);
$stmt = $pdo->prepare("DELETE FROM kurikulum WHERE id=?"); $stmt->execute([$id]);
header('Location: list.php?sukses=Data berhasil dihapus'); exit;
