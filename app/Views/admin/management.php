<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administracion Admin</title>
    <link rel="stylesheet" href="<?= base_url('assets/bootstrap/bootstrap-5.3.8-dist/css/bootstrap.min.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/admin.css') ?>">
</head>
<body class="admin-body py-4">
    <main class="container admin-shell">
        <?= view('admin/partials/navbar', ['auth' => $auth, 'currentPage' => $currentPage ?? 'management']) ?>

        <div class="admin-card p-4 p-lg-5">
            <div class="d-flex flex-column flex-lg-row justify-content-between gap-3 align-items-lg-center">
                <div>
                    <span class="admin-badge">Administracion</span>
                    <h1 class="admin-title mt-4 mb-2 fw-bold">Centro de configuracion.</h1>
                    <p class="admin-copy mb-0">Aqui vamos a concentrar la administracion de sucursales, routers, usuarios administrativos y configuraciones operativas del sistema.</p>
                </div>
            </div>

            <?php if (! empty($status)): ?>
                <div class="alert alert-info mt-4 mb-0"><?= esc($status) ?></div>
            <?php endif; ?>

            <div class="row g-4 mt-1">
                <div class="col-12 col-lg-6" id="usuarios">
                    <div class="admin-placeholder h-100 p-4">
                        <h2 class="h5 mb-2">Usuarios</h2>
                        <p class="mb-0">Aqui quedara la gestion de usuarios administradores, activacion, desactivacion y cambios de credenciales.</p>
                    </div>
                </div>
                <div class="col-12 col-lg-6" id="roles">
                    <div class="admin-placeholder h-100 p-4">
                        <h2 class="h5 mb-2">Roles</h2>
                        <p class="mb-0">Espacio reservado para extender permisos por rol y futuras restricciones por modulo.</p>
                    </div>
                </div>
                <div class="col-12 col-lg-6" id="sucursales">
                    <div class="admin-placeholder h-100 p-4">
                        <h2 class="h5 mb-2">Sucursales</h2>
                        <p class="mb-0">Siguiente modulo recomendado para centralizar puntos de acceso y datos operativos.</p>
                    </div>
                </div>
                <div class="col-12 col-lg-6" id="routers">
                    <div class="admin-placeholder h-100 p-4">
                        <h2 class="h5 mb-2">Routers MikroTik</h2>
                        <p class="mb-0">Aqui quedara el CRUD de routers, host por WireGuard, estado y credenciales de integracion.</p>
                    </div>
                </div>
            </div>
        </div>
    </main>
    <script src="<?= base_url('assets/bootstrap/bootstrap-5.3.8-dist/js/bootstrap.bundle.min.js') ?>"></script>
</body>
</html>
