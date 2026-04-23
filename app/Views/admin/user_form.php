<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= ($mode ?? 'create') === 'edit' ? 'Editar usuario admin' : 'Nuevo usuario admin' ?></title>
    <link rel="stylesheet" href="<?= base_url('assets/bootstrap/bootstrap-5.3.8-dist/css/bootstrap.min.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/admin.css') ?>">
</head>
<body class="admin-body py-4">
    <main class="container admin-shell">
        <?= view('admin/partials/navbar', ['auth' => $auth, 'currentPage' => $currentPage ?? 'users']) ?>

        <?php $userForm = is_array($old ?? null) && ! empty($old) ? $old : ($user ?? []); ?>

        <div class="admin-card p-4 p-lg-5">
            <div class="d-flex flex-column flex-lg-row justify-content-between gap-3 align-items-lg-center">
                <div>
                    <span class="admin-badge">Usuarios admin</span>
                    <h1 class="admin-title mt-4 mb-2 fw-bold"><?= ($mode ?? 'create') === 'edit' ? 'Editar usuario.' : 'Crear usuario.' ?></h1>
                    <p class="admin-copy mb-0">Completa los datos del usuario administrativo y asigna el rol que le corresponde dentro del panel.</p>
                </div>
                <a class="btn admin-secondary-btn" href="<?= site_url('admin/users') ?>">Volver al listado</a>
            </div>

            <?php if (! empty($status)): ?>
                <div class="alert alert-info mt-4 mb-0"><?= esc($status) ?></div>
            <?php endif; ?>

            <div class="row justify-content-center mt-1">
                <div class="col-12 col-xl-8">
                    <div class="admin-placeholder p-4 mt-4">
                        <form method="post" action="<?= site_url('admin/users/save') ?>">
                            <?= csrf_field() ?>
                            <input type="hidden" name="id" value="<?= esc((string) ($userForm['id'] ?? '')) ?>">

                            <div class="row g-3">
                                <div class="col-12">
                                    <label class="form-label fw-semibold" for="user_nombre">Nombre</label>
                                    <input id="user_nombre" name="nombre" type="text" class="form-control<?= ! empty($errors['nombre']) ? ' is-invalid' : '' ?>" value="<?= esc($userForm['nombre'] ?? '') ?>" required>
                                    <?php if (! empty($errors['nombre'])): ?><div class="invalid-feedback d-block"><?= esc($errors['nombre']) ?></div><?php endif; ?>
                                </div>

                                <div class="col-12 col-lg-6">
                                    <label class="form-label fw-semibold" for="user_email">Correo</label>
                                    <input id="user_email" name="email" type="email" class="form-control<?= ! empty($errors['email']) ? ' is-invalid' : '' ?>" value="<?= esc($userForm['email'] ?? '') ?>" required>
                                    <?php if (! empty($errors['email'])): ?><div class="invalid-feedback d-block"><?= esc($errors['email']) ?></div><?php endif; ?>
                                </div>

                                <div class="col-12 col-lg-6">
                                    <label class="form-label fw-semibold" for="user_role_id">Rol</label>
                                    <select id="user_role_id" name="role_id" class="form-select<?= ! empty($errors['role_id']) ? ' is-invalid' : '' ?>" required>
                                        <option value="">Selecciona un rol</option>
                                        <?php foreach ($roles as $role): ?>
                                            <option value="<?= esc((string) $role['id']) ?>" <?= (string) ($userForm['role_id'] ?? '') === (string) $role['id'] ? 'selected' : '' ?>>
                                                <?= esc($role['nombre']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                    <?php if (! empty($errors['role_id'])): ?><div class="invalid-feedback d-block"><?= esc($errors['role_id']) ?></div><?php endif; ?>
                                </div>

                                <div class="col-12 col-lg-6">
                                    <label class="form-label fw-semibold" for="user_password">Contraseña</label>
                                    <input id="user_password" name="password" type="password" class="form-control<?= ! empty($errors['password']) ? ' is-invalid' : '' ?>" <?= empty($userForm['id']) ? 'required' : '' ?>>
                                    <div class="form-text">En edición puedes dejarla vacía para conservar la contraseña actual.</div>
                                    <?php if (! empty($errors['password'])): ?><div class="invalid-feedback d-block"><?= esc($errors['password']) ?></div><?php endif; ?>
                                </div>

                                <div class="col-12 col-lg-6">
                                    <label class="form-label fw-semibold" for="user_estado">Estado</label>
                                    <select id="user_estado" name="estado" class="form-select<?= ! empty($errors['estado']) ? ' is-invalid' : '' ?>">
                                        <option value="activo" <?= ($userForm['estado'] ?? 'activo') === 'activo' ? 'selected' : '' ?>>Activo</option>
                                        <option value="inactivo" <?= ($userForm['estado'] ?? '') === 'inactivo' ? 'selected' : '' ?>>Inactivo</option>
                                    </select>
                                    <?php if (! empty($errors['estado'])): ?><div class="invalid-feedback d-block"><?= esc($errors['estado']) ?></div><?php endif; ?>
                                </div>
                            </div>

                            <div class="d-flex flex-wrap gap-2 mt-4">
                                <button type="submit" class="btn admin-primary-btn text-white"><?= ($mode ?? 'create') === 'edit' ? 'Actualizar usuario' : 'Crear usuario' ?></button>
                                <a class="btn admin-secondary-btn" href="<?= site_url('admin/users') ?>">Cancelar</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </main>
    <script src="<?= base_url('assets/bootstrap/bootstrap-5.3.8-dist/js/bootstrap.bundle.min.js') ?>"></script>
</body>
</html>
