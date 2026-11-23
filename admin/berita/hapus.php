<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../../includes/koneksi.php';

$id = (int)($_GET['id'] ?? 0);

$stmt = $pdo->prepare("SELECT gambar FROM berita WHERE id = ?");
$stmt->execute([$id]);
$data = $stmt->fetch();

if ($data) {
    // Hapus file gambar dari server jika ada
    if (!empty($data['gambar'])) {
        $path = __DIR__ . '/../../uploads/berita/' . $data['gambar'];
        if (file_exists($path)) {
            unlink($path);
        }
    }

    // Prepared statement -> aman dari SQL Injection
    $stmt = $pdo->prepare("DELETE FROM berita WHERE id = ?");
    $stmt->execute([$id]);
}

header('Location: list.php?sukses=Berita berhasil dihapus');
exit;
