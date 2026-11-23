<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../../includes/koneksi.php';
require_once __DIR__ . '/../../includes/migrasi-jurusan.php';

$id = (int)($_GET['id'] ?? 0);
$stmt = $pdo->prepare("SELECT * FROM jurusan WHERE id = ?");
$stmt->execute([$id]);
$data = $stmt->fetch();

if (!$data) {
    header('Location: list.php');
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama      = trim($_POST['nama_jurusan'] ?? '');
    $singkatan = trim($_POST['singkatan'] ?? '');
    $deskripsi = trim($_POST['deskripsi'] ?? '');
    $visi      = trim($_POST['visi'] ?? '');
    $misi      = trim($_POST['misi'] ?? '');
    $tujuan    = trim($_POST['tujuan'] ?? '');

    if ($nama === '') {
        $error = 'Nama jurusan wajib diisi.';
    } else {
        $namaFile = $data['gambar'];

        if (!empty($_FILES['gambar']['name'])) {
            $ext = strtolower(pathinfo($_FILES['gambar']['name'], PATHINFO_EXTENSION));
            $allowed = ['jpg', 'jpeg', 'png', 'webp'];
            if (!in_array($ext, $allowed)) {
                $error = 'Format gambar tidak didukung.';
            } else {
                $lama = __DIR__ . '/../../uploads/jurusan/' . $data['gambar'];
                if ($data['gambar'] && file_exists($lama)) {
                    unlink($lama);
                }
                $namaFile = 'jurusan_' . time() . '.' . $ext;
                move_uploaded_file($_FILES['gambar']['tmp_name'], __DIR__ . '/../../uploads/jurusan/' . $namaFile);
            }
        }

        if ($error === '') {
            $stmt = $pdo->prepare(
                "UPDATE jurusan SET nama_jurusan=:nama, singkatan=:singkatan, deskripsi=:deskripsi,
                                    visi=:visi, misi=:misi, tujuan=:tujuan, gambar=:gambar WHERE id=:id"
            );
            $stmt->execute([
                ':nama'      => $nama,
                ':singkatan' => $singkatan,
                ':deskripsi' => $deskripsi,
                ':visi'      => $visi,
                ':misi'      => $misi,
                ':tujuan'    => $tujuan,
                ':gambar'    => $namaFile,
                ':id'        => $id,
            ]);
            header('Location: list.php?sukses=Jurusan berhasil diperbarui');
            exit;
        }
    }
}
$pageTitleAdmin = 'Edit Jurusan';
require_once __DIR__ . '/../includes/top.php';
?>

    <h4 class="mb-4"><i class="bi bi-pencil-square"></i> Edit Jurusan</h4>

    <?php if ($error): ?>
      <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <div class="card card-admin p-4">
      <form method="POST" enctype="multipart/form-data">
        <div class="mb-3">
          <label class="form-label fw-semibold">Nama Jurusan</label>
          <input type="text" name="nama_jurusan" class="form-control" required value="<?= htmlspecialchars($data['nama_jurusan']) ?>">
        </div>
        <div class="mb-3">
          <label class="form-label fw-semibold">Singkatan</label>
          <input type="text" name="singkatan" class="form-control" value="<?= htmlspecialchars($data['singkatan']) ?>">
        </div>

        <?php if (!empty($data['gambar'])): ?>
        <div class="mb-3">
          <label class="form-label fw-semibold">Gambar Saat Ini</label><br>
          <img src="../../uploads/jurusan/<?= htmlspecialchars($data['gambar']) ?>" width="150" class="rounded mb-2">
        </div>
        <?php endif; ?>

        <div class="mb-3">
          <label class="form-label fw-semibold">Ganti Gambar (opsional)</label>
          <input type="file" name="gambar" class="form-control" accept=".jpg,.jpeg,.png,.webp">
        </div>
        <div class="mb-3">
          <label class="form-label fw-semibold">Deskripsi</label>
          <textarea name="deskripsi" rows="5" class="form-control"><?= htmlspecialchars($data['deskripsi']) ?></textarea>
        </div>
        <div class="mb-3">
          <label class="form-label fw-semibold">Visi</label>
          <textarea name="visi" rows="3" class="form-control" placeholder="Visi jurusan (ditampilkan di halaman Akademik)"><?= htmlspecialchars($data['visi'] ?? '') ?></textarea>
        </div>
        <div class="mb-3">
          <label class="form-label fw-semibold">Misi</label>
          <textarea name="misi" rows="5" class="form-control" placeholder="Satu poin misi per baris"><?= htmlspecialchars($data['misi'] ?? '') ?></textarea>
        </div>
        <div class="mb-3">
          <label class="form-label fw-semibold">Tujuan</label>
          <textarea name="tujuan" rows="3" class="form-control" placeholder="Tujuan lulusan jurusan"><?= htmlspecialchars($data['tujuan'] ?? '') ?></textarea>
        </div>
        <button type="submit" class="btn btn-primary text-white">Update</button>
        <a href="list.php" class="btn btn-outline-secondary">Batal</a>
      </form>
    </div>
<?php require_once __DIR__ . '/../includes/bottom.php'; ?>
