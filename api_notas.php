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
    $r = mysqli_query($con, "SELECT * FROM notas WHERE usuario_correo='$correo_esc' ORDER BY creado_en DESC");
    $notas = [];
    while ($row = mysqli_fetch_assoc($r)) $notas[] = $row;
    echo json_encode(['ok'=>true,'notas'=>$notas]);
    exit();
}

// ── GUARDAR (crear) ────────────────────────────────────────
if ($action === 'guardar') {
    $materia     = mysqli_real_escape_string($con, trim($_POST['materia'] ?? ''));
    $descripcion = mysqli_real_escape_string($con, trim($_POST['descripcion'] ?? ''));
    $tipo        = ($_POST['tipo'] ?? 'nota') === 'firma' ? 'firma' : 'nota';

    if (!$materia) { echo json_encode(['ok'=>false,'msg'=>'La materia es obligatoria']); exit(); }

    if ($tipo === 'nota') {
        $valorRaw = trim($_POST['valor'] ?? '');
        if ($valorRaw === '' || !is_numeric($valorRaw)) { echo json_encode(['ok'=>false,'msg'=>'Ingresa una nota válida']); exit(); }
        $valor = max(0, min(5, (float)$valorRaw));
        $q = "INSERT INTO notas (usuario_correo,materia,descripcion,tipo,valor,firmado)
              VALUES ('$correo_esc','$materia','$descripcion','nota',$valor,0)";
    } else {
        $q = "INSERT INTO notas (usuario_correo,materia,descripcion,tipo,valor,firmado)
              VALUES ('$correo_esc','$materia','$descripcion','firma',NULL,0)";
    }

    if (mysqli_query($con, $q)) {
        echo json_encode(['ok'=>true,'id'=>mysqli_insert_id($con)]);
    } else {
        echo json_encode(['ok'=>false,'msg'=>mysqli_error($con)]);
    }
    exit();
}

// ── ALTERNAR FIRMA (pendiente ↔ firmado) ─────────────────────
if ($action === 'firmar') {
    $id = (int)($_POST['id'] ?? 0);
    mysqli_query($con, "UPDATE notas SET firmado = IF(firmado=1,0,1) WHERE id=$id AND usuario_correo='$correo_esc' AND tipo='firma'");
    echo json_encode(['ok'=>true]);
    exit();
}

// ── ELIMINAR ─────────────────────────────────────────────────
if ($action === 'eliminar') {
    $id = (int)($_POST['id'] ?? 0);
    mysqli_query($con, "DELETE FROM notas WHERE id=$id AND usuario_correo='$correo_esc'");
    echo json_encode(['ok'=>true]);
    exit();
}

echo json_encode(['ok'=>false,'msg'=>'Acción desconocida']);
