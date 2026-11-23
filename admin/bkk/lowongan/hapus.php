<?php
require_once __DIR__ . '/../../includes/auth.php';
require_once __DIR__ . '/../../../includes/koneksi.php';

$id = (int)($_GET['id'] ?? 0);
if ($id > 0) {
    $stmt = $pdo->prepare("DELETE FROM bkk_lowongan WHERE id = ?");
    $stmt->execute([$id]);
}
header('Location: list.php?sukses=Lowongan berhasil dihapus');
exit;
