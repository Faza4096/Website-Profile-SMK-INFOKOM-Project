<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../../includes/koneksi.php';

$id = (int)($_GET['id'] ?? 0);
$stmt = $pdo->prepare("SELECT * FROM ekstrakurikuler WHERE id = ?");
$stmt->execute([$id]);
$data = $stmt->fetch();

if (!$data) {
    header('Location: list.php');
    exit;
}

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
        $namaFile = $data['gambar'];

        if (!empty($_FILES['gambar']['name'])) {
            $ext = strtolower(pathinfo($_FILES['gambar']['name'], PATHINFO_EXTENSION));
            $allowed = ['jpg', 'jpeg', 'png', 'webp'];
            if (!in_array($ext, $allowed)) {
                $error = 'Format gambar tidak didukung (gunakan jpg/jpeg/png/webp).';
            } elseif ($_FILES['gambar']['size'] > 2 * 1024 * 1024) {
                $error = 'Ukuran gambar maksimal 2MB.';
            } else {
                if (!empty($data['gambar'])) {
                    $lama = __DIR__ . '/../../uploads/ekstrakurikuler/' . $data['gambar'];
                    if (file_exists($lama)) {
                        unlink($lama);
                    }
                }
                $namaFile = 'ekstra_' . time() . '_' . uniqid() . '.' . $ext;
                move_uploaded_file($_FILES['gambar']['tmp_name'], __DIR__ . '/../../uploads/ekstrakurikuler/' . $namaFile);
            }
        }

        if ($error === '') {
            $stmt = $pdo->prepare("UPDATE ekstrakurikuler SET nama=:nama, deskripsi=:deskripsi, pembina=:pembina, jadwal=:jadwal, gambar=:gambar, urutan=:urutan WHERE id=:id");
            $stmt->execute([
                ':nama'      => $nama,
                ':deskripsi' => $deskripsi ?: null,
                ':pembina'   => $pembina ?: null,
                ':jadwal'    => $jadwal ?: null,
                ':gambar'    => $namaFile,
                ':urutan'    => $urutan,
                ':id'        => $id,
            ]);
            header('Location: list.php?sukses=Kegiatan berhasil diperbarui');
            exit;
        }
    }
}
$pageTitleAdmin = 'Edit Ekstrakurikuler';
require_once __DIR__ . '/../includes/top.php';
?>

    <h4 class="mb-4"><i class="bi bi-pencil-square"></i> Edit Ekstrakurikuler</h4>

    <?php if ($error): ?>
      <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <div class="card card-admin p-4" style="max-width:600px;">
      <form method="POST" enctype="multipart/form-data">
        <div class="mb-3">
          <label class="form-label fw-semibold">Nama Kegiatan</label>
          <input type="text" name="nama" class="form-control" required value="<?= htmlspecialchars($_POST['nama'] ?? $data['nama']) ?>">
        </div>
        <div class="mb-3">
          <label class="form-label fw-semibold">Deskripsi</label>
          <textarea name="deskripsi" rows="3" class="form-control"><?= htmlspecialchars($_POST['deskripsi'] ?? ($data['deskripsi'] ?? '')) ?></textarea>
        </div>
        <div class="row">
          <div class="col-md-6 mb-3">
            <label class="form-label fw-semibold">Pembina</label>
            <input type="text" name="pembina" class="form-control" value="<?= htmlspecialchars($_POST['pembina'] ?? ($data['pembina'] ?? '')) ?>">
          </div>
          <div class="col-md-6 mb-3">
            <label class="form-label fw-semibold">Jadwal</label>
            <input type="text" name="jadwal" class="form-control" value="<?= htmlspecialchars($_POST['jadwal'] ?? ($data['jadwal'] ?? '')) ?>">
          </div>
        </div>
        <div class="mb-3">
          <label class="form-label fw-semibold">Urutan Tampil</label>
          <input type="number" name="urutan" min="0" class="form-control" style="max-width:120px;"
                 value="<?= (int)($_POST['urutan'] ?? $data['urutan']) ?>">
        </div>
        <?php if (!empty($data['gambar'])): ?>
        <div class="mb-3">
          <label class="form-label fw-semibold">Gambar Saat Ini</label><br>
          <img src="../../uploads/ekstrakurikuler/<?= htmlspecialchars($data['gambar']) ?>" width="150" class="rounded mb-2" alt="">
        </div>
        <?php endif; ?>
        <div class="mb-3">
          <label class="form-label fw-semibold"><?= !empty($data['gambar']) ? 'Ganti Gambar (opsional, maks 2MB)' : 'Gambar (opsional, maks 2MB)' ?></label>
          <input type="file" name="gambar" class="form-control" accept=".jpg,.jpeg,.png,.webp">
        </div>
        <button type="submit" class="btn btn-primary text-white">Update</button>
        <a href="list.php" class="btn btn-outline-secondary">Batal</a>
      </form>
    </div>
<?php require_once __DIR__ . '/../includes/bottom.php'; ?>
