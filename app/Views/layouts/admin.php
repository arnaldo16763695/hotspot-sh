<?php
$pageTitle = trim((string) $this->renderSection('title'));
$pageTitle = $pageTitle !== '' ? $pageTitle : 'Admin';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($pageTitle) ?></title>
    <link rel="icon" type="image/png" href="<?= base_url('assets/img/sh-logo.png') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/bootstrap/bootstrap-5.3.8-dist/css/bootstrap.min.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/admin.css') ?>">
    <?= $this->renderSection('head') ?>
</head>
<body class="admin-body py-4">
    <main class="container-fluid px-3 px-lg-4">
        <div class="mx-auto admin-shell">
            <?= view('admin/partials/navbar', ['auth' => $auth ?? [], 'currentPage' => $currentPage ?? '']) ?>
            <?= $this->renderSection('content') ?>
        </div>
    </main>
    <?= view('admin/partials/footer') ?>
    <?= $this->renderSection('scripts') ?>
</body>
</html>
