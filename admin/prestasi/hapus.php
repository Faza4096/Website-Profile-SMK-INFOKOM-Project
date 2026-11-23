<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../../includes/koneksi.php';
$id = (int)($_GET['id'] ?? 0);
$stmt = $pdo->prepare("SELECT gambar FROM prestasi WHERE id=?"); $stmt->execute([$id]); $data = $stmt->fetch();
if ($data) {
    if ($data['gambar']) { $p = __DIR__.'/../../uploads/prestasi/'.$data['gambar']; if (file_exists($p)) unlink($p); }
    $stmt = $pdo->prepare("DELETE FROM prestasi WHERE id=?"); $stmt->execute([$id]);
}
header('Location: list.php?sukses=Data berhasil dihapus'); exit;
