<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../../includes/koneksi.php';
$id = (int)($_GET['id'] ?? 0);
$stmt = $pdo->prepare("SELECT foto FROM struktur_organisasi WHERE id=?"); $stmt->execute([$id]); $data = $stmt->fetch();
if ($data) {
    if ($data['foto']) { $p = __DIR__.'/../../uploads/struktur/'.$data['foto']; if (file_exists($p)) unlink($p); }
    $stmt = $pdo->prepare("DELETE FROM struktur_organisasi WHERE id=?"); $stmt->execute([$id]);
}
header('Location: list.php?sukses=Data berhasil dihapus'); exit;
