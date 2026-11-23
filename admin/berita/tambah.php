<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../../includes/koneksi.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $judul   = trim($_POST['judul'] ?? '');
    $konten  = trim($_POST['konten'] ?? '');
    $penulis = trim($_POST['penulis'] ?? 'Admin');
    $status  = ($_POST['status'] ?? 'draft') === 'publish' ? 'publish' : 'draft';

    if ($judul === '' || $konten === '') {
        $error = 'Judul dan konten wajib diisi.';
    } else {
        // Slug unik otomatis dari judul
        $slugBase = strtolower(trim(preg_replace('/[^A-Za-z0-9]+/', '-', $judul), '-'));
        $slug = $slugBase . '-' . substr(uniqid(), -5);

        // Upload gambar (opsional)
        $namaFile = null;
        if (!empty($_FILES['gambar']['name'])) {
            $ext = strtolower(pathinfo($_FILES['gambar']['name'], PATHINFO_EXTENSION));
            $allowed = ['jpg', 'jpeg', 'png', 'webp'];
            if (!in_array($ext, $allowed)) {
                $error = 'Format gambar tidak didukung (gunakan jpg/jpeg/png/webp).';
            } else {
                $namaFile = 'berita_' . time() . '.' . $ext;
                $target = __DIR__ . '/../../uploads/berita/' . $namaFile;
                move_uploaded_file($_FILES['gambar']['tmp_name'], $target);
            }
        }

        if ($error === '') {
            // Prepared statement -> aman dari SQL Injection
            $stmt = $pdo->prepare(
                "INSERT INTO berita (judul, slug, gambar, konten, penulis, status)
                 VALUES (:judul, :slug, :gambar, :konten, :penulis, :status)"
            );
            $stmt->execute([
                ':judul'   => $judul,
                ':slug'    => $slug,
                ':gambar'  => $namaFile,
                ':konten'  => $konten,
                ':penulis' => $penulis,
                ':status'  => $status,
            ]);

            header('Location: list.php?sukses=Berita berhasil ditambahkan');
            exit;
        }
    }
}
$pageTitleAdmin = 'Tambah Berita';
require_once __DIR__ . '/../includes/top.php';
?>

    <h4 class="mb-4"><i class="bi bi-plus-lg"></i> Tambah Berita</h4>

    <?php if ($error): ?>
      <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <div class="card card-admin p-4">
      <form method="POST" enctype="multipart/form-data">
        <div class="mb-3">
          <label class="form-label fw-semibold">Judul Berita</label>
          <input type="text" name="judul" class="form-control" required value="<?= htmlspecialchars($_POST['judul'] ?? '') ?>">
        </div>
        <div class="mb-3">
          <label class="form-label fw-semibold">Penulis</label>
          <input type="text" name="penulis" class="form-control" value="<?= htmlspecialchars($_POST['penulis'] ?? 'Admin') ?>">
        </div>
        <div class="mb-3">
          <label class="form-label fw-semibold">Gambar Sampul</label>
          <input type="file" name="gambar" class="form-control" accept=".jpg,.jpeg,.png,.webp">
        </div>
        <div class="mb-3">
          <label class="form-label fw-semibold">Konten</label>
          <textarea name="konten" rows="6" class="form-control" required><?= htmlspecialchars($_POST['konten'] ?? '') ?></textarea>
        </div>
        <div class="mb-3">
          <label class="form-label fw-semibold">Status</label>
          <select name="status" class="form-select">
            <option value="publish">Publish</option>
            <option value="draft">Draft</option>
          </select>
        </div>
        <button type="submit" class="btn btn-primary text-white">Simpan</button>
        <a href="list.php" class="btn btn-outline-secondary">Batal</a>
      </form>
    </div>
<?php require_once __DIR__ . '/../includes/bottom.php'; ?>
