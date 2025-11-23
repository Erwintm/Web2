<?php
session_start();

if (!isset($_SESSION['tipo_usuario']) || $_SESSION['tipo_usuario'] !== 'administrador') {
    header("Location: ../Presentacion/index.php");
    exit;
}

require_once __DIR__ . '/../Datos/conexion.php';

// Validar método
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: ../Presentacion/Administrador/agregar_alumno.php");
    exit;
}

// Sanitización
function limpiarTexto($v) {
    return trim(filter_var($v, FILTER_SANITIZE_STRING));
}

function validarRegex($valor, $regex) {
    return preg_match($regex, $valor);
}

// Recibir datos limpios
$matricula = limpiarTexto($_POST['matricula']);
$nombre = limpiarTexto($_POST['nombre']);
$apellido = limpiarTexto($_POST['apellido']);
$email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
$telefono = limpiarTexto($_POST['telefono']);
$fecha_nacimiento = limpiarTexto($_POST['fecha_nac']);
$carrera = limpiarTexto($_POST['carrera']);
$usuario = limpiarTexto($_POST['usuario']);
$password = $_POST['password'];

// Validaciones
$errores = [];

if (!validarRegex($nombre, "/^[A-Za-zÁÉÍÓÚáéíóúñÑ ]{2,40}$/"))
    $errores[] = "Nombre con caracteres inválidos.";

if (!validarRegex($apellido, "/^[A-Za-zÁÉÍÓÚáéíóúñÑ ]{2,40}$/"))
    $errores[] = "Apellido con caracteres inválidos.";

if (!$email)
    $errores[] = "Email no válido.";

if ($telefono !== "" && !validarRegex($telefono, "/^[0-9]{10}$/"))
    $errores[] = "Teléfono debe ser de 10 dígitos.";

if (!validarRegex($usuario, "/^[A-Za-z0-9._-]{4,20}$/"))
    $errores[] = "Usuario contiene caracteres no permitidos.";

if (strlen($password) < 6)
    $errores[] = "La contraseña debe tener mínimo 6 caracteres.";

if (!empty($errores)) {
    echo "<script>alert('".implode("\\n", $errores)."'); window.history.back();</script>";
    exit;
}

try {

    $pdo->beginTransaction();

    // Insertar usuario
    $sqlUser = "INSERT INTO usuarios (nombre_usuario, contraseña, email, tipo_usuario)
                VALUES (:usuario, SHA2(:password,256), :email, 'alumno')";
    $stmtUser = $pdo->prepare($sqlUser);
    $stmtUser->execute([
        ':usuario' => $usuario,
        ':password' => $password,
        ':email' => $email
    ]);

    $id_usuario = $pdo->lastInsertId();

    // Insertar alumno
    $sqlAlumno = "INSERT INTO alumnos
        (id_usuario, matricula, nombre, apellido, email, telefono, fecha_nacimiento, estado, carrera)
        VALUES (:id_usuario, :matricula, :nombre, :apellido, :email, :telefono, :fecha_nacimiento, 'activo', :carrera)";
    $stmtAlumno = $pdo->prepare($sqlAlumno);
    $stmtAlumno->execute([
        ':id_usuario' => $id_usuario,
        ':matricula' => $matricula,
        ':nombre' => $nombre,
        ':apellido' => $apellido,
        ':email' => $email,
        ':telefono' => $telefono,
        ':fecha_nacimiento' => $fecha_nacimiento,
        ':carrera' => $carrera
    ]);

    $pdo->commit();

    echo "<script>
            alert('Alumno registrado correctamente. Usuario: {$usuario}');
            window.location.href = '../Presentacion/Administrador/gestion_alumnos.php';
          </script>";
    exit;

} catch (PDOException $e) {
    if ($pdo->inTransaction()) $pdo->rollBack();
    echo "<script>alert('Error: ".addslashes($e->getMessage())."'); window.history.back();</script>";
    exit;
}
?>
