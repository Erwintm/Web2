<?php
require_once '../Datos/conexion.php';

// --- Sanitizar función ---
function limpiar($str) {
    return htmlspecialchars(trim($str), ENT_QUOTES, 'UTF-8');
}

// --- Obtener valores ---
$nombre = limpiar($_POST['nombre'] ?? '');
$apellido = limpiar($_POST['apellido'] ?? '');
$email = limpiar($_POST['email'] ?? '');
$telefono = limpiar($_POST['telefono'] ?? '');
$usuario = limpiar($_POST['usuario'] ?? '');
$password = $_POST['password'] ?? '';
$especialidad = limpiar($_POST['especialidad'] ?? '');

// --- VALIDACIONES ---

// Campos vacíos
if (
    empty($nombre) || empty($apellido) || empty($email) ||
    empty($usuario) || empty($password) || empty($especialidad)
) {
    echo "<script>alert('Todos los campos marcados son obligatorios.'); history.back();</script>";
    exit;
}

// Nombre y apellido solo letras
if (!preg_match("/^[a-zA-ZÁÉÍÓÚáéíóúñÑ ]+$/", $nombre)) {
    echo "<script>alert('El nombre solo debe contener letras.'); history.back();</script>";
    exit;
}

if (!preg_match("/^[a-zA-ZÁÉÍÓÚáéíóúñÑ ]+$/", $apellido)) {
    echo "<script>alert('El apellido solo debe contener letras.'); history.back();</script>";
    exit;
}

// Email válido
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo "<script>alert('El correo electrónico no es válido.'); history.back();</script>";
    exit;
}

// Teléfono opcional pero debe ser numérico de 10 dígitos
if (!empty($telefono) && !preg_match("/^[0-9]{10}$/", $telefono)) {
    echo "<script>alert('El teléfono debe contener exactamente 10 dígitos.'); history.back();</script>";
    exit;
}

// Usuario mínimo 4 caracteres
if (strlen($usuario) < 4) {
    echo "<script>alert('El usuario debe tener al menos 4 caracteres.'); history.back();</script>";
    exit;
}

// Password mínimo 6 caracteres
if (strlen($password) < 6) {
    echo "<script>alert('La contraseña debe tener mínimo 6 caracteres.'); history.back();</script>";
    exit;
}

$passwordHash = password_hash($password, PASSWORD_DEFAULT);

try {
    // Iniciar transacción
    $pdo->beginTransaction();

    // 1. Insertar en usuarios
    $sqlUser = "INSERT INTO usuarios (nombre_usuario, contraseña, email, tipo_usuario)
                VALUES (:usuario, :password, :email, 'maestro')";
    $stmtUser = $pdo->prepare($sqlUser);

    $stmtUser->bindParam(':usuario', $usuario);
    $stmtUser->bindParam(':password', $passwordHash);
    $stmtUser->bindParam(':email', $email);

    $stmtUser->execute();

    $idUsuario = $pdo->lastInsertId();

    // 2. Insertar maestro
    $sqlMaestro = "INSERT INTO maestros (id_usuario, nombre, apellido, email, telefono, especialidad)
                   VALUES (:id_usuario, :nombre, :apellido, :email, :telefono, :especialidad)";
    $stmtMaestro = $pdo->prepare($sqlMaestro);

    $stmtMaestro->bindParam(':id_usuario', $idUsuario);
    $stmtMaestro->bindParam(':nombre', $nombre);
    $stmtMaestro->bindParam(':apellido', $apellido);
    $stmtMaestro->bindParam(':email', $email);
    $stmtMaestro->bindParam(':telefono', $telefono);
    $stmtMaestro->bindParam(':especialidad', $especialidad);

    $stmtMaestro->execute();

    $pdo->commit();

    echo "<script>
        alert('Maestro registrado correctamente. Usuario: {$usuario}');
        window.location.href = '../Presentacion/Administrador/gestion_maestros.php';
    </script>";
    exit;

} catch (Exception $e) {
    $pdo->rollBack();
    echo "Error: " . $e->getMessage();
}
?>
