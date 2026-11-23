<?php
/**
 * File Koneksi Database
 * Menggunakan PDO agar mendukung Prepared Statements (aman dari SQL Injection)
 */

$DB_HOST = 'localhost';
$DB_NAME = 'db_smkinfokom';
$DB_USER = 'root';      // ganti sesuai konfigurasi server Anda
$DB_PASS = '';          // ganti sesuai konfigurasi server Anda

try {
    $pdo = new PDO(
        "mysql:host={$DB_HOST};dbname={$DB_NAME};charset=utf8mb4",
        $DB_USER,
        $DB_PASS,
        [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ]
    );
} catch (PDOException $e) {
    die('Koneksi database gagal: ' . $e->getMessage());
}
