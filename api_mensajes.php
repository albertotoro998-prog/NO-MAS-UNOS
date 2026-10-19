<?php
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['correo'])) {
    echo json_encode(['ok'=>false,'msg'=>'No autenticado']);
    exit();
}

$correo = $_SESSION['correo'];
$srv = "localhost"; $usr = "root"; $pw = ""; $db = "nomasunos";
$con = mysqli_connect($srv,$usr,$pw,$db);
if (!$con) { echo json_encode(['ok'=>false,'msg'=>'DB error']); exit(); }

$action = $_POST['action'] ?? $_GET['action'] ?? '';
$correo_esc = mysqli_real_escape_string($con, $correo);

// ── LISTAR ──────────────────────────────────────────────────
if ($action === 'listar') {
    $r = mysqli_query($con, "SELECT * FROM mensajes WHERE usuario_correo='$correo_esc' ORDER BY creado_en DESC");
    $mensajes = [];
    while ($row = mysqli_fetch_assoc($r)) $mensajes[] = $row;
    echo json_encode(['ok'=>true,'mensajes'=>$mensajes]);
    exit();
}

// ── CREAR ────────────────────────────────────────────────────
if ($action === 'crear') {
    $titulo    = mysqli_real_escape_string($con, trim($_POST['titulo'] ?? ''));
    $contenido = mysqli_real_escape_string($con, trim($_POST['contenido'] ?? ''));

    if (!$titulo) { echo json_encode(['ok'=>false,'msg'=>'El título es obligatorio']); exit(); }

    $q = "INSERT INTO mensajes (usuario_correo,titulo,contenido,leido) VALUES ('$correo_esc','$titulo','$contenido',0)";
    if (mysqli_query($con, $q)) {
        echo json_encode(['ok'=>true,'id'=>mysqli_insert_id($con)]);
    } else {
        echo json_encode(['ok'=>false,'msg'=>mysqli_error($con)]);
    }
    exit();
}

// ── MARCAR LEÍDO / NO LEÍDO ────────────────────────────────────
if ($action === 'leido') {
    $id = (int)($_POST['id'] ?? 0);
    mysqli_query($con, "UPDATE mensajes SET leido = IF(leido=1,0,1) WHERE id=$id AND usuario_correo='$correo_esc'");
    echo json_encode(['ok'=>true]);
    exit();
}

// ── ELIMINAR ─────────────────────────────────────────────────
if ($action === 'eliminar') {
    $id = (int)($_POST['id'] ?? 0);
    mysqli_query($con, "DELETE FROM mensajes WHERE id=$id AND usuario_correo='$correo_esc'");
    echo json_encode(['ok'=>true]);
    exit();
}

echo json_encode(['ok'=>false,'msg'=>'Acción desconocida']);
