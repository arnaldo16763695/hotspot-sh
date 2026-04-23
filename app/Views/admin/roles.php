<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Roles Admin</title>
    <link rel="stylesheet" href="<?= base_url('assets/bootstrap/bootstrap-5.3.8-dist/css/bootstrap.min.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/admin.css') ?>">
</head>
<body class="admin-body py-4">
    <main class="container admin-shell">
        <?= view('admin/partials/navbar', ['auth' => $auth, 'currentPage' => $currentPage ?? 'roles']) ?>

        <div class="admin-card p-4 p-lg-5">
            <div>
                <span class="admin-badge">Roles</span>
                <h1 class="admin-title mt-4 mb-2 fw-bold">Roles administrativos.</h1>
                <p class="admin-copy mb-0">Aqui se muestran los roles base del sistema. En una siguiente fase podemos convertirlo en un CRUD completo de permisos.</p>
            </div>

            <?php if (! empty($status)): ?>
                <div class="alert alert-info mt-4 mb-0"><?= esc($status) ?></div>
            <?php endif; ?>

            <div class="table-responsive mt-4">
                <table class="table table-hover align-middle">
                    <thead>
                        <tr>
                            <th>Codigo</th>
                            <th>Nombre</th>
                            <th>Descripcion</th>
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
    </main>
    <script src="<?= base_url('assets/bootstrap/bootstrap-5.3.8-dist/js/bootstrap.bundle.min.js') ?>"></script>
</body>
</html>
