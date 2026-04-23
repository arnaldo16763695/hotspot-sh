<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Routers</title>
    <link rel="stylesheet" href="<?= base_url('assets/bootstrap/bootstrap-5.3.8-dist/css/bootstrap.min.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/admin.css') ?>">
</head>
<body class="admin-body py-4">
    <main class="container admin-shell">
        <?= view('admin/partials/navbar', ['auth' => $auth, 'currentPage' => $currentPage ?? 'routers']) ?>

        <div class="admin-card p-4 p-lg-5">
            <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-end gap-3 mb-3">
                <div>
                    <span class="admin-badge">Routers</span>
                    <h1 class="admin-title mt-4 mb-2 fw-bold">Gestion de routers MikroTik.</h1>
                    <p class="admin-copy mb-0">Administra los equipos, sus credenciales de integracion y la sucursal a la que pertenecen.</p>
                </div>
                <form class="row g-2" method="get" action="<?= site_url('admin/routers') ?>">
                    <div class="col-auto">
                        <input type="text" name="search" class="form-control" value="<?= esc($search ?? '') ?>" placeholder="Buscar router">
                    </div>
                    <div class="col-auto">
                        <button type="submit" class="btn admin-secondary-btn">Filtrar</button>
                    </div>
                </form>
            </div>

            <?php if (! empty($status)): ?>
                <div class="alert alert-info mt-4 mb-0"><?= esc($status) ?></div>
            <?php endif; ?>

            <?php $routerForm = is_array($old ?? null) && (isset($old['host']) || isset($old['nombre_router'])) ? $old : ($editRouter ?? []); ?>

            <div class="row g-4 mt-1">
                <div class="col-12 col-xl-4">
                    <div class="admin-placeholder p-4">
                        <h2 class="h5 mb-3"><?= ! empty($routerForm['id']) ? 'Editar router' : 'Nuevo router' ?></h2>
                        <form method="post" action="<?= site_url('admin/routers/save') ?>">
                            <?= csrf_field() ?>
                            <input type="hidden" name="id" value="<?= esc((string) ($routerForm['id'] ?? '')) ?>">

                            <div class="mb-3">
                                <label class="form-label fw-semibold" for="router_sucursal_id">Sucursal</label>
                                <select id="router_sucursal_id" name="sucursal_id" class="form-select<?= ! empty($errors['sucursal_id']) ? ' is-invalid' : '' ?>" required>
                                    <option value="">Selecciona una sucursal</option>
                                    <?php foreach ($sucursales as $sucursal): ?>
                                        <option value="<?= esc((string) $sucursal['id']) ?>" <?= (string) ($routerForm['sucursal_id'] ?? '') === (string) $sucursal['id'] ? 'selected' : '' ?>>
                                            <?= esc($sucursal['nombre']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                                <?php if (! empty($errors['sucursal_id'])): ?><div class="invalid-feedback d-block"><?= esc($errors['sucursal_id']) ?></div><?php endif; ?>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-semibold" for="router_codigo">Codigo</label>
                                <input id="router_codigo" name="codigo" type="text" class="form-control<?= ! empty($errors['codigo']) ? ' is-invalid' : '' ?>" value="<?= esc($routerForm['codigo'] ?? '') ?>" required>
                                <?php if (! empty($errors['codigo'])): ?><div class="invalid-feedback d-block"><?= esc($errors['codigo']) ?></div><?php endif; ?>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-semibold" for="router_nombre">Nombre del router</label>
                                <input id="router_nombre" name="nombre_router" type="text" class="form-control<?= ! empty($errors['nombre_router']) ? ' is-invalid' : '' ?>" value="<?= esc($routerForm['nombre_router'] ?? '') ?>" required>
                                <?php if (! empty($errors['nombre_router'])): ?><div class="invalid-feedback d-block"><?= esc($errors['nombre_router']) ?></div><?php endif; ?>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-semibold" for="router_host">Host / IP WireGuard</label>
                                <input id="router_host" name="host" type="text" class="form-control<?= ! empty($errors['host']) ? ' is-invalid' : '' ?>" value="<?= esc($routerForm['host'] ?? '') ?>" required>
                                <?php if (! empty($errors['host'])): ?><div class="invalid-feedback d-block"><?= esc($errors['host']) ?></div><?php endif; ?>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-semibold" for="router_puerto">Puerto</label>
                                <input id="router_puerto" name="puerto" type="number" class="form-control<?= ! empty($errors['puerto']) ? ' is-invalid' : '' ?>" value="<?= esc((string) ($routerForm['puerto'] ?? '443')) ?>" required>
                                <?php if (! empty($errors['puerto'])): ?><div class="invalid-feedback d-block"><?= esc($errors['puerto']) ?></div><?php endif; ?>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-semibold" for="router_usuario">Usuario API</label>
                                <input id="router_usuario" name="usuario" type="text" class="form-control<?= ! empty($errors['usuario']) ? ' is-invalid' : '' ?>" value="<?= esc($routerForm['usuario'] ?? '') ?>" required>
                                <?php if (! empty($errors['usuario'])): ?><div class="invalid-feedback d-block"><?= esc($errors['usuario']) ?></div><?php endif; ?>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-semibold" for="router_password">Password</label>
                                <input id="router_password" name="password" type="text" class="form-control<?= ! empty($errors['password']) ? ' is-invalid' : '' ?>" value="" <?= empty($routerForm['id']) ? 'required' : '' ?>>
                                <div class="form-text">Si estas editando y lo dejas vacio, se conserva el password actual.</div>
                                <?php if (! empty($errors['password'])): ?><div class="invalid-feedback d-block"><?= esc($errors['password']) ?></div><?php endif; ?>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-semibold" for="router_estado">Estado</label>
                                <select id="router_estado" name="estado" class="form-select">
                                    <option value="activo" <?= ($routerForm['estado'] ?? 'activo') === 'activo' ? 'selected' : '' ?>>Activo</option>
                                    <option value="inactivo" <?= ($routerForm['estado'] ?? '') === 'inactivo' ? 'selected' : '' ?>>Inactivo</option>
                                </select>
                                <?php if (! empty($errors['estado'])): ?><div class="invalid-feedback d-block"><?= esc($errors['estado']) ?></div><?php endif; ?>
                            </div>

                            <div class="d-flex flex-wrap gap-2">
                                <button type="submit" class="btn admin-primary-btn text-white"><?= ! empty($routerForm['id']) ? 'Actualizar' : 'Crear' ?></button>
                                <a class="btn admin-secondary-btn" href="<?= site_url('admin/routers') ?>">Limpiar</a>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="col-12 col-xl-8">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead>
                                <tr>
                                    <th>Codigo</th>
                                    <th>Router</th>
                                    <th>Sucursal</th>
                                    <th>Host</th>
                                    <th>Puerto</th>
                                    <th>Estado</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if ($routers === []): ?>
                                    <tr>
                                        <td colspan="7" class="text-center text-body-secondary py-4">No hay routers registrados.</td>
                                    </tr>
                                <?php endif; ?>
                                <?php foreach ($routers as $router): ?>
                                    <tr>
                                        <td><strong><?= esc($router['codigo']) ?></strong></td>
                                        <td><?= esc($router['nombre_router']) ?></td>
                                        <td><?= esc($router['sucursal_nombre'] ?? '-') ?></td>
                                        <td><?= esc($router['host']) ?></td>
                                        <td><?= esc((string) $router['puerto']) ?></td>
                                        <td><span class="badge text-bg-light border"><?= esc(ucfirst((string) $router['estado'])) ?></span></td>
                                        <td>
                                            <div class="d-flex flex-wrap gap-2">
                                                <a class="btn btn-sm admin-secondary-btn" href="<?= site_url('admin/routers?edit=' . $router['id']) ?>">Editar</a>
                                                <form method="post" action="<?= site_url('admin/routers/toggle/' . $router['id']) ?>">
                                                    <?= csrf_field() ?>
                                                    <button type="submit" class="btn btn-sm admin-secondary-btn"><?= ($router['estado'] ?? 'activo') === 'activo' ? 'Inactivar' : 'Activar' ?></button>
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
        </div>
    </main>
    <script src="<?= base_url('assets/bootstrap/bootstrap-5.3.8-dist/js/bootstrap.bundle.min.js') ?>"></script>
</body>
</html>
