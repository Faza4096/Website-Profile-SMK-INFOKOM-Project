<?php
/**
 * Proteksi halaman admin.
 * File ini WAJIB di-include paling atas di setiap halaman admin (kecuali login.php).
 */
session_start();

/**
 * Deteksi otomatis base URL folder /admin/, agar redirect dan link menu
 * tetap benar meskipun halaman dibuka dari sub-folder (berita/, jurusan/, dst).
 * Contoh hasil: /smkinfokom/admin/
 */
$scriptName = $_SERVER['SCRIPT_NAME'];
$pos = strpos($scriptName, '/admin/');
$GLOBALS['ADMIN_BASE'] = ($pos !== false) ? substr($scriptName, 0, $pos + 7) : '/admin/';

if (!isset($_SESSION['admin_id'])) {
    header('Location: ' . $GLOBALS['ADMIN_BASE'] . 'login.php');
    exit;
}
