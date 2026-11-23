<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../../includes/koneksi.php';

$id = (int)($_GET['id'] ?? 0);

$stmt = $pdo->prepare("SELECT gambar FROM galeri WHERE id = ?");
$stmt->execute([$id]);
$data = $stmt->fetch();

if ($data) {
    $path = __DIR__ . '/../../uploads/galeri/' . $data['gambar'];
    if (file_exists($path)) {
        unlink($path);
    }
    $stmt = $pdo->prepare("DELETE FROM galeri WHERE id = ?");
    $stmt->execute([$id]);
}

header('Location: list.php?sukses=Foto berhasil dihapus');
exit;
