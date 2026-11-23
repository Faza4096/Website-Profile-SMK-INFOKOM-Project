<?php
session_start();
require_once __DIR__ . '/../includes/koneksi.php';

if (isset($_SESSION['admin_id'])) {
    header('Location: index.php');
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($username === '' || $password === '') {
        $error = 'Username dan password wajib diisi.';
    } else {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ? LIMIT 1");
        $stmt->execute([$username]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            session_regenerate_id(true);
            $_SESSION['admin_id']    = $user['id'];
            $_SESSION['admin_nama']  = $user['nama_lengkap'];
            $_SESSION['admin_level'] = $user['level'];
            header('Location: index.php');
            exit;
        } else {
            $error = 'Username atau password salah.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Login Admin - SMK INFOKOM BOGOR</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Sora:wght@600;700&family=Plus+Jakarta+Sans:wght@400;500;600&display=swap" rel="stylesheet">
<style>
  body {
    background-color: #0F2A4A;
    background-image:
      repeating-linear-gradient(0deg, rgba(255,255,255,0.035) 0 1px, transparent 1px 32px),
      repeating-linear-gradient(90deg, rgba(255,255,255,0.035) 0 1px, transparent 1px 32px);
    height:100vh; display:flex; align-items:center;
    font-family: 'Plus Jakarta Sans', sans-serif;
  }
  .login-card { max-width: 400px; margin:auto; border-radius: 6px; }
  .brand-mark {
    width: 52px; height: 52px; border-radius: 50%;
    background: #0F2A4A; color:#fff; display:flex; align-items:center; justify-content:center;
    font-family:'Sora',sans-serif; font-weight:600; overflow:hidden;
  }
  .brand-mark img { width:100%; height:100%; object-fit:cover; }
  h5 { font-family:'Sora', sans-serif; font-weight:600; }
  .btn-signal { background:#E8A33D; border-color:#E8A33D; color:#0F2A4A; font-weight:600; }
  .btn-signal:hover { background:#C9861F; border-color:#C9861F; color:#0F2A4A; }
</style>
</head>
<body>
<div class="card login-card shadow-lg border-0 p-4 w-100">
  <div class="text-center mb-3">
    <div class="brand-mark mx-auto mb-2">
      <img src="../assets/img/logo.png" alt="Logo" onerror="this.style.display='none'; this.parentElement.innerHTML='SI';">
    </div>
    <h5 class="mb-0">Admin Panel</h5>
    <p class="text-muted small mb-0">SMK INFOKOM BOGOR</p>
  </div>

  <?php if ($error): ?>
    <div class="alert alert-danger py-2 small"><?= htmlspecialchars($error) ?></div>
  <?php endif; ?>

  <form method="POST" action="login.php" autocomplete="off">
    <div class="mb-3">
      <label class="form-label small fw-semibold">Username</label>
      <input type="text" name="username" class="form-control" required autofocus>
    </div>
    <div class="mb-3">
      <label class="form-label small fw-semibold">Password</label>
      <input type="password" name="password" class="form-control" required>
    </div>
    <button type="submit" class="btn btn-signal w-100">Masuk</button>
  </form>
</div>
</body>
</html>
