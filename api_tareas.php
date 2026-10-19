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

// ── LISTAR ──────────────────────────────────────────────────
if ($action === 'listar') {
    $r = mysqli_query($con, "SELECT * FROM tareas WHERE usuario_correo='".mysqli_real_escape_string($con,$correo)."' ORDER BY fecha_limite ASC, creado_en DESC");
    $tareas = [];
    while ($row = mysqli_fetch_assoc($r)) $tareas[] = $row;
    echo json_encode(['ok'=>true,'tareas'=>$tareas]);
    exit();
}

// ── CREAR ────────────────────────────────────────────────────
if ($action === 'crear') {
    $nombre    = mysqli_real_escape_string($con, trim($_POST['nombre']));
    $materia   = mysqli_real_escape_string($con, trim($_POST['materia'] ?? 'General'));
    $fecha     = mysqli_real_escape_string($con, trim($_POST['fecha'] ?? ''));
    $hora      = mysqli_real_escape_string($con, trim($_POST['hora'] ?? ''));
    $prioridad = mysqli_real_escape_string($con, trim($_POST['prioridad'] ?? 'media'));
    $desc      = mysqli_real_escape_string($con, trim($_POST['descripcion'] ?? ''));

    if (!$nombre) { echo json_encode(['ok'=>false,'msg'=>'Nombre requerido']); exit(); }

    $fecha_val = $fecha ? "'$fecha'" : "NULL";
    $hora_val  = $hora  ? "'$hora'"  : "NULL";

    $q = "INSERT INTO tareas (usuario_correo,nombre,materia,fecha_limite,hora_limite,prioridad,descripcion)
          VALUES ('$correo','$nombre','$materia',$fecha_val,$hora_val,'$prioridad','$desc')";
    if (mysqli_query($con, $q)) {
        $id = mysqli_insert_id($con);
        echo json_encode(['ok'=>true,'id'=>$id]);
    } else {
        echo json_encode(['ok'=>false,'msg'=>mysqli_error($con)]);
    }
    exit();
}

// ── MOVER ESTADO ─────────────────────────────────────────────
if ($action === 'estado') {
    $id     = (int)($_POST['id'] ?? 0);
    $estado = mysqli_real_escape_string($con, $_POST['estado'] ?? 'pendiente');
    mysqli_query($con, "UPDATE tareas SET estado='$estado' WHERE id=$id AND usuario_correo='".mysqli_real_escape_string($con,$correo)."'");
    echo json_encode(['ok'=>true]);
    exit();
}

// ── ELIMINAR ─────────────────────────────────────────────────
if ($action === 'eliminar') {
    $id = (int)($_POST['id'] ?? 0);
    mysqli_query($con, "DELETE FROM tareas WHERE id=$id AND usuario_correo='".mysqli_real_escape_string($con,$correo)."'");
    echo json_encode(['ok'=>true]);
    exit();
}

echo json_encode(['ok'=>false,'msg'=>'Acción desconocida']);
