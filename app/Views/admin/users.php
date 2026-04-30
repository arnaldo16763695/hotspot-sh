<?= $this->extend('layouts/admin') ?>

<?= $this->section('title') ?>Usuarios Admin<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="admin-card p-4 p-lg-5">
                <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-end gap-3 mb-3">
                    <div>
                        <span class="admin-badge">Usuarios admin</span>
                        <h1 class="admin-title mt-4 mb-2 fw-bold">Gestión de usuarios administrativos.</h1>
                        <p class="admin-copy mb-0">Consulta los usuarios del panel y entra a formularios dedicados para crear o editar sin congestionar la tabla.</p>
                    </div>
                </div>
                <div class="d-flex justify-content-between flex-wrap gap-2">
                    <form class="row g-2 flex-grow-1" method="get" action="<?= site_url('admin/users') ?>">
                        <div class="col-auto">
                            <input type="text" name="search" class="form-control" value="<?= esc($search ?? '') ?>" placeholder="Buscar usuario">
                        </div>
                        <div class="col-auto">
                            <button type="submit" class="btn admin-secondary-btn">Filtrar</button>
                        </div>
                    </form>
                    <a class="btn admin-primary-btn text-white" href="<?= site_url('admin/users/create') ?>">Nuevo usuario</a>
                </div>

                <?php if (! empty($status)): ?>
                    <div class="alert alert-info mt-4 mb-0"><?= esc($status) ?></div>
                <?php endif; ?>

                <div class="table-responsive mt-4">
                    <table class="table table-hover align-middle">
                        <thead>
                            <tr>
                                <th>Nombre</th>
                                <th>Correo</th>
                                <th>Rol</th>
                                <th>Estado</th>
                                <th>Último login</th>
                                <th class="text-end">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($usuarios === []): ?>
                                <tr>
                                    <td colspan="6" class="text-center text-body-secondary py-4">No hay usuarios admin registrados.</td>
                                </tr>
                            <?php endif; ?>
                            <?php foreach ($usuarios as $usuario): ?>
                                <tr>
                                    <td><strong><?= esc($usuario['nombre']) ?></strong></td>
                                    <td><?= esc($usuario['email']) ?></td>
                                    <td><?= esc($usuario['role_name'] ?? '-') ?></td>
                                    <td><span class="badge text-bg-light border"><?= esc(ucfirst((string) $usuario['estado'])) ?></span></td>
                                    <td><?= ! empty($usuario['ultimo_login_at']) ? esc(date('d/m/Y h:i A', strtotime((string) $usuario['ultimo_login_at']))) : 'Sin login' ?></td>
                                    <td class="text-end">
                                        <div class="d-inline-flex flex-wrap justify-content-end gap-2">
                                            <a class="btn btn-sm admin-secondary-btn" href="<?= site_url('admin/users/edit/' . $usuario['id']) ?>">Editar</a>
                                            <form method="post" action="<?= site_url('admin/users/toggle/' . $usuario['id']) ?>">
                                                <?= csrf_field() ?>
                                                <button type="submit" class="btn btn-sm admin-secondary-btn"><?= ($usuario['estado'] ?? 'activo') === 'activo' ? 'Inactivar' : 'Activar' ?></button>
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
<?= $this->endSection() ?>
