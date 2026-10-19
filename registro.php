<?php
$servidor = "localhost";
$usuario = "root";
$clave = "";
$baseDeDatos = "nomasunos";
$enlace = mysqli_connect($servidor, $usuario, $clave, $baseDeDatos);

$mensaje = "";
$tipo = "";

if (isset($_POST['registro'])) {
    $nombre   = mysqli_real_escape_string($enlace, trim($_POST['nombre']));
    $correo   = mysqli_real_escape_string($enlace, trim($_POST['correo']));
    $telefono = mysqli_real_escape_string($enlace, trim($_POST['telefono']));
    $password = $_POST['password'];

    if (!preg_match('/^\d{10}$/', $telefono)) {
        $mensaje = "El teléfono debe tener exactamente 10 dígitos.";
        $tipo = "error";
    } elseif (empty($nombre) || empty($correo) || empty($password)) {
        $mensaje = "Todos los campos son obligatorios.";
        $tipo = "error";
    } else {
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $check = mysqli_query($enlace, "SELECT correo FROM datos WHERE correo='$correo'");
        if (mysqli_num_rows($check) > 0) {
            $mensaje = "Ese correo ya está registrado.";
            $tipo = "error";
        } else {
            $sql = "INSERT INTO datos (nombre, correo, telefono, password) VALUES ('$nombre','$correo','$telefono','$hash')";
            if (mysqli_query($enlace, $sql)) {
                $mensaje = "¡Cuenta creada! Redirigiendo al login...";
                $tipo = "exito";
                echo '<meta http-equiv="refresh" content="2;url=login.php">';
            } else {
                $mensaje = "Error al registrar. Intenta de nuevo.";
                $tipo = "error";
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Registro — No Más Unos</title>
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
            position: fixed; top: -200px; left: -200px;
            width: 600px; height: 600px; border-radius: 50%;
            background: radial-gradient(circle, rgba(91,75,245,.12) 0%, transparent 70%);
            pointer-events: none;
        }
        .card {
            background: var(--surface);
            border-radius: 20px;
            border: 1px solid var(--border);
            padding: 2.5rem;
            width: 100%; max-width: 440px;
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
        input[type="text"], input[type="email"], input[type="password"] {
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
        .hint { font-size: 12px; color: #a1a1aa; }
        .botones { display: flex; gap: 10px; margin-top: 1.25rem; }
        input[type="submit"] {
            flex: 1; padding: 12px;
            background: var(--purple); color: #fff;
            border: none; border-radius: 99px;
            font-size: 15px; font-weight: 700;
            font-family: 'Syne', sans-serif;
            cursor: pointer;
            box-shadow: 0 6px 20px rgba(91,75,245,.35);
            transition: transform .18s, box-shadow .18s;
        }
        input[type="submit"]:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 28px rgba(91,75,245,.45);
        }
        input[type="reset"] {
            padding: 12px 18px; background: transparent;
            color: var(--muted); border: 1.5px solid var(--border);
            border-radius: 99px; font-size: 14px;
            font-family: 'Inter', sans-serif; cursor: pointer;
            transition: border-color .2s, color .2s;
        }
        input[type="reset"]:hover { border-color: var(--purple); color: var(--purple); }
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
        .link-login {
            text-align: center; margin-top: 1.25rem;
            font-size: 13px; color: var(--muted);
        }
        .link-login a { color: var(--purple); text-decoration: none; font-weight: 600; }
        .link-login a:hover { text-decoration: underline; }
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
    <div class="avatar">👤</div>
    <h1>Crear cuenta</h1>
    <p class="subtitulo">Únete y nunca más pierdas una entrega</p>

    <form action="registro.php" method="post">
        <div class="campo">
            <label for="nombre">Nombre completo</label>
            <input type="text" id="nombre" name="nombre" placeholder="Tu nombre" required>
        </div>
        <div class="campo">
            <label for="correo">Correo electrónico</label>
            <input type="email" id="correo" name="correo" placeholder="correo@ejemplo.com" required>
        </div>
        <div class="campo">
            <label for="telefono">Teléfono</label>
            <input type="text" id="telefono" name="telefono" placeholder="3001234567"
                   maxlength="10" pattern="\d{10}"
                   oninput="this.value=this.value.replace(/[^0-9]/g,'')" required>
            <span class="hint">Solo números, exactamente 10 dígitos</span>
        </div>
        <div class="campo">
            <label for="password">Contraseña</label>
            <input type="password" id="password" name="password" placeholder="Mínimo 6 caracteres" minlength="6" required>
        </div>
        <div class="botones">
            <input type="submit" name="registro" value="Registrarse →">
            <input type="reset" value="Limpiar">
        </div>
    </form>

    <?php if ($mensaje): ?>
        <div class="msg-<?= $tipo ?>"><?= $mensaje ?></div>
    <?php endif; ?>

    <div class="divider">— o —</div>
    <a href="index.html" class="btn-volver">← Volver al inicio</a>

    <div class="link-login">
        ¿Ya tienes cuenta? <a href="login.php">Inicia sesión</a>
    </div>
</div>
</body>
</html>
