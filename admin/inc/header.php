<?php
// admin/inc/header.php — layout modern. Dipakai setelah auth.php + config.php.
$flash = function_exists('flash_get') ? flash_get() : null;
$active_menu = $active_menu ?? '';
$page_title = $page_title ?? 'Admin';
$page_sub = $page_sub ?? '';
function nav_cls($key, $active) { return $key === $active ? 'active' : ''; }
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= e($page_title) ?> — Admin E-Book</title>
<link rel="stylesheet" href="../css/bootstrap.min.css">
<link rel="stylesheet" href="../fontawesome/css/all.min.css">
<link rel="stylesheet" href="assets/admin.css">
</head>
<body>
<div class="admin-wrap">
  <aside class="sidebar" id="sidebar">
    <div class="brand">
      <div class="logo"><i class="fas fa-book-open"></i></div>
      <div><h1>E-Book Admin<small>RS • Library & Dokumen</small></h1></div>
    </div>
    <div class="nav-sec">MENU UTAMA</div>
    <nav class="snav">
      <a href="index" class="<?= nav_cls('dashboard',$active_menu) ?>"><i class="fas fa-tachometer-alt"></i>Dashboard</a>
      <a href="dokumen" class="<?= nav_cls('dokumen',$active_menu) ?>"><i class="fas fa-file-pdf"></i>Dokumen</a>
      <a href="dokumen-tambah" class="<?= nav_cls('dokumen-tambah',$active_menu) ?>"><i class="fas fa-cloud-upload-alt"></i>Upload Baru</a>
    </nav>
    <div class="nav-sec">DATA MASTER</div>
    <nav class="snav">
      <a href="kategori" class="<?= nav_cls('kategori',$active_menu) ?>"><i class="fas fa-folder-open"></i>Kategori</a>
      <a href="users" class="<?= nav_cls('users',$active_menu) ?>"><i class="fas fa-users"></i>User Katalog</a>
    </nav>
    <div class="nav-sec">SISTEM</div>
    <nav class="snav">
      <?php if (is_full_admin()): ?>
      <a href="admins" class="<?= nav_cls('admins',$active_menu) ?>"><i class="fas fa-user-shield"></i>Akun Admin</a>
      <?php endif; ?>
      <a href="../folder"><i class="fas fa-globe"></i>Lihat Katalog</a>
    </nav>
    <div class="side-foot"><div class="card-mini"><i class="fas fa-info-circle"></i> Soft-delete aktif: nonaktif ≠ hapus file.</div></div>
  </aside>
  <div class="main">
    <div class="topbar">
      <button id="menuBtn" class="btn btn-sm btn-outline-secondary mr-2" onclick="document.getElementById('sidebar').classList.toggle('open')"><i class="fas fa-bars"></i></button>
      <div>
        <div class="crumb">Admin E-Book / <?= e($page_title) ?></div>
        <h2><?= e($page_title) ?></h2>
      </div>
      <div class="ml-auto d-flex align-items-center" style="gap:10px">
        <div class="user-chip"><span class="avatar"><i class="fas fa-user"></i></span><?= e($_SESSION['admin_username'] ?? '') ?> <span class="badge badge-<?= admin_role()==='supervisi'?'info':'primary' ?>" style="margin-left:4px"><?= e(admin_role()) ?></span></div>
        <a class="btn btn-sm btn-outline-danger" href="logout"><i class="fas fa-sign-out-alt"></i> Keluar</a>
      </div>
    </div>
    <div class="content">
      <?php if ($page_sub): ?><p class="text-muted" style="margin-top:-8px"><?= e($page_sub) ?></p><?php endif; ?>
      <?php if ($flash): ?>
        <div class="alert alert-<?= e($flash['type']) ?> alert-dismissible fade show" style="border-radius:12px">
          <?= e($flash['msg']) ?>
          <button type="button" class="close" data-dismiss="alert">&times;</button>
        </div>
      <?php endif; ?>
