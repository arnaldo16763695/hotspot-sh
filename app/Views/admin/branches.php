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
    <main class="container admin-shell">
        <?= view('admin/partials/navbar', ['auth' => $auth, 'currentPage' => $currentPage ?? 'branches']) ?>

        <div class="admin-card p-4 p-lg-5">
            <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-end gap-3 mb-3">
                <div>
                    <span class="admin-badge">Sucursales</span>
                    <h1 class="admin-title mt-4 mb-2 fw-bold">Gestion de sucursales.</h1>
                    <p class="admin-copy mb-0">Crea, edita y activa o inactiva las sucursales donde operan los hotspots.</p>
                </div>
                <form class="row g-2" method="get" action="<?= site_url('admin/branches') ?>">
                    <div class="col-auto">
                        <input type="text" name="search" class="form-control" value="<?= esc($search ?? '') ?>" placeholder="Buscar sucursal">
                    </div>
                    <div class="col-auto">
                        <button type="submit" class="btn admin-secondary-btn">Filtrar</button>
                    </div>
                </form>
            </div>

            <?php if (! empty($status)): ?>
                <div class="alert alert-info mt-4 mb-0"><?= esc($status) ?></div>
            <?php endif; ?>

            <?php $branchForm = is_array($old ?? null) && ! empty($old['codigo']) ? $old : ($editBranch ?? []); ?>

            <div class="row g-4 mt-1">
                <div class="col-12 col-xl-4">
                    <div class="admin-placeholder p-4">
                        <h2 class="h5 mb-3"><?= ! empty($branchForm['id']) ? 'Editar sucursal' : 'Nueva sucursal' ?></h2>
                        <form method="post" action="<?= site_url('admin/branches/save') ?>">
                            <?= csrf_field() ?>
                            <input type="hidden" name="id" value="<?= esc((string) ($branchForm['id'] ?? '')) ?>">

                            <div class="mb-3">
                                <label class="form-label fw-semibold" for="branch_codigo">Codigo</label>
                                <input id="branch_codigo" name="codigo" type="text" class="form-control<?= ! empty($errors['codigo']) ? ' is-invalid' : '' ?>" value="<?= esc($branchForm['codigo'] ?? '') ?>" required>
                                <?php if (! empty($errors['codigo'])): ?><div class="invalid-feedback d-block"><?= esc($errors['codigo']) ?></div><?php endif; ?>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-semibold" for="branch_nombre">Nombre</label>
                                <input id="branch_nombre" name="nombre" type="text" class="form-control<?= ! empty($errors['nombre']) ? ' is-invalid' : '' ?>" value="<?= esc($branchForm['nombre'] ?? '') ?>" required>
                                <?php if (! empty($errors['nombre'])): ?><div class="invalid-feedback d-block"><?= esc($errors['nombre']) ?></div><?php endif; ?>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-semibold" for="branch_ciudad">Ciudad</label>
                                <input id="branch_ciudad" name="ciudad" type="text" class="form-control<?= ! empty($errors['ciudad']) ? ' is-invalid' : '' ?>" value="<?= esc($branchForm['ciudad'] ?? '') ?>">
                                <?php if (! empty($errors['ciudad'])): ?><div class="invalid-feedback d-block"><?= esc($errors['ciudad']) ?></div><?php endif; ?>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-semibold" for="branch_direccion">Direccion</label>
                                <textarea id="branch_direccion" name="direccion" class="form-control<?= ! empty($errors['direccion']) ? ' is-invalid' : '' ?>" rows="3"><?= esc($branchForm['direccion'] ?? '') ?></textarea>
                                <?php if (! empty($errors['direccion'])): ?><div class="invalid-feedback d-block"><?= esc($errors['direccion']) ?></div><?php endif; ?>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-semibold" for="branch_estado">Estado</label>
                                <select id="branch_estado" name="estado" class="form-select">
                                    <option value="activa" <?= ($branchForm['estado'] ?? 'activa') === 'activa' ? 'selected' : '' ?>>Activa</option>
                                    <option value="inactiva" <?= ($branchForm['estado'] ?? '') === 'inactiva' ? 'selected' : '' ?>>Inactiva</option>
                                </select>
                                <?php if (! empty($errors['estado'])): ?><div class="invalid-feedback d-block"><?= esc($errors['estado']) ?></div><?php endif; ?>
                            </div>

                            <div class="d-flex flex-wrap gap-2">
                                <button type="submit" class="btn admin-primary-btn text-white"><?= ! empty($branchForm['id']) ? 'Actualizar' : 'Crear' ?></button>
                                <a class="btn admin-secondary-btn" href="<?= site_url('admin/branches') ?>">Limpiar</a>
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
                                    <th>Nombre</th>
                                    <th>Ciudad</th>
                                    <th>Estado</th>
                                    <th>Acciones</th>
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
                                        <td>
                                            <div class="d-flex flex-wrap gap-2">
                                                <a class="btn btn-sm admin-secondary-btn" href="<?= site_url('admin/branches?edit=' . $sucursal['id']) ?>">Editar</a>
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
        </div>
    </main>
    <script src="<?= base_url('assets/bootstrap/bootstrap-5.3.8-dist/js/bootstrap.bundle.min.js') ?>"></script>
</body>
</html>
