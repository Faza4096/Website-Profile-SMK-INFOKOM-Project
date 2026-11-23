<?php
require_once __DIR__ . '/../../includes/auth.php';
require_once __DIR__ . '/../../../includes/koneksi.php';

$id = (int)($_GET['id'] ?? 0);
$stmt = $pdo->prepare("SELECT cv_file FROM bkk_pelamar WHERE id = ?");
$stmt->execute([$id]);
$data = $stmt->fetch();

if ($data) {
    if ($data['cv_file']) {
        $p = __DIR__ . '/../../../uploads/bkk/' . $data['cv_file'];
        if (file_exists($p)) unlink($p);
    }
    $stmt = $pdo->prepare("DELETE FROM bkk_pelamar WHERE id = ?");
    $stmt->execute([$id]);
}
header('Location: list.php?sukses=Data pelamar berhasil dihapus');
exit;
