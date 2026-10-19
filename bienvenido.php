<?php
session_start();
if (!isset($_SESSION['nombre'])) {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Bienvenido — No Más Unos</title>
    <script src="assets/js/theme.js"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;700;800&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        :root {
            --purple:    #5B4BF5;
            --purple-lt: #EAE7FF;
            --coral:     #FF5C57;
            --ink:       #0D0D14;
            --bg:        #F6F5FF;
            --muted:     #6B6882;
            --border:    #E2DFFB;
            --surface:   #ffffff;
            color-scheme: light;
        }
        [data-theme="dark"] {
            --purple:    #8B7CFF;
            --purple-lt: #241F3D;
            --coral:     #FF6F68;
            --ink:       #F1F0FA;
            --bg:        #100E1C;
            --muted:     #A6A2C4;
            --border:    #322C52;
            --surface:   #1B1830;
            color-scheme: dark;
        }
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'Inter', sans-serif;
            background: var(--bg);
            min-height: 100vh;
            display: flex; align-items: center; justify-content: center;
            padding: 2rem;
            transition: background .2s ease;
        }
        body::before {
            content: '';
            position: fixed; top: -200px; right: -200px;
            width: 600px; height: 600px; border-radius: 50%;
            background: radial-gradient(circle, rgba(91,75,245,.14) 0%, transparent 70%);
            pointer-events: none;
        }
        .card {
            background: var(--surface);
            border-radius: 20px;
            border: 1px solid var(--border);
            padding: 2.75rem;
            text-align: center;
            width: 100%; max-width: 420px;
            box-shadow: 0 4px 24px rgba(91,75,245,.08);
            position: relative; z-index: 1;
        }
        .topline {
            display: flex; align-items: center; justify-content: space-between;
            margin-bottom: 1.5rem;
        }
        .logo {
            display: flex; align-items: center; gap: .5rem;
            font-family: 'Syne', sans-serif; font-weight: 800; font-size: 1rem;
            color: var(--ink);
            text-decoration: none;
        }
        .logo-dot {
            width: 10px; height: 10px; border-radius: 50%;
            background: var(--coral); flex-shrink: 0;
        }
        .theme-toggle {
            width: 38px; height: 38px; flex-shrink: 0;
            border-radius: 50%; border: 1.5px solid var(--border);
            background: var(--surface); color: var(--ink);
            display: inline-flex; align-items: center; justify-content: center;
            font-size: 16px; cursor: pointer; padding: 0;
            transition: transform .18s ease, border-color .18s ease;
        }
        .theme-toggle:hover { transform: translateY(-2px) rotate(8deg); border-color: var(--purple); }
        .avatar {
            width: 64px; height: 64px; border-radius: 50%;
            background: var(--purple-lt);
            display: flex; align-items: center; justify-content: center;
            font-size: 30px; margin: 0 auto 1.25rem;
        }
        h1 {
            font-family: 'Syne', sans-serif; font-weight: 800;
            font-size: 24px; color: var(--ink); margin-bottom: 8px;
        }
        p { font-size: 14.5px; color: var(--muted); margin-bottom: 1.75rem; line-height: 1.6; }
        p strong { color: var(--ink); }
        .botones { display: flex; flex-direction: column; gap: .65rem; }
        .btn {
            display: inline-flex; align-items: center; justify-content: center; gap: .4rem;
            padding: 12px 24px; border-radius: 99px;
            font-size: 14.5px; font-weight: 700;
            font-family: 'Syne', sans-serif;
            text-decoration: none; cursor: pointer; border: none;
            transition: transform .18s ease, box-shadow .18s ease, border-color .18s ease;
        }
        .btn--primary {
            background: var(--purple); color: #fff;
            box-shadow: 0 6px 20px rgba(91,75,245,.35);
        }
        .btn--primary:hover { transform: translateY(-2px); box-shadow: 0 10px 28px rgba(91,75,245,.45); }
        .btn--ghost {
            background: transparent; color: var(--muted);
            border: 1.5px solid var(--border);
        }
        .btn--ghost:hover { border-color: var(--coral); color: var(--coral); }
    </style>
</head>
<body>
<div class="card">
    <div class="topline">
        <a href="index.html" class="logo">
            <span class="logo-dot"></span> No Más Unos
        </a>
        <button type="button" class="theme-toggle" onclick="nmuToggleTheme()" aria-label="Cambiar tema claro/oscuro" aria-pressed="false" title="Cambiar tema">🌙</button>
    </div>
    <div class="avatar">👋</div>
    <h1>¡Hola, <?= htmlspecialchars($_SESSION['nombre']) ?>!</h1>
    <p>Iniciaste sesión correctamente con<br><strong><?= htmlspecialchars($_SESSION['correo']) ?></strong></p>
    <div class="botones">
        <a href="index.php" class="btn btn--primary">Ir a mi panel →</a>
        <a href="cerrar_sesion.php" class="btn btn--ghost">Cerrar sesión</a>
    </div>
</div>
</body>
</html>
