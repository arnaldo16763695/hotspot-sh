<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sesiones Hotspot</title>
    <link rel="stylesheet" href="<?= base_url('assets/bootstrap/bootstrap-5.3.8-dist/css/bootstrap.min.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/admin.css') ?>">
</head>
<body class="admin-body py-4">
    <main class="container admin-shell">
        <?= view('admin/partials/navbar', ['auth' => $auth, 'currentPage' => $currentPage ?? 'sessions']) ?>

        <div class="admin-card p-4 p-lg-5">
            <div class="d-flex flex-column flex-lg-row justify-content-between gap-3 align-items-lg-center">
                <div>
                    <span class="admin-badge">Sesiones</span>
                    <h1 class="admin-title mt-4 mb-2 fw-bold">Actividad del hotspot.</h1>
                    <p class="admin-copy mb-0">Monitorea sesiones autorizadas, rechazos, sucursal de origen y datos tecnicos de conexion.</p>
                </div>
            </div>

            <?php if (! empty($status)): ?>
                <div class="alert alert-info mt-4 mb-0"><?= esc($status) ?></div>
            <?php endif; ?>

            <form class="row g-3 mt-1 mb-4" method="get" action="<?= site_url('admin/sessions') ?>">
                <div class="col-12 col-lg-5">
                    <label class="form-label fw-semibold" for="search">Buscar</label>
                    <input id="search" name="search" type="text" class="form-control" value="<?= esc($filters['search'] ?? '') ?>" placeholder="Cliente, celular, MAC o IP">
                </div>
                <div class="col-12 col-md-6 col-lg-3">
                    <label class="form-label fw-semibold" for="autorizado">Estado de acceso</label>
                    <select id="autorizado" name="autorizado" class="form-select">
                        <option value="">Todos</option>
                        <option value="1" <?= (string) ($filters['autorizado'] ?? '') === '1' ? 'selected' : '' ?>>Autorizado</option>
                        <option value="0" <?= (string) ($filters['autorizado'] ?? '') === '0' ? 'selected' : '' ?>>Rechazado</option>
                    </select>
                </div>
                <div class="col-12 col-md-6 col-lg-3">
                    <label class="form-label fw-semibold" for="sucursal_id">Sucursal</label>
                    <select id="sucursal_id" name="sucursal_id" class="form-select">
                        <option value="">Todas</option>
                        <?php foreach ($sucursales as $sucursal): ?>
                            <option value="<?= esc((string) $sucursal['id']) ?>" <?= (string) ($filters['sucursal_id'] ?? '') === (string) $sucursal['id'] ? 'selected' : '' ?>>
                                <?= esc($sucursal['nombre']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-12 col-lg-1 d-flex align-items-end">
                    <button type="submit" class="btn d-flex justify-content-center admin-primary-btn text-white w-100">Filtrar</button>
                </div>
            </form>

            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead>
                        <tr>
                            <th>Cliente</th>
                            <th>Sucursal</th>
                            <th>MAC / IP</th>
                            <th>Inicio</th>
                            <th>Fin</th>
                            <th>Acceso</th>
                            <th>Observaciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($sesiones === []): ?>
                            <tr>
                                <td colspan="7" class="text-center text-body-secondary py-4">No encontramos sesiones con los filtros aplicados.</td>
                            </tr>
                        <?php endif; ?>
                        <?php foreach ($sesiones as $sesion): ?>
                            <tr>
                                <td>
                                    <strong><?= esc($sesion['cliente_nombre']) ?></strong><br>
                                    <span class="text-body-secondary small"><?= esc($sesion['celular']) ?></span>
                                </td>
                                <td><?= esc($sesion['sucursal_nombre'] ?? ($sesion['branch_code'] ?? 'Sin sucursal')) ?></td>
                                <td>
                                    <?= esc($sesion['mac_address'] ?? '-') ?><br>
                                    <span class="text-body-secondary small"><?= esc($sesion['ip_address'] ?? '-') ?></span>
                                </td>
                                <td><?= esc(date('d/m/Y h:i A', strtotime((string) $sesion['fecha_inicio']))) ?></td>
                                <td><?= ! empty($sesion['fecha_fin']) ? esc(date('d/m/Y h:i A', strtotime((string) $sesion['fecha_fin']))) : '-' ?></td>
                                <td>
                                    <?php if ((int) ($sesion['autorizado'] ?? 0) === 1): ?>
                                        <span class="badge text-bg-success">Autorizado</span>
                                    <?php else: ?>
                                        <span class="badge text-bg-danger">Rechazado</span>
                                    <?php endif; ?>
                                </td>
                                <td class="small text-body-secondary"><?= esc($sesion['observaciones'] ?? '') ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <?php if ($pager !== null): ?>
                <div class="mt-4">
                    <?= $pager->links('sesiones', 'default_full') ?>
                </div>
            <?php endif; ?>
        </div>
    </main>
    <script src="<?= base_url('assets/bootstrap/bootstrap-5.3.8-dist/js/bootstrap.bundle.min.js') ?>"></script>
</body>
</html>
