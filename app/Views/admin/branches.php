<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sucursales</title>
    <link rel="stylesheet" href="<?= base_url('assets/bootstrap/bootstrap-5.3.8-dist/css/bootstrap.min.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/admin.css') ?>">
</head>
<body class="admin-body py-4">
    <main class="container-fluid px-3 px-lg-4">
        <div class="mx-auto admin-shell">
            <?= view('admin/partials/navbar', ['auth' => $auth, 'currentPage' => $currentPage ?? 'branches']) ?>

            <div class="admin-card p-4 p-lg-5">
                <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-end gap-3 mb-3">
                    <div>
                        <span class="admin-badge">Sucursales</span>
                        <h1 class="admin-title mt-4 mb-2 fw-bold">Gestión de sucursales.</h1>
                        <p class="admin-copy mb-0">Consulta las sucursales del sistema y entra a formularios dedicados para crear o editar sin recargar la pantalla principal.</p>
                    </div>
                </div>
                <div class="d-flex justify-content-between flex-wrap gap-2">
                    <form class="row g-2 flex-grow-1" method="get" action="<?= site_url('admin/branches') ?>">
                        <div class="col-auto">
                            <input type="text" name="search" class="form-control" value="<?= esc($search ?? '') ?>" placeholder="Buscar sucursal">
                        </div>
                        <div class="col-auto">
                            <button type="submit" class="btn admin-secondary-btn">Filtrar</button>
                        </div>
                    </form>
                    <a class="btn admin-primary-btn text-white" href="<?= site_url('admin/branches/create') ?>">Nueva sucursal</a>
                </div>

                <?php if (! empty($status)): ?>
                    <div class="alert alert-info mt-4 mb-0"><?= esc($status) ?></div>
                <?php endif; ?>

                <div class="table-responsive mt-4">
                    <table class="table table-hover align-middle">
                        <thead>
                            <tr>
                                <th>Código</th>
                                <th>Nombre</th>
                                <th>Ciudad</th>
                                <th>Estado</th>
                                <th class="text-end">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($sucursales === []): ?>
                                <tr>
                                    <td colspan="5" class="text-center text-body-secondary py-4">No hay sucursales registradas.</td>
                                </tr>
                            <?php endif; ?>
                            <?php foreach ($sucursales as $sucursal): ?>
                                <tr>
                                    <td><strong><?= esc($sucursal['codigo']) ?></strong></td>
                                    <td><?= esc($sucursal['nombre']) ?></td>
                                    <td><?= esc($sucursal['ciudad'] ?? '-') ?></td>
                                    <td><span class="badge text-bg-light border"><?= esc(ucfirst((string) $sucursal['estado'])) ?></span></td>
                                    <td class="text-end">
                                        <div class="d-inline-flex flex-wrap justify-content-end gap-2">
                                            <a class="btn btn-sm admin-secondary-btn" href="<?= site_url('admin/branches/edit/' . $sucursal['id']) ?>">Editar</a>
                                            <form method="post" action="<?= site_url('admin/branches/toggle/' . $sucursal['id']) ?>">
                                                <?= csrf_field() ?>
                                                <button type="submit" class="btn btn-sm admin-secondary-btn"><?= ($sucursal['estado'] ?? 'activa') === 'activa' ? 'Inactivar' : 'Activar' ?></button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>
    <script src="<?= base_url('assets/bootstrap/bootstrap-5.3.8-dist/js/bootstrap.bundle.min.js') ?>"></script>
</body>
</html>
