<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Acceso WiFi</title>
    <style>
        :root {
            color-scheme: light;
            --bg-top: #fff7ed;
            --bg-bottom: #f4f1ea;
            --card: #fffefb;
            --ink: #2b2118;
            --muted: #726252;
            --accent: #bf5b2c;
            --accent-dark: #8f3f1a;
            --line: #ead9c8;
            --warning: #9a5d00;
            --danger: #b42318;
        }

        * { box-sizing: border-box; }
        body {
            margin: 0;
            min-height: 100vh;
            font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
            color: var(--ink);
            background:
                radial-gradient(circle at top left, rgba(191, 91, 44, 0.18), transparent 35%),
                linear-gradient(180deg, var(--bg-top), var(--bg-bottom));
        }
        .shell {
            width: min(960px, calc(100% - 32px));
            margin: 0 auto;
            padding: 28px 0 44px;
        }
        .hero {
            display: grid;
            grid-template-columns: 1.1fr 0.9fr;
            gap: 24px;
            align-items: stretch;
        }
        .panel, .side-card {
            background: rgba(255, 254, 251, 0.92);
            border: 1px solid var(--line);
            border-radius: 24px;
            box-shadow: 0 24px 60px rgba(73, 48, 28, 0.12);
        }
        .panel { padding: 28px; }
        .side-card {
            padding: 24px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }
        .eyebrow {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 8px 12px;
            border-radius: 999px;
            background: #fff1e8;
            color: var(--accent-dark);
            font-size: 0.9rem;
            font-weight: 700;
            letter-spacing: 0.02em;
        }
        h1 {
            margin: 18px 0 12px;
            font-size: clamp(2rem, 5vw, 3.5rem);
            line-height: 1;
        }
        p {
            margin: 0;
            color: var(--muted);
            line-height: 1.55;
        }
        .section-title {
            margin: 24px 0 8px;
            font-size: 1.2rem;
            font-weight: 700;
        }
        .notice {
            margin-top: 20px;
            padding: 14px 16px;
            border-radius: 16px;
            font-size: 0.95rem;
            border: 1px solid var(--line);
            background: #fffaf4;
        }
        .notice.info { color: var(--accent-dark); }
        .wait-box {
            margin-top: 14px;
            padding: 14px 16px;
            border-radius: 16px;
            background: #fff6e6;
            color: var(--warning);
            border: 1px solid #f0d4a5;
        }
        form { margin-top: 24px; }
        .grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 16px;
        }
        .field {
            display: flex;
            flex-direction: column;
            gap: 8px;
            margin-bottom: 16px;
        }
        .field.full { grid-column: 1 / -1; }
        label {
            font-size: 0.95rem;
            font-weight: 700;
        }
        input[type="text"], input[type="email"], input[type="date"] {
            width: 100%;
            border: 1px solid #d9c3b0;
            border-radius: 14px;
            background: #fff;
            padding: 14px 16px;
            font: inherit;
            color: var(--ink);
        }
        input:focus {
            outline: 2px solid rgba(191, 91, 44, 0.25);
            border-color: var(--accent);
        }
        .error-text {
            color: var(--danger);
            font-size: 0.88rem;
        }
        .checkbox {
            display: flex;
            gap: 10px;
            align-items: flex-start;
            padding: 12px 14px;
            background: #fffbf7;
            border: 1px solid var(--line);
            border-radius: 14px;
        }
        .checkbox input { margin-top: 4px; }
        .checkbox.error {
            border-color: #e8a6a0;
            background: #fff3f2;
        }
        .actions {
            display: flex;
            gap: 12px;
            flex-wrap: wrap;
            margin-top: 12px;
        }
        button, .ghost-link {
            border: 0;
            border-radius: 999px;
            padding: 14px 20px;
            font: inherit;
            font-weight: 700;
            text-decoration: none;
            transition: transform 180ms ease, box-shadow 180ms ease, background-color 180ms ease;
        }
        button {
            background: var(--accent);
            color: #fff;
            cursor: pointer;
            box-shadow: 0 14px 30px rgba(191, 91, 44, 0.25);
        }
        button:hover, .ghost-link:hover { transform: translateY(-1px); }
        .ghost-link {
            color: var(--accent-dark);
            background: #fff2e9;
        }
        .detail-list {
            display: grid;
            gap: 14px;
            margin-top: 20px;
        }
        .detail-item {
            padding-bottom: 14px;
            border-bottom: 1px dashed var(--line);
        }
        .detail-item strong {
            display: block;
            margin-bottom: 4px;
            font-size: 0.95rem;
        }
        .mini-meta {
            margin-top: 20px;
            display: grid;
            gap: 10px;
            font-size: 0.9rem;
            color: var(--muted);
        }
        @media (max-width: 820px) {
            .hero { grid-template-columns: 1fr; }
            .grid { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>
    <main class="shell">
        <section class="hero">
            <div class="panel">
                <span class="eyebrow">Hotspot WiFi gratuito</span>
                <h1>Conectate en pocos pasos.</h1>
                <p>Ingresa tu celular para validar si ya puedes acceder. Si aun no estas registrado, te pediremos tus datos para activar tu navegacion gratuita.</p>

                <?php if (! empty($branchInfo['branch_name'])): ?>
                    <div class="notice info">
                        Estas navegando desde: <strong><?= esc($branchInfo['branch_name']) ?></strong>
                        <?php if (! empty($branchInfo['router_name'])): ?>
                            <br>Hotspot asignado: <?= esc($branchInfo['router_name']) ?>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>

                <?php if (! empty($status)): ?>
                    <div class="notice info"><?= esc($status) ?></div>
                <?php endif; ?>

                <?php if (! empty($waitUntil)): ?>
                    <div class="wait-box">
                        Podras volver a conectarte despues de:
                        <strong><?= esc(date('d/m/Y h:i A', strtotime((string) $waitUntil))) ?></strong>
                    </div>
                <?php endif; ?>

                <?php if ($mode === 'register'): ?>
                    <div class="section-title">Completa tu registro</div>
                    <form method="post" action="<?= site_url('hotspot/register') ?>">
                        <?= csrf_field() ?>

                        <div class="grid">
                            <div class="field full">
                                <label for="nombre">Nombre completo</label>
                                <input id="nombre" name="nombre" type="text" value="<?= esc($old['nombre'] ?? '') ?>" required>
                                <?php if (! empty($errors['nombre'])): ?><span class="error-text"><?= esc($errors['nombre']) ?></span><?php endif; ?>
                            </div>

                            <div class="field">
                                <label for="celular">Celular</label>
                                <input id="celular" name="celular" type="text" value="<?= esc($old['celular'] ?? ($registrationContext['celular'] ?? '')) ?>" required>
                                <?php if (! empty($errors['celular'])): ?><span class="error-text"><?= esc($errors['celular']) ?></span><?php endif; ?>
                            </div>

                            <div class="field">
                                <label for="correo">Correo electronico</label>
                                <input id="correo" name="correo" type="email" value="<?= esc($old['correo'] ?? '') ?>" required>
                                <?php if (! empty($errors['correo'])): ?><span class="error-text"><?= esc($errors['correo']) ?></span><?php endif; ?>
                            </div>

                            <div class="field">
                                <label for="fecha_nacimiento">Fecha de nacimiento</label>
                                <input id="fecha_nacimiento" name="fecha_nacimiento" type="date" value="<?= esc($old['fecha_nacimiento'] ?? '') ?>" required>
                                <?php if (! empty($errors['fecha_nacimiento'])): ?><span class="error-text"><?= esc($errors['fecha_nacimiento']) ?></span><?php endif; ?>
                            </div>
                        </div>

                        <input type="hidden" name="mac_address" value="<?= esc($registrationContext['mac_address'] ?? $old['mac_address'] ?? '') ?>">
                        <input type="hidden" name="ip_address" value="<?= esc($registrationContext['ip_address'] ?? $old['ip_address'] ?? '') ?>">
                        <input type="hidden" name="hotspot_nombre" value="<?= esc($registrationContext['hotspot_nombre'] ?? $old['hotspot_nombre'] ?? '') ?>">
                        <input type="hidden" name="router_code" value="<?= esc($registrationContext['router_code'] ?? $old['router_code'] ?? '') ?>">
                        <input type="hidden" name="link_login_only" value="<?= esc($registrationContext['link_login_only'] ?? $old['link_login_only'] ?? '') ?>">
                        <input type="hidden" name="link_orig" value="<?= esc($registrationContext['link_orig'] ?? $old['link_orig'] ?? '') ?>">

                        <div class="field">
                            <label class="checkbox <?= ! empty($errors['acepta_terminos']) ? 'error' : '' ?>" for="acepta_terminos">
                                <input id="acepta_terminos" name="acepta_terminos" type="checkbox" value="1" required <?= ! empty($old['acepta_terminos']) ? 'checked' : '' ?>>
                                <span>Acepto los terminos de uso y la politica de privacidad.</span>
                            </label>
                            <?php if (! empty($errors['acepta_terminos'])): ?><span class="error-text"><?= esc($errors['acepta_terminos']) ?></span><?php endif; ?>
                        </div>

                        <div class="field">
                            <label class="checkbox" for="acepta_promociones">
                                <input id="acepta_promociones" name="acepta_promociones" type="checkbox" value="1" <?= ! empty($old['acepta_promociones']) ? 'checked' : '' ?>>
                                <span>Deseo recibir promociones, novedades y beneficios por correo o SMS.</span>
                            </label>
                        </div>

                        <div class="actions">
                            <button type="submit">Activar acceso gratuito</button>
                            <a class="ghost-link" href="<?= site_url('hotspot') ?>">Volver</a>
                        </div>
                    </form>
                <?php else: ?>
                    <div class="section-title">Ingresa tu celular</div>
                    <form method="post" action="<?= site_url('hotspot/identify') ?>">
                        <?= csrf_field() ?>

                        <div class="field">
                            <label for="celular">Numero de celular</label>
                            <input id="celular" name="celular" type="text" value="<?= esc($old['celular'] ?? '') ?>" placeholder="Ejemplo: 04121234567" required>
                            <?php if (! empty($errors['celular'])): ?><span class="error-text"><?= esc($errors['celular']) ?></span><?php endif; ?>
                        </div>

                        <input type="hidden" name="mac_address" value="<?= esc($old['mac_address'] ?? ($_GET['mac'] ?? '')) ?>">
                        <input type="hidden" name="ip_address" value="<?= esc($old['ip_address'] ?? '') ?>">
                        <input type="hidden" name="hotspot_nombre" value="<?= esc($old['hotspot_nombre'] ?? ($_GET['hotspot'] ?? '')) ?>">
                        <input type="hidden" name="router_code" value="<?= esc($old['router_code'] ?? ($_GET['router'] ?? '')) ?>">
                        <input type="hidden" name="link_login_only" value="<?= esc($old['link_login_only'] ?? ($_GET['link_login_only'] ?? '')) ?>">
                        <input type="hidden" name="link_orig" value="<?= esc($old['link_orig'] ?? ($_GET['link_orig'] ?? '')) ?>">

                        <div class="actions">
                            <button type="submit">Continuar</button>
                        </div>
                    </form>
                <?php endif; ?>
            </div>

            <aside class="side-card">
                <div>
                    <div class="section-title">Como funciona</div>
                    <div class="detail-list">
                        <div class="detail-item">
                            <strong>1. Validacion rapida</strong>
                            <p>Primero verificamos tu celular y el router del hotspot para saber si ya puedes entrar o si necesitas registrarte.</p>
                        </div>
                        <div class="detail-item">
                            <strong>2. Registro solo una vez</strong>
                            <p>Si es tu primer acceso, te pediremos nombre, correo y fecha de nacimiento.</p>
                        </div>
                        <div class="detail-item">
                            <strong>3. Acceso por sucursal</strong>
                            <p>Cada sesion queda ligada a la sucursal y al MikroTik que corresponden a ese punto de acceso.</p>
                        </div>
                    </div>
                </div>

                <div class="mini-meta">
                    <span>Ventana entre accesos: 3 horas</span>
                    <span>Identificacion publica mediante router_code</span>
                    <span>Portal preparado para campanas futuras por correo y SMS</span>
                </div>
            </aside>
        </section>
    </main>
    <script>
        (function () {
            const registerForm = document.querySelector('form[action$="hotspot/register"]');

            if (!registerForm) {
                return;
            }

            const termsCheckbox = registerForm.querySelector('#acepta_terminos');
            const termsWrapper = registerForm.querySelector('label[for="acepta_terminos"]');
            const phoneInput = registerForm.querySelector('#celular');

            if (phoneInput) {
                phoneInput.value = phoneInput.value.trim();
            }

            if (termsCheckbox && termsWrapper) {
                const syncTermsState = function () {
                    if (termsCheckbox.checked) {
                        termsCheckbox.setCustomValidity('');
                        termsWrapper.classList.remove('error');
                    } else {
                        termsCheckbox.setCustomValidity('Debes aceptar los terminos y la politica de privacidad para continuar.');
                        termsWrapper.classList.add('error');
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
</body>
</html>
