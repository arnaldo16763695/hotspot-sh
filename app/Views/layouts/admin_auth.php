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
<body class="admin-body d-flex align-items-center py-4">
    <?= $this->renderSection('content') ?>
    <?= view('admin/partials/footer') ?>
    <?= $this->renderSection('scripts') ?>
</body>
</html>
