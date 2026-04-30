<?= $this->extend('layouts/portal_centered') ?>

<?= $this->section('title') ?>Acceso activado<?= $this->endSection() ?>

<?= $this->section('content') ?>
<main class="hotspot-success-card">
        <h1 class="display-6 fw-bold mb-3">Acceso activado</h1>
        <p class="mb-3"><?= esc($nombre) ?>, tu navegación gratuita fue habilitada correctamente.</p>
        <?php if (! empty($branch_name)): ?>
            <p class="mb-3">Sucursal detectada: <strong><?= esc($branch_name) ?></strong><?php if (! empty($router_name)): ?> mediante el router <strong><?= esc($router_name) ?></strong><?php endif; ?>.</p>
        <?php endif; ?>
        <p class="mb-0">El acceso ya fue autorizado directamente en el MikroTik y la sesión quedó registrada en el sistema.</p>
        <div class="hotspot-success-highlight mt-4 p-3 fw-semibold">
            Tu acceso estará vigente hasta: <?= esc(date('d/m/Y h:i A', strtotime((string) $expires_at))) ?>
        </div>
        <a class="btn hotspot-outline-btn mt-4" href="<?= site_url('hotspot') ?>">Volver al portal</a>
    </main>
<?= $this->endSection() ?>
