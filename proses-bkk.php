<?php
require_once __DIR__ . '/includes/koneksi.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: bkk-daftar.php');
    exit;
}

$lowonganId   = (int)($_POST['lowongan_id'] ?? 0);
$namaLengkap  = trim($_POST['nama_lengkap'] ?? '');
$tahunLulus   = (int)($_POST['tahun_lulus'] ?? 0);
$jurusan      = trim($_POST['jurusan'] ?? '');
$noHp         = trim($_POST['no_hp'] ?? '');
$email        = trim($_POST['email'] ?? '');
$alamat       = trim($_POST['alamat'] ?? '');

if ($lowonganId === 0 || $namaLengkap === '' || $tahunLulus === 0 || $jurusan === '' || $noHp === '') {
    header('Location: bkk-daftar.php?gagal=1');
    exit;
}

// Email opsional — jika diisi harus valid
if ($email !== '' && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    header('Location: bkk-daftar.php?gagal=1');
    exit;
}

// Pastikan lowongan tersedia dan masih buka (batas daftar belum lewat)
$stmt = $pdo->prepare("SELECT * FROM bkk_lowongan WHERE id = ? AND status='buka' AND (batas_daftar IS NULL OR batas_daftar >= CURDATE()) LIMIT 1");
$stmt->execute([$lowonganId]);
$lowongan = $stmt->fetch();

if (!$lowongan) {
    header('Location: bkk-daftar.php?gagal=2');
    exit;
}

// Upload CV (opsional)
$namaFile = null;
if (!empty($_FILES['cv_file']['name'])) {
    $ext = strtolower(pathinfo($_FILES['cv_file']['name'], PATHINFO_EXTENSION));
    $allowed = ['pdf', 'jpg', 'jpeg', 'png'];
    if (in_array($ext, $allowed) && $_FILES['cv_file']['size'] <= 2 * 1024 * 1024) {
        $namaFile = 'bkk_cv_' . time() . '_' . uniqid() . '.' . $ext;
        move_uploaded_file($_FILES['cv_file']['tmp_name'], __DIR__ . '/uploads/bkk/' . $namaFile);
    }
}

// Buat kode lamaran unik: BKK-TAHUN-XXXXX
$kodeLamaran = 'BKK-' . date('Y') . '-' . strtoupper(substr(uniqid(), -5));

$stmt = $pdo->prepare(
    "INSERT INTO bkk_pelamar
     (kode_lamaran, lowongan_id, nama_lengkap, tahun_lulus, jurusan, no_hp, email, alamat, cv_file)
     VALUES
     (:kode, :lowongan, :nama, :lulus, :jurusan, :hp, :email, :alamat, :cv)"
);
$stmt->execute([
    ':kode'     => $kodeLamaran,
    ':lowongan' => $lowonganId,
    ':nama'     => $namaLengkap,
    ':lulus'    => $tahunLulus,
    ':jurusan'  => $jurusan,
    ':hp'       => $noHp,
    ':email'    => $email ?: null,
    ':alamat'   => $alamat ?: null,
    ':cv'       => $namaFile,
]);

header('Location: bkk-daftar.php?sukses=' . urlencode($kodeLamaran));
exit;
