<?php
require_once __DIR__ . '/includes/koneksi.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ppdb.php');
    exit;
}

$nama_lengkap    = trim($_POST['nama_lengkap'] ?? '');
$jenis_kelamin   = $_POST['jenis_kelamin'] ?? 'Laki-laki';
if (!in_array($jenis_kelamin, ['Laki-laki', 'Perempuan'], true)) {
    $jenis_kelamin = 'Laki-laki';
}
$nisn            = trim($_POST['nisn'] ?? '');
$asal_sekolah    = trim($_POST['asal_sekolah'] ?? '');
$tempat_lahir    = trim($_POST['tempat_lahir'] ?? '');
$tanggal_lahir   = $_POST['tanggal_lahir'] ?? null;
$jurusan_pilihan = (int)($_POST['jurusan_pilihan'] ?? 0);
$nama_orangtua   = trim($_POST['nama_orangtua'] ?? '');
$no_hp           = trim($_POST['no_hp'] ?? '');
$email           = trim($_POST['email'] ?? '');
$alamat          = trim($_POST['alamat'] ?? '');

if ($nama_lengkap === '' || $asal_sekolah === '' || $no_hp === '' || $jurusan_pilihan === 0) {
    header('Location: ppdb.php?gagal=1');
    exit;
}

// Email opsional — jika diisi harus valid
if ($email !== '' && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    header('Location: ppdb.php?gagal=1');
    exit;
}

// Upload berkas (opsional)
$namaFile = null;
if (!empty($_FILES['berkas_dokumen']['name'])) {
    $ext = strtolower(pathinfo($_FILES['berkas_dokumen']['name'], PATHINFO_EXTENSION));
    $allowed = ['pdf', 'jpg', 'jpeg', 'png'];
    if (in_array($ext, $allowed) && $_FILES['berkas_dokumen']['size'] <= 2 * 1024 * 1024) {
        $namaFile = 'ppdb_' . time() . '_' . uniqid() . '.' . $ext;
        move_uploaded_file($_FILES['berkas_dokumen']['tmp_name'], __DIR__ . '/uploads/ppdb/' . $namaFile);
    }
}

// Buat nomor pendaftaran unik: PPDB-TAHUN-XXXXX
$nomorPendaftaran = 'PPDB-' . date('Y') . '-' . strtoupper(substr(uniqid(), -5));

$stmt = $pdo->prepare(
    "INSERT INTO pendaftaran_ppdb
     (nomor_pendaftaran, nama_lengkap, nisn, jenis_kelamin, tempat_lahir, tanggal_lahir,
      asal_sekolah, jurusan_pilihan, nama_orangtua, no_hp, email, alamat, berkas_dokumen)
     VALUES
     (:nomor, :nama, :nisn, :jk, :tempat, :tgl, :sekolah, :jurusan, :ortu, :hp, :email, :alamat, :berkas)"
);
$stmt->execute([
    ':nomor'   => $nomorPendaftaran,
    ':nama'    => $nama_lengkap,
    ':nisn'    => $nisn ?: null,
    ':jk'      => $jenis_kelamin,
    ':tempat'  => $tempat_lahir ?: null,
    ':tgl'     => $tanggal_lahir ?: null,
    ':sekolah' => $asal_sekolah,
    ':jurusan' => $jurusan_pilihan,
    ':ortu'    => $nama_orangtua ?: null,
    ':hp'      => $no_hp,
    ':email'   => $email ?: null,
    ':alamat'  => $alamat ?: null,
    ':berkas'  => $namaFile,
]);

header('Location: ppdb.php?sukses=' . urlencode($nomorPendaftaran));
exit;
