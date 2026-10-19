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
    $r = mysqli_query($con, "SELECT * FROM proyectos WHERE usuario_correo='$correo_esc' ORDER BY fecha_entrega ASC, creado_en DESC");
    $proyectos = [];
    while ($row = mysqli_fetch_assoc($r)) $proyectos[] = $row;
    echo json_encode(['ok'=>true,'proyectos'=>$proyectos]);
    exit();
}

// ── CREAR ────────────────────────────────────────────────────
if ($action === 'crear') {
    $nombre    = mysqli_real_escape_string($con, trim($_POST['nombre']));
    $materia   = mysqli_real_escape_string($con, trim($_POST['materia'] ?? 'General'));
    $fecha     = mysqli_real_escape_string($con, trim($_POST['fecha'] ?? ''));
    $prioridad = mysqli_real_escape_string($con, trim($_POST['prioridad'] ?? 'media'));
    $desc      = mysqli_real_escape_string($con, trim($_POST['descripcion'] ?? ''));

    if (!$nombre) { echo json_encode(['ok'=>false,'msg'=>'Nombre requerido']); exit(); }

    $fecha_val = $fecha ? "'$fecha'" : "NULL";

    $q = "INSERT INTO proyectos (usuario_correo,nombre,materia,fecha_entrega,prioridad,descripcion,estado)
          VALUES ('$correo_esc','$nombre','$materia',$fecha_val,'$prioridad','$desc','planificacion')";
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
    $estado = mysqli_real_escape_string($con, $_POST['estado'] ?? 'planificacion');
    mysqli_query($con, "UPDATE proyectos SET estado='$estado' WHERE id=$id AND usuario_correo='$correo_esc'");
    echo json_encode(['ok'=>true]);
    exit();
}

// ── ELIMINAR ─────────────────────────────────────────────────
if ($action === 'eliminar') {
    $id = (int)($_POST['id'] ?? 0);
    mysqli_query($con, "DELETE FROM proyectos WHERE id=$id AND usuario_correo='$correo_esc'");
    echo json_encode(['ok'=>true]);
    exit();
}

echo json_encode(['ok'=>false,'msg'=>'Acción desconocida']);
