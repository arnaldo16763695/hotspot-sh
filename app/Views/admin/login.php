<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin</title>
    <link rel="stylesheet" href="<?= base_url('assets/bootstrap/bootstrap-5.3.8-dist/css/bootstrap.min.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/admin.css') ?>">
</head>
<body class="admin-body d-flex align-items-center py-4">
    <main class="container admin-shell">
        <div class="row g-4">
            <section class="col-12 col-lg-7">
                <div class="admin-card h-100 p-4 p-lg-5">
                    <span class="admin-badge">Acceso administrativo</span>
                    <h1 class="admin-title mt-4 mb-3 fw-bold">Panel privado del hotspot.</h1>
                    <p class="admin-copy mb-0">Desde aquí se irán habilitando los módulos de clientes, sesiones, sucursales, routers y futuras campañas. El portal cautivo público sigue disponible aparte.</p>

                    <div class="mt-4 d-grid gap-3">
                        <div class="admin-item">
                            <strong class="d-block mb-1">Login protegido</strong>
                            <p class="admin-copy mb-0">El acceso al admin ya usa sesiones y contraseñas con hash seguro.</p>
                        </div>
                        <div class="admin-item">
                            <strong class="d-block mb-1">Roles base</strong>
                            <p class="admin-copy mb-0">El sistema ya queda preparado para super admin, marketing y operaciones.</p>
                        </div>
                        <div class="admin-item">
                            <strong class="d-block mb-1">Portal separado</strong>
                            <p class="admin-copy mb-0">El flujo del hotspot sigue viviendo en su propia ruta pública y no depende de este login.</p>
                        </div>
                    </div>
                </div>
            </section>

            <section class="col-12 col-lg-5">
                <div class="admin-card h-100 p-4 p-lg-5">
                    <h2 class="h3 mb-2">Iniciar sesión</h2>
                    <p class="admin-copy mb-0">Ingresa con tu usuario administrador para entrar al panel.</p>

                    <?php if (! empty($status)): ?>
                        <div class="alert alert-warning mt-4 mb-0"><?= esc($status) ?></div>
                    <?php endif; ?>

                    <form class="mt-4" method="post" action="<?= site_url('admin/login') ?>">
                        <?= csrf_field() ?>

                        <div class="mb-3">
                            <label class="form-label fw-semibold" for="email">Correo</label>
                            <input id="email" name="email" type="email" class="form-control<?= ! empty($errors['email']) ? ' is-invalid' : '' ?>" value="<?= esc($old['email'] ?? '') ?>" required>
                            <?php if (! empty($errors['email'])): ?><div class="invalid-feedback d-block"><?= esc($errors['email']) ?></div><?php endif; ?>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold" for="password">Contraseña</label>
                            <input id="password" name="password" type="password" class="form-control<?= ! empty($errors['password']) ? ' is-invalid' : '' ?>" required>
                            <?php if (! empty($errors['password'])): ?><div class="invalid-feedback d-block"><?= esc($errors['password']) ?></div><?php endif; ?>
                        </div>

                        <div class="d-flex flex-wrap gap-2 mt-4">
                            <button type="submit" class="btn admin-primary-btn text-white">Entrar al admin</button>
                            <a class="btn admin-secondary-btn" href="<?= site_url('hotspot') ?>">Ir al portal hotspot</a>
                        </div>
                    </form>

                    <div class="admin-placeholder mt-4 p-3">
                        Usuario inicial del seeder:
                        <strong>admin@wifi.ajedev.com</strong><br>
                        Contraseña inicial:
                        <strong>Admin123*</strong>
                    </div>
                </div>
            </section>
        </div>
    </main>
</body>
</html>
