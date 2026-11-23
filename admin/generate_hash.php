<?php
/**
 * UTILITAS SEKALI PAKAI: Generate hash password bcrypt yang valid.
 *
 * Cara pakai:
 * 1. Taruh file ini di folder admin/ (sudah ada di sini).
 * 2. Buka di browser: http://localhost/smkinfokom/admin/generate_hash.php?pw=admin123
 * 3. Copy hasil hash yang muncul.
 * 4. Buka phpMyAdmin -> tabel `users` -> edit kolom `password` -> paste hash tsb.
 * 5. HAPUS file ini setelah selesai dipakai (jangan biarkan ada di server production).
 */

$password = $_GET['pw'] ?? 'admin123';
$hash = password_hash($password, PASSWORD_BCRYPT);

echo "<pre>";
echo "Password asli : " . htmlspecialchars($password) . "\n";
echo "Hash bcrypt   : " . htmlspecialchars($hash) . "\n\n";
echo "Verifikasi otomatis: " . (password_verify($password, $hash) ? "VALID ✔" : "GAGAL ✘") . "\n";
echo "</pre>";
echo "<p style='color:red;font-weight:bold;'>PENTING: Hapus file generate_hash.php ini setelah selesai digunakan!</p>";
