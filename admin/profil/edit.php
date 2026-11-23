<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../../includes/koneksi.php';

$id = (int)($_GET['id'] ?? 0);
$stmt = $pdo->prepare("SELECT * FROM profil WHERE id = ?");
$stmt->execute([$id]);
$data = $stmt->fetch();

if (!$data) {
    header('Location: list.php');
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $judul = trim($_POST['judul'] ?? '');
    $isi   = trim($_POST['isi'] ?? '');

    if ($judul === '' || $isi === '') {
        $error = 'Judul dan isi wajib diisi.';
    } else {
        $namaFile = $data['gambar'];

        if (!empty($_FILES['gambar']['name'])) {
            $ext = strtolower(pathinfo($_FILES['gambar']['name'], PATHINFO_EXTENSION));
            $allowed = ['jpg', 'jpeg', 'png', 'webp'];
            if (!in_array($ext, $allowed)) {
                $error = 'Format gambar tidak didukung.';
            } else {
                $lama = __DIR__ . '/../../uploads/profil/' . $data['gambar'];
                if ($data['gambar'] && file_exists($lama)) {
                    unlink($lama);
                }
                $namaFile = 'profil_' . $data['jenis'] . '_' . time() . '.' . $ext;
                move_uploaded_file($_FILES['gambar']['tmp_name'], __DIR__ . '/../../uploads/profil/' . $namaFile);
            }
        }

        if ($error === '') {
            $stmt = $pdo->prepare("UPDATE profil SET judul=:judul, isi=:isi, gambar=:gambar WHERE id=:id");
            $stmt->execute([
                ':judul'  => $judul,
                ':isi'    => $isi,
                ':gambar' => $namaFile,
                ':id'     => $id,
            ]);
            header('Location: list.php?sukses=Profil berhasil diperbarui');
            exit;
        }
    }
}
$pageTitleAdmin = 'Edit Profil Sekolah';
require_once __DIR__ . '/../includes/top.php';
?>

    <h4 class="mb-4"><i class="bi bi-pencil-square"></i> Edit Profil - <?= htmlspecialchars($data['judul']) ?></h4>

    <?php if ($error): ?>
      <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <div class="card card-admin p-4">
      <form method="POST" enctype="multipart/form-data">
        <div class="mb-3">
          <label class="form-label fw-semibold">Judul</label>
          <input type="text" name="judul" class="form-control" required value="<?= htmlspecialchars($data['judul']) ?>">
        </div>

        <?php if (!empty($data['gambar'])): ?>
        <div class="mb-3">
          <label class="form-label fw-semibold">Gambar Saat Ini</label><br>
          <img src="../../uploads/profil/<?= htmlspecialchars($data['gambar']) ?>" width="150" class="rounded mb-2">
        </div>
        <?php endif; ?>

        <div class="mb-3">
          <label class="form-label fw-semibold">Ganti Gambar (opsional)</label>
          <input type="file" name="gambar" class="form-control" accept=".jpg,.jpeg,.png,.webp">
        </div>

        <div class="mb-3">
          <label class="form-label fw-semibold">Isi Konten</label>
          <textarea name="isi" rows="8" class="form-control" required><?= htmlspecialchars($data['isi']) ?></textarea>
        </div>

        <button type="submit" class="btn btn-primary text-white">Update</button>
        <a href="list.php" class="btn btn-outline-secondary">Batal</a>
      </form>
    </div>
<?php require_once __DIR__ . '/../includes/bottom.php'; ?>
