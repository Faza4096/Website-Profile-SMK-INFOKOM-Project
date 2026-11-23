<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../../includes/koneksi.php';

$id = (int)($_GET['id'] ?? 0);

$stmt = $pdo->prepare("SELECT * FROM berita WHERE id = ?");
$stmt->execute([$id]);
$data = $stmt->fetch();

if (!$data) {
    header('Location: list.php');
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $judul   = trim($_POST['judul'] ?? '');
    $konten  = trim($_POST['konten'] ?? '');
    $penulis = trim($_POST['penulis'] ?? 'Admin');
    $status  = ($_POST['status'] ?? 'draft') === 'publish' ? 'publish' : 'draft';

    if ($judul === '' || $konten === '') {
        $error = 'Judul dan konten wajib diisi.';
    } else {
        $namaFile = $data['gambar']; // default: gambar lama tetap dipakai

        if (!empty($_FILES['gambar']['name'])) {
            $ext = strtolower(pathinfo($_FILES['gambar']['name'], PATHINFO_EXTENSION));
            $allowed = ['jpg', 'jpeg', 'png', 'webp'];
            if (!in_array($ext, $allowed)) {
                $error = 'Format gambar tidak didukung.';
            } else {
                // Hapus gambar lama jika ada
                $lama = __DIR__ . '/../../uploads/berita/' . $data['gambar'];
                if ($data['gambar'] && file_exists($lama)) {
                    unlink($lama);
                }
                $namaFile = 'berita_' . time() . '.' . $ext;
                move_uploaded_file($_FILES['gambar']['tmp_name'], __DIR__ . '/../../uploads/berita/' . $namaFile);
            }
        }

        if ($error === '') {
            $stmt = $pdo->prepare(
                "UPDATE berita SET judul=:judul, gambar=:gambar, konten=:konten, penulis=:penulis, status=:status WHERE id=:id"
            );
            $stmt->execute([
                ':judul'   => $judul,
                ':gambar'  => $namaFile,
                ':konten'  => $konten,
                ':penulis' => $penulis,
                ':status'  => $status,
                ':id'      => $id,
            ]);

            header('Location: list.php?sukses=Berita berhasil diperbarui');
            exit;
        }
    }
}
$pageTitleAdmin = 'Edit Berita';
require_once __DIR__ . '/../includes/top.php';
?>

    <h4 class="mb-4"><i class="bi bi-pencil-square"></i> Edit Berita</h4>

    <?php if ($error): ?>
      <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <div class="card card-admin p-4">
      <form method="POST" enctype="multipart/form-data">
        <div class="mb-3">
          <label class="form-label fw-semibold">Judul Berita</label>
          <input type="text" name="judul" class="form-control" required value="<?= htmlspecialchars($data['judul']) ?>">
        </div>
        <div class="mb-3">
          <label class="form-label fw-semibold">Penulis</label>
          <input type="text" name="penulis" class="form-control" value="<?= htmlspecialchars($data['penulis']) ?>">
        </div>
        <div class="mb-3">
          <label class="form-label fw-semibold">Gambar Sampul Saat Ini</label><br>
          <img src="../../uploads/berita/<?= htmlspecialchars($data['gambar'] ?: 'default.jpg') ?>"
               width="150" class="rounded mb-2" onerror="this.src='https://placehold.co/150x100'"><br>
          <input type="file" name="gambar" class="form-control" accept=".jpg,.jpeg,.png,.webp">
          <small class="text-muted">Kosongkan jika tidak ingin mengganti gambar.</small>
        </div>
        <div class="mb-3">
          <label class="form-label fw-semibold">Konten</label>
          <textarea name="konten" rows="6" class="form-control" required><?= htmlspecialchars($data['konten']) ?></textarea>
        </div>
        <div class="mb-3">
          <label class="form-label fw-semibold">Status</label>
          <select name="status" class="form-select">
            <option value="publish" <?= $data['status']==='publish'?'selected':'' ?>>Publish</option>
            <option value="draft" <?= $data['status']==='draft'?'selected':'' ?>>Draft</option>
          </select>
        </div>
        <button type="submit" class="btn btn-primary text-white">Update</button>
        <a href="list.php" class="btn btn-outline-secondary">Batal</a>
      </form>
    </div>
<?php require_once __DIR__ . '/../includes/bottom.php'; ?>
