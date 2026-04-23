<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Acceso activado</title>
    <link rel="stylesheet" href="<?= base_url('assets/bootstrap/bootstrap-5.3.8-dist/css/bootstrap.min.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/hotspot.css') ?>">
</head>
<body class="hotspot-success-body d-flex align-items-center justify-content-center p-3">
    <main class="hotspot-success-card">
        <h1 class="display-6 fw-bold mb-3">Acceso activado</h1>
        <p class="mb-3"><?= esc($nombre) ?>, tu navegación gratuita fue habilitada correctamente.</p>
        <?php if (! empty($branch_name)): ?>
            <p class="mb-3">Sucursal detectada: <strong><?= esc($branch_name) ?></strong><?php if (! empty($router_name)): ?> mediante el router <strong><?= esc($router_name) ?></strong><?php endif; ?>.</p>
        <?php endif; ?>
        <p class="mb-0">El acceso ya fue autorizado directamente en el MikroTik y la sesión quedó registrada en el sistema.</p>
        <div class="hotspot-success-highlight mt-4 p-3 fw-semibold">
            Tu acceso estará vigente hasta: <?= esc(date('d/m/Y h:i A', strtotime((string) $expires_at))) ?>
        </div>
        <a class="btn hotspot-outline-btn mt-4" href="<?= site_url('hotspot') ?>">Volver al portal</a>
    </main>
</body>
</html>
