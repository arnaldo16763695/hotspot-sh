<?= $this->extend('layouts/admin') ?>

<?= $this->section('title') ?>Dashboard Admin<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="admin-card p-4 p-lg-5">
            <div class="d-flex flex-column flex-lg-row justify-content-between gap-3 align-items-lg-center">
                <div>
                    <span class="admin-badge">Dashboard Admin</span>
                    <h1 class="admin-title mt-4 mb-2 fw-bold">Bienvenido, <?= esc($auth['nombre'] ?? 'Administrador') ?>.</h1>
                    <p class="admin-copy mb-0">Tu sesión ya está protegida y lista para crecer con módulos de clientes, sesiones, campañas y configuración operativa.</p>
                </div>
            </div>

            <?php if (! empty($status)): ?>
                <div class="alert alert-info mt-4 mb-0"><?= esc($status) ?></div>
            <?php endif; ?>

            <div class="row g-4 mt-1">
                <div class="col-12 col-lg-4">
                    <div class="admin-placeholder h-100 p-4">
                        <h2 class="h5 mb-2">Rol actual</h2>
                        <p class="mb-0"><?= esc($auth['role_name'] ?? 'Sin rol') ?> <span class="text-body-secondary">(<?= esc($auth['role_code'] ?? '-') ?>)</span></p>
                    </div>
                </div>
                <div class="col-12 col-lg-4">
                    <div class="admin-placeholder h-100 p-4">
                        <h2 class="h5 mb-2">Próximo módulo</h2>
                        <p class="mb-0">Administración de sucursales, routers y monitoreo operativo del hotspot.</p>
                    </div>
                </div>
                <div class="col-12 col-lg-4">
                    <div class="admin-placeholder h-100 p-4">
                        <h2 class="h5 mb-2">Seguridad</h2>
                        <p class="mb-0">Acceso protegido por sesión y preparado para restricciones por rol.</p>
                    </div>
                </div>
            </div>
        </div>
<?= $this->endSection() ?>
