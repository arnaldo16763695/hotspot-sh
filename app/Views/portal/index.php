<?= $this->extend('layouts/portal') ?>

<?= $this->section('title') ?>Acceso WiFi<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="row g-4 align-items-stretch">
            <section class="col-12 col-lg-7">
                <div class="hotspot-card h-100">
                    <span class="hotspot-eyebrow">Hotspot WiFi gratuito</span>
                    <h1 class="hotspot-title mt-4 mb-3 fw-bold">Conéctate en pocos pasos.</h1>
                    <p class="hotspot-copy fs-5 mb-0">Ingresa tu celular para validar si ya puedes acceder. Si aún no estás registrado, te pediremos tus datos para activar tu navegación gratuita.</p>

                    <?php if (! empty($branchInfo['branch_name'])): ?>
                        <div class="alert hotspot-status mt-4 mb-0">
                            Estás navegando desde: <strong><?= esc($branchInfo['branch_name']) ?></strong>
                            <?php if (! empty($branchInfo['router_name'])): ?>
                                <br>Hotspot asignado: <?= esc($branchInfo['router_name']) ?>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>

                    <?php if (! empty($status)): ?>
                        <div class="alert hotspot-status mt-3 mb-0"><?= esc($status) ?></div>
                    <?php endif; ?>

                    <?php if (! empty($waitUntil)): ?>
                        <div class="hotspot-wait mt-3 p-3">
                            Podrás volver a conectarte después de:
                            <strong><?= esc(date('d/m/Y h:i A', strtotime((string) $waitUntil))) ?></strong>
                        </div>
                    <?php endif; ?>

                    <?php if ($mode === 'register'): ?>
                        <div class="mt-4">
                            <h2 class="h4 fw-bold mb-3">Completa tu registro</h2>
                            <form method="post" action="<?= site_url('hotspot/register') ?>" novalidate>
                                <?= csrf_field() ?>

                                <div class="row g-3">
                                    <div class="col-12">
                                        <label class="form-label fw-semibold" for="nombre">Nombre completo</label>
                                        <input id="nombre" name="nombre" type="text" class="form-control hotspot-input<?= ! empty($errors['nombre']) ? ' is-invalid' : '' ?>" value="<?= esc($old['nombre'] ?? '') ?>" required>
                                        <?php if (! empty($errors['nombre'])): ?><div class="invalid-feedback d-block"><?= esc($errors['nombre']) ?></div><?php endif; ?>
                                    </div>

                                    <div class="col-12 col-md-6">
                                        <label class="form-label fw-semibold" for="celular">Celular</label>
                                        <input id="celular" name="celular" type="text" class="form-control hotspot-input<?= ! empty($errors['celular']) ? ' is-invalid' : '' ?>" value="<?= esc($old['celular'] ?? ($registrationContext['celular'] ?? '')) ?>" required>
                                        <?php if (! empty($errors['celular'])): ?><div class="invalid-feedback d-block"><?= esc($errors['celular']) ?></div><?php endif; ?>
                                    </div>

                                    <div class="col-12 col-md-6">
                                        <label class="form-label fw-semibold" for="correo">Correo electrónico</label>
                                        <input id="correo" name="correo" type="email" class="form-control hotspot-input<?= ! empty($errors['correo']) ? ' is-invalid' : '' ?>" value="<?= esc($old['correo'] ?? '') ?>" required>
                                        <?php if (! empty($errors['correo'])): ?><div class="invalid-feedback d-block"><?= esc($errors['correo']) ?></div><?php endif; ?>
                                    </div>

                                    <div class="col-12 col-md-6">
                                        <label class="form-label fw-semibold" for="fecha_nacimiento">Fecha de nacimiento</label>
                                        <input id="fecha_nacimiento" name="fecha_nacimiento" type="date" class="form-control hotspot-input<?= ! empty($errors['fecha_nacimiento']) ? ' is-invalid' : '' ?>" value="<?= esc($old['fecha_nacimiento'] ?? '') ?>" required>
                                        <?php if (! empty($errors['fecha_nacimiento'])): ?><div class="invalid-feedback d-block"><?= esc($errors['fecha_nacimiento']) ?></div><?php endif; ?>
                                    </div>
                                </div>

                                <input type="hidden" name="mac_address" value="<?= esc($registrationContext['mac_address'] ?? $old['mac_address'] ?? '') ?>">
                                <input type="hidden" name="ip_address" value="<?= esc($registrationContext['ip_address'] ?? $old['ip_address'] ?? '') ?>">
                                <input type="hidden" name="hotspot_nombre" value="<?= esc($registrationContext['hotspot_nombre'] ?? $old['hotspot_nombre'] ?? '') ?>">
                                <input type="hidden" name="router_code" value="<?= esc($registrationContext['router_code'] ?? $old['router_code'] ?? '') ?>">
                                <input type="hidden" name="link_login_only" value="<?= esc($registrationContext['link_login_only'] ?? $old['link_login_only'] ?? '') ?>">
                                <input type="hidden" name="link_orig" value="<?= esc($registrationContext['link_orig'] ?? $old['link_orig'] ?? '') ?>">

                                <div class="hotspot-check mt-4 p-3<?= ! empty($errors['acepta_terminos']) ? ' is-invalid' : '' ?>">
                                    <div class="form-check m-0">
                                        <input id="acepta_terminos" name="acepta_terminos" type="checkbox" class="form-check-input" value="1" required <?= ! empty($old['acepta_terminos']) ? 'checked' : '' ?>>
                                        <label class="form-check-label fw-semibold" for="acepta_terminos">Acepto los términos de uso y la política de privacidad.</label>
                                    </div>
                                </div>
                                <?php if (! empty($errors['acepta_terminos'])): ?><div class="invalid-feedback d-block"><?= esc($errors['acepta_terminos']) ?></div><?php endif; ?>

                                <div class="hotspot-check mt-3 p-3">
                                    <div class="form-check m-0">
                                        <input id="acepta_promociones" name="acepta_promociones" type="checkbox" class="form-check-input" value="1" <?= ! empty($old['acepta_promociones']) ? 'checked' : '' ?>>
                                        <label class="form-check-label" for="acepta_promociones">Deseo recibir promociones, novedades y beneficios por correo o SMS.</label>
                                    </div>
                                </div>

                                <div class="d-flex flex-wrap gap-2 mt-4">
                                    <button type="submit" class="btn hotspot-btn text-white">Activar acceso gratuito</button>
                                    <a class="btn hotspot-outline-btn" href="<?= site_url('hotspot') ?>">Volver</a>
                                </div>
                            </form>
                        </div>
                    <?php else: ?>
                        <div class="mt-4">
                            <h2 class="h4 fw-bold mb-3">Ingresa tu celular</h2>
                            <form method="post" action="<?= site_url('hotspot/identify') ?>">
                                <?= csrf_field() ?>

                                <div class="mb-3">
                                    <label class="form-label fw-semibold" for="celular">Número de celular</label>
                                    <input id="celular" name="celular" type="text" class="form-control hotspot-input<?= ! empty($errors['celular']) ? ' is-invalid' : '' ?>" value="<?= esc($old['celular'] ?? '') ?>" placeholder="Ejemplo: 04121234567" required>
                                    <?php if (! empty($errors['celular'])): ?><div class="invalid-feedback d-block"><?= esc($errors['celular']) ?></div><?php endif; ?>
                                </div>

                                <input type="hidden" name="mac_address" value="<?= esc($old['mac_address'] ?? ($_GET['mac'] ?? '')) ?>">
                                <input type="hidden" name="ip_address" value="<?= esc($old['ip_address'] ?? ($_GET['ip'] ?? $_GET['ip-address'] ?? '')) ?>">
                                <input type="hidden" name="hotspot_nombre" value="<?= esc($old['hotspot_nombre'] ?? ($_GET['hotspot'] ?? '')) ?>">
                                <input type="hidden" name="router_code" value="<?= esc($old['router_code'] ?? ($_GET['router'] ?? '')) ?>">
                                <input type="hidden" name="link_login_only" value="<?= esc($old['link_login_only'] ?? ($_GET['link_login_only'] ?? '')) ?>">
                                <input type="hidden" name="link_orig" value="<?= esc($old['link_orig'] ?? ($_GET['link_orig'] ?? '')) ?>">

                                <button type="submit" class="btn hotspot-btn text-white mt-2">Continuar</button>
                            </form>
                        </div>
                    <?php endif; ?>
                </div>
            </section>

            <aside class="col-12 col-lg-5">
                <div class="hotspot-side-card h-100">
                    <div>
                        <h2 class="h4 fw-bold mb-3">Como funciona</h2>
                        <div class="d-grid gap-3">
                            <div class="hotspot-side-item">
                                <strong class="d-block mb-1">1. Validación rápida</strong>
                                <p class="mb-0">Primero verificamos tu celular y el router del hotspot para saber si ya puedes entrar o si necesitas registrarte.</p>
                            </div>
                            <div class="hotspot-side-item">
                                <strong class="d-block mb-1">2. Registro solo una vez</strong>
                                <p class="mb-0">Si es tu primer acceso, te pediremos nombre, correo y fecha de nacimiento.</p>
                            </div>
                            <div class="hotspot-side-item">
                                <strong class="d-block mb-1">3. Acceso por sucursal</strong>
                                <p class="mb-0">Cada sesión queda ligada a la sucursal y al MikroTik que corresponden a ese punto de acceso.</p>
                            </div>
                        </div>
                    </div>

                    <div class="small text-secondary mt-4 d-grid gap-2">
                        <span>Ventana entre accesos: 3 horas</span>
                        <span>Identificación pública mediante router_code</span>
                        <span>Portal preparado para campañas futuras por correo y SMS</span>
                    </div>
                </div>
            </aside>
        </div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
        (function () {
            const registerForm = document.querySelector('form[action$="hotspot/register"]');

            if (!registerForm) {
                return;
            }

            const termsCheckbox = registerForm.querySelector('#acepta_terminos');
            const termsWrapper = registerForm.querySelector('.hotspot-check');
            const phoneInput = registerForm.querySelector('#celular');

            if (phoneInput) {
                phoneInput.value = phoneInput.value.trim();
            }

            if (termsCheckbox && termsWrapper) {
                const syncTermsState = function () {
                    if (termsCheckbox.checked) {
                        termsCheckbox.setCustomValidity('');
                        termsWrapper.classList.remove('is-invalid');
                    } else {
                        termsCheckbox.setCustomValidity('Debes aceptar los términos y la política de privacidad para continuar.');
                        termsWrapper.classList.add('is-invalid');
                    }
                };

                syncTermsState();
                termsCheckbox.addEventListener('change', syncTermsState);

                registerForm.addEventListener('submit', function () {
                    syncTermsState();
                });
            }
        })();
    </script>
<?= $this->endSection() ?>
