<?php
$pageTitle = 'Profil Sekolah - SMK INFOKOM BOGOR';
$pageDesc = 'Profil SMK INFOKOM BOGOR: sejarah, visi misi, sambutan kepala sekolah, struktur organisasi, dan fasilitas sekolah.';
require_once __DIR__ . '/includes/header.php';

$jenisAktif = $_GET['jenis'] ?? 'sejarah';
$daftarJenis = [
    'sambutan'  => ['Sambutan Kepala Sekolah', 'bi-chat-quote'],
    'sejarah'   => ['Sejarah Sekolah', 'bi-clock-history'],
    'visi_misi' => ['Visi & Misi', 'bi-bullseye'],
    'struktur'  => ['Struktur Organisasi', 'bi-diagram-3'],
    'guru'      => ['Guru & Staff', 'bi-people'],
    'fasilitas' => ['Fasilitas Sekolah', 'bi-buildings'],
];
if (!array_key_exists($jenisAktif, $daftarJenis)) {
    $jenisAktif = 'sejarah';
}

$data = null;
$strukturList = [];
$fasilitasList = [];
$guruList = [];

if ($jenisAktif === 'struktur') {
    $strukturList = $pdo->query("SELECT * FROM struktur_organisasi ORDER BY urutan ASC, id ASC")->fetchAll();

    // Gabungan struktur organisasi + guru & staff, dikelompokkan per tingkatan:
    // kepala sekolah -> wakil -> kepala program keahlian -> guru -> staf
    $guruStaffSemua = $pdo->query("SELECT * FROM guru_staff ORDER BY urutan ASC, id ASC")->fetchAll();
    $tingkat = ['kepsek' => [], 'wakil' => [], 'kaprog' => [], 'guru' => [], 'staf' => []];
    foreach ($strukturList as $s) {
        $p = [
            'nama'    => $s['nama'],
            'jabatan' => $s['jabatan'],
            'foto'    => 'uploads/struktur/' . ($s['foto'] ?: 'default.jpg'),
        ];
        $j = (string)$s['jabatan'];
        if (stripos($j, 'Kepala Sekolah') !== false && stripos($j, 'Wakil') === false) {
            $tingkat['kepsek'][] = $p;
        } elseif (stripos($j, 'Wakil') !== false) {
            $tingkat['wakil'][] = $p;
        } else {
            // Ketua/kepala urusan (tata usaha, perpustakaan, bendahara, dsb) → tenaga kependidikan
            $tingkat['staf'][] = $p;
        }
    }
    foreach ($guruStaffSemua as $g) {
        $p = [
            'nama'    => $g['nama'],
            'jabatan' => $g['jabatan'],
            'foto'    => 'uploads/guru/' . ($g['foto'] ?: 'default.jpg'),
        ];
        $j = (string)$g['jabatan'];
        if (stripos($j, 'Kepala Program Keahlian') !== false) {
            $tingkat['kaprog'][] = $p;
        } elseif (preg_match('/staf/i', $j)) {
            $tingkat['staf'][] = $p;
        } else {
            $tingkat['guru'][] = $p;
        }
    }
} elseif ($jenisAktif === 'fasilitas') {
    $fasilitasList = $pdo->query("SELECT * FROM fasilitas ORDER BY urutan ASC, id ASC")->fetchAll();
} elseif ($jenisAktif === 'guru') {
    $guruList = $pdo->query("SELECT * FROM guru_staff ORDER BY urutan ASC, id ASC")->fetchAll();
} else {
    $stmt = $pdo->prepare("SELECT * FROM profil WHERE jenis = ? LIMIT 1");
    $stmt->execute([$jenisAktif]);
    $data = $stmt->fetch();
}
?>

<div class="page-header">
  <div class="container">
    <div class="page-breadcrumb"><a href="index.php">Beranda</a> <i class="bi bi-chevron-right"></i> Profil Sekolah</div>
    <h1 class="page-title">Profil Sekolah</h1>
  </div>
</div>

<div class="container py-5">
  <div class="row g-4">
    <div class="col-md-3">
      <div class="list-group profile-sidebar">
        <?php foreach ($daftarJenis as $key => $label): ?>
          <a href="profil.php?jenis=<?= $key ?>"
             class="list-group-item list-group-item-action <?= $jenisAktif === $key ? 'active' : '' ?>">
            <i class="bi <?= $label[1] ?>"></i><?= htmlspecialchars($label[0]) ?>
          </a>
        <?php endforeach; ?>
      </div>
    </div>

    <div class="col-md-9">

      <?php if ($jenisAktif === 'struktur'): ?>
        <h4 class="mb-2">Struktur Organisasi Sekolah</h4>
        <p class="text-muted-ink small mb-4">Urutan jabatan dari atas ke bawah: Kepala Sekolah, Wakil Kepala Sekolah, dan Staf / Tenaga Kependidikan.</p>
        <?php
        $adaKonten = false;
        foreach (['kepsek', 'wakil', 'staf'] as $tTingkat) { if (count($tingkat[$tTingkat]) > 0) { $adaKonten = true; break; } }
        // Kotak diagram organisasi: hanya nama & jabatan (tanpa foto), seperti bagan struktur klasik
        $kartuOrg = function (array $p): string {
            return '<div class="org-node">'
                . '<span class="o-jabatan">' . htmlspecialchars($p['jabatan']) . '</span>'
                . '<span class="o-nama">' . htmlspecialchars($p['nama']) . '</span>'
                . '</div>';
        };
        ?>
        <?php if (!$adaKonten): ?>
          <p class="text-muted-ink">Data struktur organisasi belum tersedia.</p>
        <?php else: ?>
        <div class="org-chart">
          <?php if (count($tingkat['kepsek']) > 0): ?>
          <div class="org-node-row">
            <?php foreach ($tingkat['kepsek'] as $pOrg): ?>
              <?= $kartuOrg($pOrg) ?>
            <?php endforeach; ?>
          </div>
          <?php endif; ?>

          <?php if (count($tingkat['wakil']) > 0): ?>
          <div class="org-bus" aria-hidden="true"></div>
          <div class="org-node-row">
            <?php foreach ($tingkat['wakil'] as $pOrg): ?>
              <?= $kartuOrg($pOrg) ?>
            <?php endforeach; ?>
          </div>
          <?php endif; ?>

          <?php if (count($tingkat['staf']) > 0): ?>
          <?php if (count($tingkat['wakil']) > 0 || count($tingkat['kepsek']) > 0): ?>
          <div class="org-bus org-bus-bawah" aria-hidden="true"></div>
          <?php endif; ?>
          <div class="org-node-row org-grid-staf">
            <?php foreach ($tingkat['staf'] as $pOrg): ?>
              <?= $kartuOrg($pOrg) ?>
            <?php endforeach; ?>
          </div>
          <?php endif; ?>
        </div>
        <?php endif; ?>

      <?php elseif ($jenisAktif === 'fasilitas'): ?>
        <h4 class="mb-4">Fasilitas Sekolah</h4>
        <div class="row g-4">
          <?php if (count($fasilitasList) === 0): ?>
            <p class="text-muted-ink">Data fasilitas belum tersedia.</p>
          <?php endif; ?>
          <?php foreach ($fasilitasList as $f): ?>
          <div class="col-md-6">
            <div class="card-editorial h-100">
              <img src="uploads/fasilitas/<?= htmlspecialchars($f['gambar'] ?: 'default.jpg') ?>" alt="<?= htmlspecialchars($f['nama']) ?>"
                   onerror="this.src='https://placehold.co/500x220?text=Fasilitas'">
              <div class="card-body">
                <h6 class="mb-2"><?= htmlspecialchars($f['nama']) ?></h6>
                <p class="small text-muted-ink mb-0"><?= htmlspecialchars($f['deskripsi']) ?></p>
              </div>
            </div>
          </div>
          <?php endforeach; ?>
        </div>

      <?php elseif ($jenisAktif === 'guru'): ?>
        <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
          <h4 class="mb-0">Guru &amp; Staff</h4>
          <a href="guru-staff.php" class="btn btn-sm btn-outline-ink">Lihat Halaman Lengkap</a>
        </div>
        <?php if (count($guruList) === 0): ?>
          <p class="text-muted-ink">Data guru &amp; staff belum tersedia.</p>
        <?php endif; ?>
        <div class="row g-4">
          <?php foreach ($guruList as $g): ?>
          <div class="col-6 col-md-3">
            <div class="people-card">
              <div class="person-frame person-round">
                <img src="uploads/guru/<?= htmlspecialchars($g['foto'] ?: 'default.jpg') ?>" class="people-photo" alt="<?= htmlspecialchars($g['nama']) ?>"
                     onerror="this.src='https://placehold.co/300x400?text=Foto'">
              </div>
              <p class="nama mb-0"><?= htmlspecialchars($g['nama']) ?></p>
              <p class="jabatan"><?= htmlspecialchars($g['jabatan']) ?></p>
            </div>
          </div>
          <?php endforeach; ?>
        </div>

      <?php else: ?>
        <div class="card-editorial p-4">
          <h4 class="mb-3"><?= htmlspecialchars($data['judul'] ?? $daftarJenis[$jenisAktif][0]) ?></h4>
          <div class="row g-4 align-items-start">
            <div class="<?= !empty($data['gambar']) ? 'col-md-8' : 'col-12' ?>">
              <p class="text-muted-ink" style="white-space:pre-line;">
                <?= htmlspecialchars($data['isi'] ?? 'Konten belum tersedia.') ?>
              </p>
            </div>
            <?php if (!empty($data['gambar'])): ?>
            <div class="col-md-4">
              <?php if ($jenisAktif === 'sambutan'): ?>
                <!-- Foto orang (kepsek): bingkai bulat, sama dengan struktur & guru/staf -->
                <div class="person-frame person-round person-lg mx-auto">
                  <img src="uploads/profil/<?= htmlspecialchars($data['gambar']) ?>" class="people-photo" alt="Kepala Sekolah">
                </div>
              <?php else: ?>
                <!-- Foto gedung/kegiatan: bingkai landscape -->
                <img src="uploads/profil/<?= htmlspecialchars($data['gambar']) ?>" class="profile-figure" alt="">
              <?php endif; ?>
            </div>
            <?php endif; ?>
          </div>
        </div>
      <?php endif; ?>

    </div>
  </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
