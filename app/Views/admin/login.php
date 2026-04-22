<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Hotspot</title>
    <link rel="stylesheet" href="<?= base_url('assets/bootstrap/bootstrap-5.3.8-dist/css/bootstrap.min.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/admin.css') ?>">
</head>
<body class="admin-body d-flex align-items-center py-4">
    <main class="container admin-shell">
        <div class="row g-4">
            <section class="col-12 col-lg-7">
                <div class="admin-card h-100 p-4 p-lg-5">
                    <span class="admin-badge">Admin Hotspot</span>
                    <h1 class="admin-title mt-4 mb-3 fw-bold">Panel administrativo en preparacion.</h1>
                    <p class="admin-copy mb-0">La raiz del dominio ya queda reservada para el acceso administrativo. Aqui viviran el login, dashboard, campanas, clientes, sesiones y la configuracion de sucursales y routers.</p>

                    <div class="mt-4 d-grid gap-3">
                        <div class="admin-item">
                            <strong class="d-block mb-1">Portal cautivo separado</strong>
                            <p class="admin-copy mb-0">El acceso del hotspot ya no depende de la raiz del dominio.</p>
                        </div>
                        <div class="admin-item">
                            <strong class="d-block mb-1">Admin publico pero protegido</strong>
                            <p class="admin-copy mb-0">Mas adelante esta entrada tendra autenticacion de usuario y contrasena.</p>
                        </div>
                        <div class="admin-item">
                            <strong class="d-block mb-1">Siguiente evolucion</strong>
                            <p class="admin-copy mb-0">Dashboard, clientes, campanas, cumpleanos automaticos y control de sucursales.</p>
                        </div>
                    </div>
                </div>
            </section>

            <section class="col-12 col-lg-5">
                <div class="admin-card h-100 p-4 p-lg-5">
                    <h2 class="h3 mb-2">Acceso provisional</h2>
                    <p class="admin-copy mb-0">Esta pantalla deja lista la ruta del admin mientras seguimos desarrollando el modulo privado.</p>

                    <div class="admin-placeholder mt-4 p-3">
                        Proximo paso previsto: implementar autenticacion para administradores y layout base del dashboard.
                    </div>

                    <div class="d-flex flex-wrap gap-2 mt-4">
                        <a class="btn admin-primary-btn text-white" href="<?= site_url('hotspot') ?>">Ir al portal hotspot</a>
                        <a class="btn admin-secondary-btn" href="<?= site_url('hotspot?router=CCS01-HS1') ?>">Probar hotspot demo</a>
                    </div>
                </div>
            </section>
        </div>
    </main>
</body>
</html>
