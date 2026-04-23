<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Clientes</title>
    <link rel="stylesheet" href="<?= base_url('assets/bootstrap/bootstrap-5.3.8-dist/css/bootstrap.min.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/admin.css') ?>">
</head>
<body class="admin-body py-4">
    <main class="container admin-shell">
        <?= view('admin/partials/navbar', ['auth' => $auth, 'currentPage' => $currentPage ?? 'customers']) ?>

        <div class="admin-card p-4 p-lg-5">
            <div class="d-flex flex-column flex-lg-row justify-content-between gap-3 align-items-lg-center">
                <div>
                    <span class="admin-badge">Clientes</span>
                    <h1 class="admin-title mt-4 mb-2 fw-bold">Base de clientes registrados.</h1>
                    <p class="admin-copy mb-0">Consulta, filtra y revisa los clientes capturados por el portal cautivo desde todas las sucursales.</p>
                </div>
            </div>

            <?php if (! empty($status)): ?>
                <div class="alert alert-info mt-4 mb-0"><?= esc($status) ?></div>
            <?php endif; ?>

            <form class="row g-3 mt-1 mb-4" method="get" action="<?= site_url('admin/customers') ?>">
                <div class="col-12 col-lg-5">
                    <label class="form-label fw-semibold" for="search">Buscar</label>
                    <input id="search" name="search" type="text" class="form-control" value="<?= esc($filters['search'] ?? '') ?>" placeholder="Nombre, celular o correo">
                </div>
                <div class="col-12 col-md-6 col-lg-3">
                    <label class="form-label fw-semibold" for="estado">Estado</label>
                    <select id="estado" name="estado" class="form-select">
                        <option value="">Todos</option>
                        <option value="activo" <?= ($filters['estado'] ?? '') === 'activo' ? 'selected' : '' ?>>Activo</option>
                        <option value="bloqueado" <?= ($filters['estado'] ?? '') === 'bloqueado' ? 'selected' : '' ?>>Bloqueado</option>
                        <option value="pendiente" <?= ($filters['estado'] ?? '') === 'pendiente' ? 'selected' : '' ?>>Pendiente</option>
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
                    <button type="submit" class="btn d-flex justify-content-center  admin-primary-btn text-white w-100">Filtrar</button>
                </div>
            </form>

            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead>
                        <tr>
                            <th>Cliente</th>
                            <th>Contacto</th>
                            <th>Promos</th>
                            <th>Estado</th>
                            <th>Registro</th>
                            <th>Ultima sesion</th>
                            <th>Sucursal</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($clientes === []): ?>
                            <tr>
                                <td colspan="7" class="text-center text-body-secondary py-4">No encontramos clientes con los filtros aplicados.</td>
                            </tr>
                        <?php endif; ?>
                        <?php foreach ($clientes as $cliente): ?>
                            <tr>
                                <td>
                                    <a class="text-decoration-none fw-semibold" href="<?= site_url('admin/customers/' . $cliente['id']) ?>"><?= esc($cliente['nombre']) ?></a><br>
                                    <span class="text-body-secondary small">ID <?= esc((string) $cliente['id']) ?></span>
                                </td>
                                <td>
                                    <?= esc($cliente['celular']) ?><br>
                                    <span class="text-body-secondary small"><?= esc($cliente['correo']) ?></span>
                                </td>
                                <td><?= (int) ($cliente['acepta_promociones'] ?? 0) === 1 ? 'Si' : 'No' ?></td>
                                <td><span class="badge text-bg-light border"><?= esc(ucfirst((string) $cliente['estado'])) ?></span></td>
                                <td><?= esc(date('d/m/Y h:i A', strtotime((string) $cliente['fecha_registro']))) ?></td>
                                <td>
                                    <?= ! empty($cliente['ultima_sesion']) ? esc(date('d/m/Y h:i A', strtotime((string) $cliente['ultima_sesion']))) : 'Sin sesiones' ?>
                                </td>
                                <td><?= esc($cliente['ultima_sucursal'] ?? 'Sin sucursal') ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <?php if ($pager !== null): ?>
                <div class="mt-4">
                    <?= $pager->links('clientes', 'default_full') ?>
                </div>
            <?php endif; ?>
        </div>
    </main>
    <script src="<?= base_url('assets/bootstrap/bootstrap-5.3.8-dist/js/bootstrap.bundle.min.js') ?>"></script>
</body>
</html>
