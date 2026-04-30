<?= $this->extend('layouts/admin') ?>

<?= $this->section('title') ?>Roles Admin<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="admin-card p-4 p-lg-5">
            <div>
                <span class="admin-badge">Roles</span>
                <h1 class="admin-title mt-4 mb-2 fw-bold">Roles administrativos.</h1>
                <p class="admin-copy mb-0">Aquí se muestran los roles base del sistema. En una siguiente fase podemos convertirlo en un CRUD completo de permisos.</p>
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
                            <th>Descripción</th>
                            <th>Estado</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($roles as $role): ?>
                            <tr>
                                <td><strong><?= esc($role['codigo']) ?></strong></td>
                                <td><?= esc($role['nombre']) ?></td>
                                <td><?= esc($role['descripcion'] ?? '-') ?></td>
                                <td><span class="badge text-bg-light border"><?= esc(ucfirst((string) $role['estado'])) ?></span></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
<?= $this->endSection() ?>
