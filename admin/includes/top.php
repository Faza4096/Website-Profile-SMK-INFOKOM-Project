<?php
/**
 * Layout atas untuk halaman admin. Set $pageTitleAdmin sebelum include file ini.
 */
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= $pageTitleAdmin ?? 'Admin' ?> - SMK INFOKOM BOGOR</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Sora:wght@600;700&family=Plus+Jakarta+Sans:wght@400;500;600&display=swap" rel="stylesheet">
<style>
  body { font-family: 'Plus Jakarta Sans', sans-serif; background:#F3F1EC; color:#0F2A4A; }
  .bg-ink { background-color:#0F2A4A; }
  h4, h5 { font-family:'Sora',sans-serif; font-weight:600; }
  .card-admin { border:1px solid rgba(20,44,70,0.12); border-radius:6px; background:#fff; }
  .btn-primary { background:#2563EB; border-color:#2563EB; }
  .btn-primary:hover { background:#1D4ED8; border-color:#1D4ED8; }
  .table thead { background:#F3F1EC; }
  .badge-ink { background:#0F2A4A; }
</style>
</head>
<body>
<div class="d-flex">
  <?php include __DIR__ . '/sidebar.php'; ?>
  <div class="flex-grow-1 p-4">
