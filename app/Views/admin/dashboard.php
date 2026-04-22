<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin</title>
    <link rel="stylesheet" href="<?= base_url('assets/bootstrap/bootstrap-5.3.8-dist/css/bootstrap.min.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/admin.css') ?>">
</head>
<body class="admin-body py-4">
    <main class="container admin-shell">
        <div class="admin-card p-4 p-lg-5">
            <div class="d-flex flex-column flex-lg-row justify-content-between gap-3 align-items-lg-center">
                <div>
                    <span class="admin-badge">Dashboard Admin</span>
                    <h1 class="admin-title mt-4 mb-2 fw-bold">Bienvenido, <?= esc($auth['nombre'] ?? 'Administrador') ?>.</h1>
                    <p class="admin-copy mb-0">Tu sesion ya esta protegida y lista para crecer con modulos de clientes, sesiones, campanas y configuracion operativa.</p>
                </div>
                <div class="d-flex flex-wrap gap-2">
                    <a class="btn admin-secondary-btn" href="<?= site_url('hotspot') ?>">Ver portal hotspot</a>
                    <a class="btn admin-primary-btn text-white" href="<?= site_url('admin/logout') ?>">Cerrar sesion</a>
                </div>
            </div>

            <?php if (! empty($status)): ?>
                <div class="alert alert-info mt-4 mb-0"><?= esc($status) ?></div>
            <?php endif; ?>

            <div class="row g-4 mt-1">
                <div class="col-12 col-lg-4">
                    <div class="admin-placeholder h-100 p-4">
                        <h2 class="h5 mb-2">Rol actual</h2>
                        <p class="mb-0"><?= esc($auth['role_name'] ?? 'Sin rol') ?> <span class="text-body-secondary">(<?= esc($auth['role_code'] ?? '-') ?>)</span></p>
                    </div>
                </div>
                <div class="col-12 col-lg-4">
                    <div class="admin-placeholder h-100 p-4">
                        <h2 class="h5 mb-2">Proximo modulo</h2>
                        <p class="mb-0">Clientes, sesiones hotspot y trazabilidad por sucursal.</p>
                    </div>
                </div>
                <div class="col-12 col-lg-4">
                    <div class="admin-placeholder h-100 p-4">
                        <h2 class="h5 mb-2">Seguridad</h2>
                        <p class="mb-0">Acceso protegido por sesion y preparado para restricciones por rol.</p>
                    </div>
                </div>
            </div>
        </div>
    </main>
</body>
</html>
