<?php
require_once __DIR__ . '/includes/koneksi.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: kontak.php');
    exit;
}

$nama  = trim($_POST['nama'] ?? '');
$email = trim($_POST['email'] ?? '');
$pesan = trim($_POST['pesan'] ?? '');

if ($nama === '' || $email === '' || $pesan === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    header('Location: kontak.php?gagal=1');
    exit;
}

// Tabel pesan_kontak dibuat otomatis bila belum ada (impor database lama)
try {
    $pdo->exec("CREATE TABLE IF NOT EXISTS pesan_kontak (
        id INT AUTO_INCREMENT PRIMARY KEY,
        nama VARCHAR(100) NOT NULL,
        email VARCHAR(100) NOT NULL,
        pesan TEXT NOT NULL,
        status ENUM('belum_dibaca','sudah_dibaca') NOT NULL DEFAULT 'belum_dibaca',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
} catch (Throwable $e) {
    header('Location: kontak.php?gagal=1');
    exit;
}

try {
    $stmt = $pdo->prepare("INSERT INTO pesan_kontak (nama, email, pesan) VALUES (:nama, :email, :pesan)");
    $stmt->execute([
        ':nama'  => $nama,
        ':email' => $email,
        ':pesan' => $pesan,
    ]);
} catch (Throwable $e) {
    header('Location: kontak.php?gagal=1');
    exit;
}

header('Location: kontak.php?sukses=1');
exit;
