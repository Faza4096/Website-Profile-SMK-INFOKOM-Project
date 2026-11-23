<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../../includes/koneksi.php';

$id = (int)($_GET['id'] ?? 0);

$stmt = $pdo->prepare("SELECT gambar FROM jurusan WHERE id = ?");
$stmt->execute([$id]);
$data = $stmt->fetch();

if ($data) {
    if (!empty($data['gambar'])) {
        $path = __DIR__ . '/../../uploads/jurusan/' . $data['gambar'];
        if (file_exists($path)) {
            unlink($path);
        }
    }
    $stmt = $pdo->prepare("DELETE FROM jurusan WHERE id = ?");
    $stmt->execute([$id]);
}

header('Location: list.php?sukses=Jurusan berhasil dihapus');
exit;
