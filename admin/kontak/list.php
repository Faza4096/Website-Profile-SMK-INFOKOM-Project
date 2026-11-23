<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../../includes/koneksi.php';

// Tabel dibuat otomatis agar halaman tetap jalan pada database lama
try {
    $pdo->exec("CREATE TABLE IF NOT EXISTS pesan_kontak (
        id INT AUTO_INCREMENT PRIMARY KEY,
        nama VARCHAR(100) NOT NULL,
        email VARCHAR(100) NOT NULL,
        pesan TEXT NOT NULL,
        status ENUM('belum_dibaca','sudah_dibaca') NOT NULL DEFAULT 'belum_dibaca',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
} catch (Throwable $e) {
    // permission DB — ditekan di sini, ditangani saat baca data
}

// Aksi: tandai sudah dibaca / hapus
if (isset($_GET['aksi'], $_GET['id'])) {
    $id = (int)$_GET['id'];
    if ($_GET['aksi'] === 'dibaca') {
        $stmt = $pdo->prepare("UPDATE pesan_kontak SET status='sudah_dibaca' WHERE id=?");
        $stmt->execute([$id]);
        header('Location: list.php?sukses=Pesan ditandai sudah dibaca'); exit;
    }
    if ($_GET['aksi'] === 'hapus') {
        $stmt = $pdo->prepare("DELETE FROM pesan_kontak WHERE id=?");
        $stmt->execute([$id]);
        header('Location: list.php?sukses=Pesan berhasil dihapus'); exit;
    }
}

$data = $pdo->query("SELECT * FROM pesan_kontak ORDER BY created_at DESC")->fetchAll();

$pageTitleAdmin = 'Pesan Kontak';
require_once __DIR__ . '/../includes/top.php';
?>
<div class="d-flex justify-content-between align-items-center mb-4">
  <h4 class="mb-0">Pesan Kontak Masuk</h4>
</div>

<?php if (isset($_GET['sukses'])): ?><div class="alert alert-success"><?= htmlspecialchars($_GET['sukses']) ?></div><?php endif; ?>

<?php if (!$data): ?>
  <div class="card card-admin p-4 text-center text-muted">Belum ada pesan masuk dari halaman Kontak.</div>
<?php else: ?>
  <div class="row g-3">
    <?php foreach ($data as $m): ?>
    <div class="col-lg-6">
      <div class="card card-admin p-4 h-100 <?= $m['status']==='belum_dibaca' ? 'border-start border-warning border-3' : '' ?>">
        <div class="d-flex justify-content-between align-items-start mb-2">
          <div>
            <h6 class="mb-0 fw-bold"><?= htmlspecialchars($m['nama']) ?></h6>
            <small class="text-muted"><a href="mailto:<?= htmlspecialchars($m['email']) ?>" class="text-decoration-none"><?= htmlspecialchars($m['email']) ?></a></small>
          </div>
          <?php if ($m['status']==='belum_dibaca'): ?>
            <span class="badge bg-warning text-dark">Baru</span>
          <?php endif; ?>
        </div>
        <p class="mb-3" style="white-space:pre-line;"><?= htmlspecialchars($m['pesan']) ?></p>
        <div class="d-flex justify-content-between align-items-center mt-auto">
          <small class="text-muted"><i class="bi bi-clock"></i> <?= date('d M Y H:i', strtotime($m['created_at'])) ?></small>
          <div class="d-flex gap-2">
            <?php if ($m['status']==='belum_dibaca'): ?>
              <a href="list.php?aksi=dibaca&id=<?= (int)$m['id'] ?>" class="btn btn-sm btn-outline-success"><i class="bi bi-check2"></i> Tandai Dibaca</a>
            <?php endif; ?>
            <a href="list.php?aksi=hapus&id=<?= (int)$m['id'] ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Yakin hapus pesan ini?');"><i class="bi bi-trash"></i></a>
          </div>
        </div>
      </div>
    </div>
    <?php endforeach; ?>
  </div>
<?php endif; ?>
<?php require_once __DIR__ . '/../includes/bottom.php'; ?>
