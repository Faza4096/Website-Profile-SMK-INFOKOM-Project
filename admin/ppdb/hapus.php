<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../../includes/koneksi.php';
$id = (int)($_GET['id'] ?? 0);
$stmt = $pdo->prepare("SELECT berkas_dokumen FROM pendaftaran_ppdb WHERE id=?"); $stmt->execute([$id]); $data = $stmt->fetch();
if ($data) {
    if ($data['berkas_dokumen']) { $p = __DIR__.'/../../uploads/ppdb/'.$data['berkas_dokumen']; if (file_exists($p)) unlink($p); }
    $stmt = $pdo->prepare("DELETE FROM pendaftaran_ppdb WHERE id=?"); $stmt->execute([$id]);
}
header('Location: list.php?sukses=Data pendaftar berhasil dihapus'); exit;
