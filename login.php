<?php
session_start();

$servidor = "localhost";
$usuario = "root";
$clave = "";
$baseDeDatos = "nomasunos";
$enlace = mysqli_connect($servidor, $usuario, $clave, $baseDeDatos);

$mensaje = "";
$tipo = "";

if (isset($_POST['login'])) {
    $correo   = mysqli_real_escape_string($enlace, trim($_POST['correo']));
    $password = $_POST['password'];

    $sql = mysqli_query($enlace, "SELECT * FROM datos WHERE correo='$correo'");

    if (mysqli_num_rows($sql) == 1) {
        $usuario_db = mysqli_fetch_assoc($sql);
        if (password_verify($password, $usuario_db['password'])) {
            $_SESSION['nombre'] = $usuario_db['nombre'];
            $_SESSION['correo'] = $usuario_db['correo'];
            header("Location: index.php");
            exit();
        } else {
            $mensaje = "Contraseña incorrecta.";
            $tipo = "error";
        }
    } else {
        $mensaje = "No existe una cuenta con ese correo.";
        $tipo = "error";
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Iniciar sesión — No Más Unos</title>
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
        /* Blob decorativo igual que la landing */
        body::before {
            content: '';
            position: fixed; top: -200px; right: -200px;
            width: 600px; height: 600px; border-radius: 50%;
            background: radial-gradient(circle, rgba(91,75,245,.12) 0%, transparent 70%);
            pointer-events: none;
        }
        .card {
            background: var(--surface);
            border-radius: 20px;
            border: 1px solid var(--border);
            padding: 2.5rem;
            width: 100%; max-width: 420px;
            box-shadow: 0 4px 24px rgba(91,75,245,.08);
            position: relative; z-index: 1;
        }
        .topline {
            display: flex; align-items: center; justify-content: space-between;
            margin-bottom: 2rem;
        }
        .logo {
            display: flex; align-items: center; gap: .5rem;
            font-family: 'Syne', sans-serif; font-weight: 800; font-size: 1rem;
            color: var(--ink);
            text-decoration: none;
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
        .logo-dot {
            width: 10px; height: 10px; border-radius: 50%;
            background: var(--coral); flex-shrink: 0;
        }
        .avatar {
            width: 52px; height: 52px; border-radius: 50%;
            background: var(--purple-lt);
            display: flex; align-items: center; justify-content: center;
            font-size: 24px; margin-bottom: 1rem;
        }
        h1 {
            font-family: 'Syne', sans-serif; font-weight: 800;
            font-size: 22px; color: var(--ink); margin-bottom: 4px;
        }
        .subtitulo { font-size: 14px; color: var(--muted); margin-bottom: 1.75rem; }
        .campo { display: flex; flex-direction: column; gap: 6px; margin-bottom: 1rem; }
        label { font-size: 13px; font-weight: 500; color: var(--muted); }
        input[type="email"], input[type="password"] {
            width: 100%; padding: 11px 14px;
            border: 1.5px solid var(--border); border-radius: 10px;
            font-size: 14px; font-family: 'Inter', sans-serif;
            color: var(--ink); outline: none;
            transition: border-color 0.2s, box-shadow 0.2s;
            background: var(--surface);
        }
        input:focus {
            border-color: var(--purple);
            box-shadow: 0 0 0 3px rgba(91,75,245,0.15);
        }
        input[type="submit"] {
            width: 100%; padding: 12px;
            background: var(--purple); color: #fff;
            border: none; border-radius: 99px;
            font-size: 15px; font-weight: 700;
            font-family: 'Syne', sans-serif;
            cursor: pointer; margin-top: 1.25rem;
            box-shadow: 0 6px 20px rgba(91,75,245,.35);
            transition: transform .18s, box-shadow .18s;
        }
        input[type="submit"]:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 28px rgba(91,75,245,.45);
        }
        .msg-exito {
            margin-top: 1rem; padding: 10px 14px;
            background: #f0fdf4; border: 1px solid #bbf7d0;
            color: #15803d; border-radius: 10px; font-size: 14px;
        }
        .msg-error {
            margin-top: 1rem; padding: 10px 14px;
            background: #fff1f0; border: 1px solid #fecaca;
            color: #b91c1c; border-radius: 10px; font-size: 14px;
        }
        .link-registro {
            text-align: center; margin-top: 1.25rem;
            font-size: 13px; color: var(--muted);
        }
        .link-registro a {
            color: var(--purple); text-decoration: none; font-weight: 600;
        }
        .link-registro a:hover { text-decoration: underline; }
        .divider {
            text-align: center; font-size: 12px; color: var(--border);
            margin: 1rem 0; letter-spacing: .05em;
        }
        .btn-volver {
            display: flex; align-items: center; justify-content: center;
            gap: .4rem; width: 100%; padding: 10px;
            background: transparent; color: var(--muted);
            border: 1.5px solid var(--border); border-radius: 99px;
            font-size: 13px; font-family: 'Inter', sans-serif;
            cursor: pointer; text-decoration: none;
            transition: border-color .2s, color .2s;
        }
        .btn-volver:hover { border-color: var(--purple); color: var(--purple); }
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
    <div class="avatar">🔐</div>
    <h1>Iniciar sesión</h1>
    <p class="subtitulo">Bienvenido de nuevo</p>

    <form action="login.php" method="post">
        <div class="campo">
            <label for="correo">Correo electrónico</label>
            <input type="email" id="correo" name="correo" placeholder="correo@ejemplo.com" required>
        </div>
        <div class="campo">
            <label for="password">Contraseña</label>
            <input type="password" id="password" name="password" placeholder="Tu contraseña" required>
        </div>
        <input type="submit" name="login" value="Entrar →">
    </form>

    <?php if ($mensaje): ?>
        <div class="msg-<?= $tipo ?>"><?= $mensaje ?></div>
    <?php endif; ?>

    <div class="divider">— o —</div>
    <a href="index.html" class="btn-volver">← Volver al inicio</a>

    <div class="link-registro">
        ¿No tienes cuenta? <a href="registro.php">Regístrate gratis</a>
    </div>
</div>
</body>
</html>
