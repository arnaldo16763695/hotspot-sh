<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Hotspot</title>
    <style>
        :root {
            --bg: #f5f4ef;
            --card: rgba(255,255,255,0.94);
            --ink: #1f2937;
            --muted: #6b7280;
            --accent: #0f766e;
            --accent-soft: #dff6f1;
            --line: #d9e5e2;
        }

        * { box-sizing: border-box; }

        body {
            margin: 0;
            min-height: 100vh;
            display: grid;
            place-items: center;
            padding: 24px;
            font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
            color: var(--ink);
            background:
                radial-gradient(circle at top right, rgba(15, 118, 110, 0.14), transparent 30%),
                linear-gradient(180deg, #f8faf9, var(--bg));
        }

        .layout {
            width: min(980px, 100%);
            display: grid;
            grid-template-columns: 1.05fr 0.95fr;
            gap: 24px;
        }

        .hero,
        .card {
            background: var(--card);
            border: 1px solid var(--line);
            border-radius: 28px;
            box-shadow: 0 24px 60px rgba(15, 23, 42, 0.08);
        }

        .hero {
            padding: 32px;
        }

        .badge {
            display: inline-block;
            padding: 8px 12px;
            border-radius: 999px;
            background: var(--accent-soft);
            color: var(--accent);
            font-weight: 700;
            font-size: 0.9rem;
        }

        h1 {
            margin: 18px 0 12px;
            font-size: clamp(2.2rem, 5vw, 3.8rem);
            line-height: 1;
        }

        p {
            margin: 0;
            color: var(--muted);
            line-height: 1.6;
        }

        .list {
            margin-top: 26px;
            display: grid;
            gap: 14px;
        }

        .item {
            padding: 14px 0;
            border-bottom: 1px dashed var(--line);
        }

        .item strong {
            display: block;
            margin-bottom: 4px;
        }

        .card {
            padding: 32px;
        }

        .card h2 {
            margin: 0 0 8px;
            font-size: 1.5rem;
        }

        .placeholder {
            margin-top: 18px;
            padding: 16px;
            border-radius: 18px;
            background: #f8fafc;
            color: var(--muted);
        }

        .actions {
            margin-top: 22px;
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
        }

        .link {
            display: inline-block;
            padding: 12px 16px;
            border-radius: 999px;
            text-decoration: none;
            font-weight: 700;
        }

        .primary {
            background: var(--accent);
            color: white;
        }

        .secondary {
            background: #eef7f5;
            color: var(--accent);
        }

        @media (max-width: 820px) {
            .layout {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <main class="layout">
        <section class="hero">
            <span class="badge">Admin Hotspot</span>
            <h1>Panel administrativo en preparación.</h1>
            <p>La raíz del dominio ya queda reservada para el acceso administrativo. Aquí vivirán el login, dashboard, campañas, clientes, sesiones y la configuración de sucursales y routers.</p>

            <div class="list">
                <div class="item">
                    <strong>Portal cautivo separado</strong>
                    <p>El acceso del hotspot ya no depende de la raíz del dominio.</p>
                </div>
                <div class="item">
                    <strong>Admin público pero protegido</strong>
                    <p>Más adelante esta entrada tendrá autenticación de usuario y contraseña.</p>
                </div>
                <div class="item">
                    <strong>Siguiente evolución</strong>
                    <p>Dashboard, clientes, campañas, cumpleaños automáticos y control de sucursales.</p>
                </div>
            </div>
        </section>

        <section class="card">
            <h2>Acceso provisional</h2>
            <p>Esta pantalla deja lista la ruta del admin mientras seguimos desarrollando el módulo privado.</p>

            <div class="placeholder">
                Próximo paso previsto: implementar autenticación para administradores y layout base del dashboard.
            </div>

            <div class="actions">
                <a class="link primary" href="<?= site_url('hotspot') ?>">Ir al portal hotspot</a>
                <a class="link secondary" href="<?= site_url('hotspot?router=CCS01-HS1') ?>">Probar hotspot demo</a>
            </div>
        </section>
    </main>
</body>
</html>
