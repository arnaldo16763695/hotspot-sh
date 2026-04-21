<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Acceso activado</title>
    <style>
        body {
            margin: 0;
            min-height: 100vh;
            display: grid;
            place-items: center;
            font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(180deg, #ecfff3, #f5f0e8);
            color: #1d2a22;
        }

        .card {
            width: min(560px, calc(100% - 32px));
            padding: 32px;
            border-radius: 24px;
            background: rgba(255, 255, 255, 0.94);
            border: 1px solid #cfe5d7;
            box-shadow: 0 24px 60px rgba(40, 83, 56, 0.12);
        }

        h1 {
            margin: 0 0 12px;
            font-size: clamp(2rem, 5vw, 3rem);
        }

        p {
            margin: 0 0 12px;
            line-height: 1.6;
            color: #4b5b51;
        }

        .highlight {
            margin-top: 18px;
            padding: 16px;
            border-radius: 16px;
            background: #effaf2;
            color: #226643;
            font-weight: 700;
        }

        .login-box {
            margin-top: 18px;
            padding: 16px;
            border-radius: 16px;
            background: #f4f8ff;
            color: #21406b;
        }

        a {
            display: inline-block;
            margin-top: 18px;
            color: #0d5f39;
            text-decoration: none;
            font-weight: 700;
        }
    </style>
</head>
<body>
    <main class="card">
        <h1>Acceso activado</h1>
        <p><?= esc($nombre) ?>, tu navegacion gratuita fue habilitada correctamente.</p>
        <?php if (! empty($branch_name)): ?>
            <p>Sucursal detectada: <strong><?= esc($branch_name) ?></strong><?php if (! empty($router_name)): ?> mediante el router <strong><?= esc($router_name) ?></strong><?php endif; ?>.</p>
        <?php endif; ?>
        <p>Esta primera version del portal ya registra la sesion y deja lista la trazabilidad para integrar la autorizacion real con MikroTik.</p>
        <div class="highlight">
            Tu acceso estara vigente hasta: <?= esc(date('d/m/Y h:i A', strtotime((string) $expires_at))) ?>
        </div>
        <?php if (! empty($login_url) && ! empty($login_username) && ! empty($login_password)): ?>
            <div class="login-box">
                Estamos completando el acceso con el hotspot. Si no avanza automaticamente, usa el boton de abajo.
            </div>
            <form id="mikrotik-login-form" method="post" action="<?= esc($login_url) ?>">
                <input type="hidden" name="username" value="<?= esc($login_username) ?>">
                <input type="hidden" name="password" value="<?= esc($login_password) ?>">
                <?php if (! empty($link_orig)): ?>
                    <input type="hidden" name="dst" value="<?= esc($link_orig) ?>">
                <?php endif; ?>
                <a href="#" onclick="document.getElementById('mikrotik-login-form').submit(); return false;">Continuar al hotspot</a>
            </form>
            <script>
                window.addEventListener('load', function () {
                    const form = document.getElementById('mikrotik-login-form');
                    if (form) {
                        setTimeout(function () {
                            form.submit();
                        }, 1200);
                    }
                });
            </script>
        <?php else: ?>
        <a href="<?= site_url('/') ?>">Volver al portal</a>
        <?php endif; ?>
    </main>
</body>
</html>
