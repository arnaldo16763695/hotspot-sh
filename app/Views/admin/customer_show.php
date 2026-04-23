<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalle de cliente</title>
    <link rel="stylesheet" href="<?= base_url('assets/bootstrap/bootstrap-5.3.8-dist/css/bootstrap.min.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/admin.css') ?>">
</head>
<body class="admin-body py-4">
    <main class="container-fluid px-3 px-lg-4">
        <div class="mx-auto admin-shell">
            <?= view('admin/partials/navbar', ['auth' => $auth, 'currentPage' => $currentPage ?? 'customers']) ?>

            <div class="admin-card p-4 p-lg-5">
                <div class="d-flex flex-column flex-lg-row justify-content-between gap-3 align-items-lg-center">
                    <div>
                        <span class="admin-badge">Detalle de cliente</span>
                        <h1 class="admin-title mt-4 mb-2 fw-bold"><?= esc($cliente['nombre']) ?>.</h1>
                        <p class="admin-copy mb-0">Revisa datos de perfil, consentimientos, historial de sesiones y eventos registrados por el portal cautivo.</p>
                    </div>
                    <a class="btn admin-secondary-btn" href="<?= site_url('admin/customers') ?>">Volver a clientes</a>
                </div>

                <?php if (! empty($status)): ?>
                    <div class="alert alert-info mt-4 mb-0"><?= esc($status) ?></div>
                <?php endif; ?>

                <div class="row g-4 mt-1">
                    <div class="col-12 col-xl-4">
                        <div class="admin-placeholder h-100 p-4">
                            <h2 class="h5 mb-3">Perfil</h2>
                            <div class="admin-item">
                                <strong>Celular</strong>
                                <div><?= esc($cliente['celular']) ?></div>
                            </div>
                            <div class="admin-item">
                                <strong>Correo</strong>
                                <div><?= esc($cliente['correo']) ?></div>
                            </div>
                            <div class="admin-item">
                                <strong>Fecha de nacimiento</strong>
                                <div><?= ! empty($cliente['fecha_nacimiento']) ? esc(date('d/m/Y', strtotime((string) $cliente['fecha_nacimiento']))) : 'Sin registro' ?></div>
                            </div>
                            <div class="admin-item">
                                <strong>Estado</strong>
                                <div><span class="badge text-bg-light border"><?= esc(ucfirst((string) $cliente['estado'])) ?></span></div>
                            </div>
                            <div class="admin-item">
                                <strong>Acepta términos</strong>
                                <div><?= (int) ($cliente['acepta_terminos'] ?? 0) === 1 ? 'Sí' : 'No' ?></div>
                            </div>
                            <div class="admin-item">
                                <strong>Acepta promociones</strong>
                                <div><?= (int) ($cliente['acepta_promociones'] ?? 0) === 1 ? 'Sí' : 'No' ?></div>
                            </div>
                            <div class="admin-item">
                                <strong>Fecha de registro</strong>
                                <div><?= ! empty($cliente['fecha_registro']) ? esc(date('d/m/Y h:i A', strtotime((string) $cliente['fecha_registro']))) : 'Sin registro' ?></div>
                            </div>
                            <div class="admin-item">
                                <strong>Última sesión</strong>
                                <div><?= ! empty($cliente['ultima_sesion']) ? esc(date('d/m/Y h:i A', strtotime((string) $cliente['ultima_sesion']))) : 'Sin sesiones' ?></div>
                            </div>
                            <div>
                                <strong>Última sucursal</strong>
                                <div><?= esc($cliente['ultima_sucursal'] ?? 'Sin sucursal') ?></div>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 col-xl-8">
                        <div class="admin-placeholder p-4 mb-4">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h2 class="h5 mb-0">Sesiones recientes</h2>
                                <span class="text-body-secondary small"><?= esc((string) count($sesiones)) ?> registro(s)</span>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0">
                                    <thead>
                                        <tr>
                                            <th>Inicio</th>
                                            <th>Fin</th>
                                            <th>Sucursal</th>
                                            <th>MAC / IP</th>
                                            <th>Acceso</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if ($sesiones === []): ?>
                                            <tr>
                                                <td colspan="5" class="text-center text-body-secondary py-4">Este cliente aún no tiene sesiones registradas.</td>
                                            </tr>
                                        <?php endif; ?>
                                        <?php foreach ($sesiones as $sesion): ?>
                                            <tr>
                                                <td><?= ! empty($sesion['fecha_inicio']) ? esc(date('d/m/Y h:i A', strtotime((string) $sesion['fecha_inicio']))) : '-' ?></td>
                                                <td><?= ! empty($sesion['fecha_fin']) ? esc(date('d/m/Y h:i A', strtotime((string) $sesion['fecha_fin']))) : '-' ?></td>
                                                <td><?= esc($sesion['sucursal_nombre'] ?? ($sesion['branch_code'] ?? 'Sin sucursal')) ?></td>
                                                <td>
                                                    <?= esc($sesion['mac_address'] ?? '-') ?><br>
                                                    <span class="text-body-secondary small"><?= esc($sesion['ip_address'] ?? '-') ?></span>
                                                </td>
                                                <td>
                                                    <?php if ((int) ($sesion['autorizado'] ?? 0) === 1): ?>
                                                        <span class="badge text-bg-success">Autorizado</span>
                                                    <?php else: ?>
                                                        <span class="badge text-bg-danger">Rechazado</span>
                                                    <?php endif; ?>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="admin-placeholder p-4">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h2 class="h5 mb-0">Eventos de acceso</h2>
                                <span class="text-body-secondary small"><?= esc((string) count($eventos)) ?> evento(s)</span>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0">
                                    <thead>
                                        <tr>
                                            <th>Fecha</th>
                                            <th>Tipo</th>
                                            <th>Descripción</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if ($eventos === []): ?>
                                            <tr>
                                                <td colspan="3" class="text-center text-body-secondary py-4">No hay eventos registrados para este cliente.</td>
                                            </tr>
                                        <?php endif; ?>
                                        <?php foreach ($eventos as $evento): ?>
                                            <tr>
                                                <td><?= ! empty($evento['fecha_evento']) ? esc(date('d/m/Y h:i A', strtotime((string) $evento['fecha_evento']))) : '-' ?></td>
                                                <td><span class="badge text-bg-light border"><?= esc($evento['tipo_evento']) ?></span></td>
                                                <td class="text-body-secondary"><?= esc($evento['descripcion'] ?? '') ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
    <script src="<?= base_url('assets/bootstrap/bootstrap-5.3.8-dist/js/bootstrap.bundle.min.js') ?>"></script>
</body>
</html>
