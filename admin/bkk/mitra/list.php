<?php
require_once __DIR__ . '/../../includes/auth.php';
require_once __DIR__ . '/../../../includes/koneksi.php';
require_once __DIR__ . '/../../includes/urut.php';
require_once __DIR__ . '/../../../includes/migrasi-mitra.php';

mitra_migrasi_jenis($pdo);

// Geser urutan baris (tombol panah naik/turun) lalu kembali ke daftar
if (isset($_GET['aksi'])) {
    urut_pindah($pdo, 'bkk_mitra', (int)($_GET['id'] ?? 0), (string)$_GET['aksi']);
    header('Location: list.php?sukses=' . urlencode('Urutan data diperbarui.'));
    exit;
}

// Filter jenis mitra: semua / industri / universitas
$filterJenis = $_GET['jenis'] ?? 'semua';
$sql = "SELECT * FROM bkk_mitra";
if (in_array($filterJenis, ['industri', 'universitas'], true)) {
    $sql .= " WHERE jenis = " . $pdo->quote($filterJenis);
}
$sql .= " ORDER BY urutan ASC, id ASC";
$data = $pdo->query($sql)->fetchAll();

$pageTitleAdmin = 'Mitra Kerja Sama BKK';
require_once __DIR__ . '/../../includes/top.php';

$badgeJenis = ['industri' => 'bg-primary', 'universitas' => 'bg-success'];
?>
<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
  <h4 class="mb-0">Mitra Kerja Sama</h4>
  <a href="tambah.php" class="btn btn-primary text-white"><i class="bi bi-plus-lg"></i> Tambah Mitra</a>
</div>

<?php if (isset($_GET['sukses'])): ?><div class="alert alert-success"><?= htmlspecialchars($_GET['sukses']) ?></div><?php endif; ?>

<div class="d-flex flex-wrap gap-2 mb-3">
  <?php foreach (['semua' => 'Semua', 'industri' => 'Industri', 'universitas' => 'Universitas'] as $key => $label): ?>
    <a href="list.php?jenis=<?= $key ?>" class="btn btn-sm <?= $filterJenis === $key ? 'btn-primary text-white' : 'btn-outline-secondary' ?>"><?= $label ?></a>
  <?php endforeach; ?>
</div>

<div class="card card-admin">
  <div class="table-responsive">
    <table class="table table-hover align-middle mb-0">
      <thead><tr><th>#</th><th>Logo</th><th>Nama</th><th>Jenis</th><th>Bidang</th><th>Alamat</th><th class="text-end">Aksi</th></tr></thead>
      <tbody>
        <?php if (!$data): ?><tr><td colspan="7" class="text-center text-muted py-4">Belum ada data.</td></tr><?php endif; ?>
        <?php foreach ($data as $i => $d): ?>
        <tr>
          <td><?= $i + 1 ?></td>
          <td>
            <?php if ($d['logo']): ?>
              <img src="../../../uploads/bkk/<?= htmlspecialchars($d['logo']) ?>" width="48" height="48" style="object-fit:cover;border-radius:10px;" onerror="this.src='https://placehold.co/48x48'">
            <?php else: ?>
              <span class="badge badge-ink"><?= htmlspecialchars(strtoupper(mb_substr($d['nama_perusahaan'], 0, 2))) ?></span>
            <?php endif; ?>
          </td>
          <td><strong><?= htmlspecialchars($d['nama_perusahaan']) ?></strong></td>
          <td><span class="badge <?= $badgeJenis[$d['jenis']] ?? 'bg-primary' ?>"><?= $d['jenis'] === 'universitas' ? 'Universitas' : 'Industri' ?></span></td>
          <td><?= htmlspecialchars($d['bidang'] ?? '-') ?></td>
          <td class="text-muted small"><?= htmlspecialchars(mb_strimwidth($d['alamat'] ?? '-', 0, 50, '...')) ?></td>
          <td class="text-end">
            <a href="list.php?aksi=naik&id=<?= $d['id'] ?><?= $filterJenis !== 'semua' ? '&jenis=' . urlencode($filterJenis) : '' ?>" class="btn btn-sm btn-outline-secondary" title="Naik"<?= $i === 0 ? ' aria-disabled="true"' : '' ?>><i class="bi bi-arrow-up"></i></a>
            <a href="list.php?aksi=turun&id=<?= $d['id'] ?><?= $filterJenis !== 'semua' ? '&jenis=' . urlencode($filterJenis) : '' ?>" class="btn btn-sm btn-outline-secondary" title="Turun"<?= $i === count($data) - 1 ? ' aria-disabled="true"' : '' ?>><i class="bi bi-arrow-down"></i></a>
            <a href="edit.php?id=<?= $d['id'] ?>" class="btn btn-sm btn-outline-warning"><i class="bi bi-pencil-square"></i></a>
            <a href="hapus.php?id=<?= $d['id'] ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Yakin hapus mitra ini?');"><i class="bi bi-trash"></i></a>
          </td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>
<?php require_once __DIR__ . '/../../includes/bottom.php'; ?>
