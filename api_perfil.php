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

// ── OBTENER ─────────────────────────────────────────────────
if ($action === 'obtener') {
    $r = mysqli_query($con, "SELECT nombre, correo, telefono, edad, colegio, grado, bio, foto FROM datos WHERE correo='$correo_esc' LIMIT 1");
    $perfil = mysqli_fetch_assoc($r);
    if (!$perfil) { echo json_encode(['ok'=>false,'msg'=>'Usuario no encontrado']); exit(); }
    echo json_encode(['ok'=>true,'perfil'=>$perfil]);
    exit();
}

// ── GUARDAR DATOS ────────────────────────────────────────────
if ($action === 'guardar') {
    $nombre   = mysqli_real_escape_string($con, trim($_POST['nombre'] ?? ''));
    $telefono = mysqli_real_escape_string($con, trim($_POST['telefono'] ?? ''));
    $colegio  = mysqli_real_escape_string($con, trim($_POST['colegio'] ?? ''));
    $grado    = mysqli_real_escape_string($con, trim($_POST['grado'] ?? ''));
    $bio      = mysqli_real_escape_string($con, trim($_POST['bio'] ?? ''));
    $edadRaw  = trim($_POST['edad'] ?? '');
    $edad     = ($edadRaw !== '' && is_numeric($edadRaw)) ? (int)$edadRaw : null;

    if (!$nombre) { echo json_encode(['ok'=>false,'msg'=>'El nombre no puede estar vacío']); exit(); }

    $edad_val = $edad === null ? "NULL" : $edad;

    $q = "UPDATE datos SET
            nombre='$nombre',
            telefono='$telefono',
            colegio='$colegio',
            grado='$grado',
            bio='$bio',
            edad=$edad_val
          WHERE correo='$correo_esc'";

    if (mysqli_query($con, $q)) {
        $_SESSION['nombre'] = $nombre; // mantiene el nombre actualizado en toda la app
        echo json_encode(['ok'=>true]);
    } else {
        echo json_encode(['ok'=>false,'msg'=>mysqli_error($con)]);
    }
    exit();
}

// ── SUBIR FOTO ───────────────────────────────────────────────
if ($action === 'foto') {
    if (!isset($_FILES['foto']) || $_FILES['foto']['error'] !== UPLOAD_ERR_OK) {
        echo json_encode(['ok'=>false,'msg'=>'No se recibió ninguna imagen']);
        exit();
    }

    $archivo = $_FILES['foto'];
    $permitidos = ['image/jpeg'=>'jpg','image/png'=>'png','image/webp'=>'webp'];
    $tipo = mime_content_type($archivo['tmp_name']);

    if (!isset($permitidos[$tipo])) {
        echo json_encode(['ok'=>false,'msg'=>'Formato no válido. Usa JPG, PNG o WEBP.']);
        exit();
    }
    if ($archivo['size'] > 3 * 1024 * 1024) {
        echo json_encode(['ok'=>false,'msg'=>'La imagen no puede pesar más de 3MB.']);
        exit();
    }

    $carpeta = __DIR__ . '/uploads/perfil/';
    if (!is_dir($carpeta)) mkdir($carpeta, 0755, true);

    $ext = $permitidos[$tipo];
    $nombreArchivo = md5($correo) . '.' . $ext; // siempre el mismo nombre por usuario: se sobreescribe
    $rutaDestino = $carpeta . $nombreArchivo;

    if (!move_uploaded_file($archivo['tmp_name'], $rutaDestino)) {
        echo json_encode(['ok'=>false,'msg'=>'No se pudo guardar la imagen']);
        exit();
    }

    mysqli_query($con, "UPDATE datos SET foto='$nombreArchivo' WHERE correo='$correo_esc'");
    echo json_encode(['ok'=>true,'foto'=>$nombreArchivo]);
    exit();
}

echo json_encode(['ok'=>false,'msg'=>'Acción desconocida']);
