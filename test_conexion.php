<?php
/**
 * Herramienta de Diagnóstico y Prueba de Conexión MySQL
 * Proyecto: No Más Unos
 *
 * Abre este archivo en tu navegador (ej: http://localhost/nomasunos/test_conexion.php)
 * para comprobar el estado de conexión con tu base de datos MySQL.
 */

$servidor    = getenv('DB_HOST') !== false ? getenv('DB_HOST') : 'localhost';
$usuario     = getenv('DB_USER') !== false ? getenv('DB_USER') : 'root';
$clave       = getenv('DB_PASS') !== false ? getenv('DB_PASS') : '';
$baseDeDatos = getenv('DB_NAME') !== false ? getenv('DB_NAME') : 'nomasunos';
$puerto      = getenv('DB_PORT') !== false ? (int)getenv('DB_PORT') : 3306;

// 1. Verificar extensión mysqli
$mysqli_disponible = extension_loaded('mysqli');

$conexion_servidor = false;
$error_servidor = null;
$db_existe = false;
$error_db = null;
$tablas_estado = [];
$tablas_esperadas = ['datos', 'tareas', 'proyectos', 'notas', 'mensajes'];

if ($mysqli_disponible) {
    if (function_exists('mysqli_report')) {
        mysqli_report(MYSQLI_REPORT_OFF);
    }

    // Intentar conectar al servidor MySQL (sin seleccionar BD para aislar el error)
    $link_servidor = @mysqli_connect($servidor, $usuario, $clave, null, $puerto);
    if ($link_servidor) {
        $conexion_servidor = true;

        // Verificar si la base de datos existe
        $res_db = @mysqli_select_db($link_servidor, $baseDeDatos);
        if ($res_db) {
            $db_existe = true;

            // Verificar cada tabla
            foreach ($tablas_esperadas as $tabla) {
                $q = @mysqli_query($link_servidor, "SHOW TABLES LIKE '$tabla'");
                if ($q && mysqli_num_rows($q) > 0) {
                    $cnt = @mysqli_query($link_servidor, "SELECT COUNT(*) as total FROM `$tabla`");
                    $total = $cnt ? mysqli_fetch_assoc($cnt)['total'] : 0;
                    $tablas_estado[$tabla] = ['existe' => true, 'filas' => $total];
                } else {
                    $tablas_estado[$tabla] = ['existe' => false, 'filas' => 0];
                }
            }
        } else {
            $error_db = mysqli_error($link_servidor);
        }
        mysqli_close($link_servidor);
    } else {
        $error_servidor = mysqli_connect_error();
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Prueba de Conexión MySQL — No Más Unos</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Syne:wght@700;800&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
  <style>
    :root {
      --purple: #5B4BF5;
      --purple-lt: #EAE7FF;
      --ink: #0D0D14;
      --bg: #F6F5FF;
      --surface: #FFFFFF;
      --border: #E2DFFB;
      --muted: #6B6882;
      --success: #10B981;
      --success-bg: #ECFDF5;
      --error: #EF4444;
      --error-bg: #FEF2F2;
      --warn: #F59E0B;
      --warn-bg: #FFFBEB;
    }
    * { box-sizing: border-box; margin: 0; padding: 0; }
    body {
      font-family: 'Inter', sans-serif;
      background: var(--bg);
      color: var(--ink);
      padding: 2rem 1rem;
      display: flex;
      justify-content: center;
      min-height: 100vh;
    }
    .container {
      width: 100%;
      max-width: 720px;
      background: var(--surface);
      border-radius: 20px;
      border: 1px solid var(--border);
      padding: 2.5rem;
      box-shadow: 0 8px 30px rgba(91, 75, 245, 0.08);
    }
    h1 {
      font-family: 'Syne', sans-serif;
      font-size: 1.8rem;
      margin-bottom: 0.5rem;
      color: var(--ink);
    }
    p.subtitle {
      color: var(--muted);
      margin-bottom: 2rem;
      font-size: 0.95rem;
    }
    .status-card {
      padding: 1.25rem 1.5rem;
      border-radius: 14px;
      margin-bottom: 1rem;
      display: flex;
      align-items: flex-start;
      gap: 1rem;
      border: 1px solid transparent;
    }
    .status-card.ok {
      background: var(--success-bg);
      border-color: rgba(16, 185, 129, 0.25);
    }
    .status-card.fail {
      background: var(--error-bg);
      border-color: rgba(239, 68, 68, 0.25);
    }
    .icon {
      font-size: 1.4rem;
      line-height: 1;
    }
    .status-title {
      font-weight: 700;
      font-size: 1rem;
      margin-bottom: 0.25rem;
    }
    .status-desc {
      font-size: 0.88rem;
      color: var(--muted);
      line-height: 1.4;
    }
    .info-table {
      width: 100%;
      border-collapse: collapse;
      margin: 1.5rem 0;
      font-size: 0.9rem;
    }
    .info-table th, .info-table td {
      padding: 0.75rem 1rem;
      text-align: left;
      border-bottom: 1px solid var(--border);
    }
    .info-table th {
      color: var(--muted);
      font-weight: 600;
      background: #FAFAFF;
    }
    .badge {
      display: inline-block;
      padding: 0.2rem 0.6rem;
      border-radius: 99px;
      font-size: 0.78rem;
      font-weight: 600;
    }
    .badge.ok { background: #D1FAE5; color: #065F46; }
    .badge.fail { background: #FEE2E2; color: #991B1B; }
    .actions {
      display: flex;
      gap: 0.75rem;
      margin-top: 2rem;
      flex-wrap: wrap;
    }
    .btn {
      display: inline-flex;
      align-items: center;
      gap: 0.5rem;
      padding: 0.75rem 1.25rem;
      border-radius: 99px;
      text-decoration: none;
      font-weight: 600;
      font-size: 0.9rem;
      transition: all 0.2s;
    }
    .btn-primary {
      background: var(--purple);
      color: #fff;
    }
    .btn-primary:hover {
      box-shadow: 0 4px 15px rgba(91, 75, 245, 0.35);
    }
    .btn-secondary {
      background: var(--purple-lt);
      color: var(--purple);
    }
    .code-box {
      background: #0D0D14;
      color: #A6A2C4;
      padding: 1rem;
      border-radius: 10px;
      font-family: monospace;
      font-size: 0.85rem;
      margin-top: 0.5rem;
      overflow-x: auto;
    }
  </style>
</head>
<body>
  <div class="container">
    <h1>Diagnóstico MySQL</h1>
    <p class="subtitle">Verificación automática de conectividad con la base de datos de No Más Unos</p>

    <!-- 1. Extensión PHP -->
    <div class="status-card <?= $mysqli_disponible ? 'ok' : 'fail' ?>">
      <div class="icon"><?= $mysqli_disponible ? '✓' : '✗' ?></div>
      <div>
        <div class="status-title">Extensión PHP MySQLi: <?= $mysqli_disponible ? 'Activa' : 'No instalada' ?></div>
        <div class="status-desc">
          <?= $mysqli_disponible ? 'PHP tiene habilitado el módulo MySQLi correctamente.' : 'Debes habilitar la extensión mysqli en tu archivo php.ini (extension=mysqli).' ?>
        </div>
      </div>
    </div>

    <!-- 2. Servidor MySQL -->
    <div class="status-card <?= $conexion_servidor ? 'ok' : 'fail' ?>">
      <div class="icon"><?= $conexion_servidor ? '✓' : '✗' ?></div>
      <div>
        <div class="status-title">Servidor MySQL (<?= htmlspecialchars($servidor) ?>:<?= (int)$puerto ?>): <?= $conexion_servidor ? 'Conectado' : 'Sin conexión' ?></div>
        <div class="status-desc">
          <?php if ($conexion_servidor): ?>
            Conexión establecida exitosamente con el usuario <strong><?= htmlspecialchars($usuario) ?></strong>.
          <?php else: ?>
            No se pudo conectar a MySQL: <code><?= htmlspecialchars($error_servidor ?: 'Servidor no responde') ?></code>.
            <br>Si usas XAMPP o WAMP, asegúrate de haber dado clic en <strong>Start</strong> en el módulo MySQL.
          <?php endif; ?>
        </div>
      </div>
    </div>

    <!-- 3. Base de Datos -->
    <div class="status-card <?= $db_existe ? 'ok' : 'fail' ?>">
      <div class="icon"><?= $db_existe ? '✓' : '✗' ?></div>
      <div>
        <div class="status-title">Base de datos '<?= htmlspecialchars($baseDeDatos) ?>': <?= $db_existe ? 'Existe' : 'No encontrada' ?></div>
        <div class="status-desc">
          <?php if ($db_existe): ?>
            La base de datos se encuentra disponible y lista para consultas.
          <?php else: ?>
            La base de datos aún no ha sido creada. Puedes crearla e importar las tablas ejecutando el script <code>database.sql</code> en phpMyAdmin.
          <?php endif; ?>
        </div>
      </div>
    </div>

    <!-- 4. Tablas Requeridas -->
    <?php if ($db_existe): ?>
      <h3 style="margin-top: 2rem; margin-bottom: 0.5rem; font-family:'Syne',sans-serif;">Tablas del Sistema</h3>
      <table class="info-table">
        <thead>
          <tr>
            <th>Tabla</th>
            <th>Estado</th>
            <th>Registros</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($tablas_esperadas as $tabla): ?>
            <?php $info = $tablas_estado[$tabla] ?? ['existe' => false, 'filas' => 0]; ?>
            <tr>
              <td><code><?= htmlspecialchars($tabla) ?></code></td>
              <td>
                <span class="badge <?= $info['existe'] ? 'ok' : 'fail' ?>">
                  <?= $info['existe'] ? 'Existe' : 'Falta' ?>
                </span>
              </td>
              <td><?= (int)$info['filas'] ?> registros</td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    <?php endif; ?>

    <!-- Acciones y enlaces -->
    <div class="actions">
      <a href="login.php" class="btn btn-primary">Ir al Login →</a>
      <a href="registro.php" class="btn btn-secondary">Ir al Registro</a>
      <a href="index.html" class="btn btn-secondary">Landing Page</a>
    </div>

    <?php if (!$db_existe || !$conexion_servidor): ?>
      <div style="margin-top: 2rem; padding-top: 1.5rem; border-top: 1px solid var(--border);">
        <h4 style="margin-bottom: 0.5rem;">Instrucciones para solucionar:</h4>
        <ol style="margin-left: 1.5rem; font-size: 0.9rem; line-height: 1.6; color: var(--muted);">
          <li>Abre <strong>XAMPP Control Panel</strong> o tu panel de MySQL y pulsa <strong>Start</strong> en MySQL.</li>
          <li>Abre tu navegador e ingresa a <strong>http://localhost/phpmyadmin</strong>.</li>
          <li>Crea una nueva base de datos llamada <code>nomasunos</code>.</li>
          <li>Haz clic en la pestaña <strong>Importar</strong> y selecciona el archivo <code>database.sql</code> de esta carpeta.</li>
          <li>Recarga esta página para confirmar que todas las comprobaciones salgan en verde.</li>
        </ol>
      </div>
    <?php endif; ?>
  </div>
</body>
</html>
