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
        <p>El acceso ya fue autorizado directamente en el MikroTik y la sesion quedo registrada en el sistema.</p>
        <div class="highlight">
            Tu acceso estara vigente hasta: <?= esc(date('d/m/Y h:i A', strtotime((string) $expires_at))) ?>
        </div>
        <a href="<?= site_url('/') ?>">Volver al portal</a>
    </main>
</body>
</html>
