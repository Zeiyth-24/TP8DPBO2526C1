<?php declare(strict_types=1); ?>
<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>SIAKAD | Ilmu Komputer UPI</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Google+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
  <link href="assets/vendor/bootstrap.min.css" rel="stylesheet">
  <link href="assets/app.css" rel="stylesheet">
</head>
<body>
<a class="skip-link" href="#main-content">Langsung ke isi</a>

<header>
  <nav class="navbar navbar-expand navbar-light app-nav">
    <div class="container">
      <a class="navbar-brand campus-brand" href="index.php?route=lecturers">
        <?php if (is_file(__DIR__ . '/../../public/assets/upi-logo.png')): ?>
          <img class="campus-logo" src="assets/upi-logo.png" alt="Logo Universitas Pendidikan Indonesia" width="126" height="48">
        <?php endif; ?>
        <span class="campus-identity">
          Ilmu Komputer
          <small>Universitas Pendidikan Indonesia</small>
        </span>
      </a>
      <div class="navbar-nav ms-auto" aria-label="Menu utama">
        <?php foreach (['lecturers' => 'Dosen', 'courses' => 'Mata Kuliah', 'assignments' => 'Pengampu'] as $key => $label): ?>
          <a class="nav-link<?= ($_GET['route'] ?? 'lecturers') === $key ? ' active' : '' ?>" <?= ($_GET['route'] ?? 'lecturers') === $key ? 'aria-current="page"' : '' ?> href="index.php?route=<?= $key ?>"><?= $label ?></a>
        <?php endforeach; ?>
      </div>
    </div>
  </nav>
</header>

<main id="main-content" class="container page-shell" tabindex="-1">
  <p class="project-context">SIAKAD <span aria-hidden="true">/</span> Proyek Praktikum DPBO</p>

  <?php if (!empty($flash_success)): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
      <?= htmlspecialchars($flash_success) ?>
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
    </div>
  <?php endif; ?>

  <?php if (!empty($flash_error)): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
      <?= htmlspecialchars($flash_error) ?>
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
    </div>
  <?php endif; ?>
