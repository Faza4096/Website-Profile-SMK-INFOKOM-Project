<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../../includes/koneksi.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama    = trim($_POST['nama'] ?? '');
    $deskripsi = trim($_POST['deskripsi'] ?? '');
    $pembina = trim($_POST['pembina'] ?? '');
    $jadwal  = trim($_POST['jadwal'] ?? '');
    $urutan  = (int)($_POST['urutan'] ?? 0);

    if ($nama === '') {
        $error = 'Nama kegiatan wajib diisi.';
    } else {
        $namaFile = null;
        if (!empty($_FILES['gambar']['name'])) {
            $ext = strtolower(pathinfo($_FILES['gambar']['name'], PATHINFO_EXTENSION));
            $allowed = ['jpg', 'jpeg', 'png', 'webp'];
            if (!in_array($ext, $allowed)) {
                $error = 'Format gambar tidak didukung (gunakan jpg/jpeg/png/webp).';
            } elseif ($_FILES['gambar']['size'] > 2 * 1024 * 1024) {
                $error = 'Ukuran gambar maksimal 2MB.';
            } else {
                $namaFile = 'ekstra_' . time() . '_' . uniqid() . '.' . $ext;
                move_uploaded_file($_FILES['gambar']['tmp_name'], __DIR__ . '/../../uploads/ekstrakurikuler/' . $namaFile);
            }
        }

        if ($error === '') {
            $stmt = $pdo->prepare("INSERT INTO ekstrakurikuler (nama, deskripsi, pembina, jadwal, gambar, urutan) VALUES (:nama, :deskripsi, :pembina, :jadwal, :gambar, :urutan)");
            $stmt->execute([
                ':nama'      => $nama,
                ':deskripsi' => $deskripsi ?: null,
                ':pembina'   => $pembina ?: null,
                ':jadwal'    => $jadwal ?: null,
                ':gambar'    => $namaFile,
                ':urutan'    => $urutan,
            ]);

            header('Location: list.php?sukses=Kegiatan berhasil ditambahkan');
            exit;
        }
    }
}
$pageTitleAdmin = 'Tambah Ekstrakurikuler';
require_once __DIR__ . '/../includes/top.php';
?>

    <h4 class="mb-4"><i class="bi bi-plus-lg"></i> Tambah Ekstrakurikuler</h4>

    <?php if ($error): ?>
      <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <div class="card card-admin p-4" style="max-width:600px;">
      <form method="POST" enctype="multipart/form-data">
        <div class="mb-3">
          <label class="form-label fw-semibold">Nama Kegiatan</label>
          <input type="text" name="nama" class="form-control" required placeholder="Contoh: Pramuka"
                 value="<?= htmlspecialchars($_POST['nama'] ?? '') ?>">
        </div>
        <div class="mb-3">
          <label class="form-label fw-semibold">Deskripsi</label>
          <textarea name="deskripsi" rows="3" class="form-control" placeholder="Penjelasan singkat kegiatan"><?= htmlspecialchars($_POST['deskripsi'] ?? '') ?></textarea>
        </div>
        <div class="row">
          <div class="col-md-6 mb-3">
            <label class="form-label fw-semibold">Pembina</label>
            <input type="text" name="pembina" class="form-control" placeholder="Nama pembina"
                   value="<?= htmlspecialchars($_POST['pembina'] ?? '') ?>">
          </div>
          <div class="col-md-6 mb-3">
            <label class="form-label fw-semibold">Jadwal</label>
            <input type="text" name="jadwal" class="form-control" placeholder="Contoh: Jumat, 15.00 WIB"
                   value="<?= htmlspecialchars($_POST['jadwal'] ?? '') ?>">
          </div>
        </div>
        <div class="row">
          <div class="col-md-6 mb-3">
            <label class="form-label fw-semibold">Urutan Tampil</label>
            <input type="number" name="urutan" min="0" class="form-control" value="<?= (int)($_POST['urutan'] ?? 0) ?>">
          </div>
          <div class="col-md-6 mb-3">
            <label class="form-label fw-semibold">Gambar (opsional, maks 2MB)</label>
            <input type="file" name="gambar" class="form-control" accept=".jpg,.jpeg,.png,.webp">
          </div>
        </div>
        <button type="submit" class="btn btn-primary text-white">Simpan</button>
        <a href="list.php" class="btn btn-outline-secondary">Batal</a>
      </form>
    </div>
<?php require_once __DIR__ . '/../includes/bottom.php'; ?>
