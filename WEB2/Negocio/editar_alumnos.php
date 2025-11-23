<?php
session_start();

if (!isset($_SESSION['tipo_usuario']) || $_SESSION['tipo_usuario'] !== 'administrador') {
    header("Location: ../Presentacion/index.php");
    exit;
}

require_once __DIR__ . '/../Datos/conexion.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: ../Presentacion/Administrador/gestion_alumnos.php");
    exit;
}

$id_alumno  = (int)$_POST['id_alumno'];
$id_usuario = (int)$_POST['id_usuario'];

$matricula = trim($_POST['matricula']);
$nombre = trim($_POST['nombre']);
$apellido = trim($_POST['apellido']);
$email = trim($_POST['email']);
$telefono = trim($_POST['telefono']);
$fecha_nac = trim($_POST['fecha_nacimiento']);
$carrera = trim($_POST['carrera']);
$usuario = trim($_POST['usuario']);
$password = $_POST['password'];
$estado = trim($_POST['estado']);

$errores = [];


// ---------- VALIDACIONES ----------
if (!preg_match('/^[A-Z0-9]{6,12}$/', $matricula)) {
    $errores[] = "La matrícula es inválida.";
}

if (!preg_match('/^[A-Za-zÁÉÍÓÚÑáéíóúñ ]{2,40}$/', $nombre)) {
    $errores[] = "Nombre inválido (solo letras).";
}

if (!preg_match('/^[A-Za-zÁÉÍÓÚÑáéíóúñ ]{2,40}$/', $apellido)) {
    $errores[] = "Apellido inválido (solo letras).";
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errores[] = "Email no válido.";
}

if (!preg_match('/^[0-9]{7,15}$/', $telefono) && $telefono !== "") {
    $errores[] = "Teléfono no válido.";
}

if (!preg_match('/^[A-Za-z0-9_]{4,20}$/', $usuario)) {
    $errores[] = "El usuario solo puede contener letras, números y guiones bajos.";
}

if (!in_array($estado, ["activo", "inactivo"])) {
    $errores[] = "Estado inválido.";
}

if (!empty($errores)) {
    echo "<script>alert('".implode("\\n", $errores)."'); window.history.back();</script>";
    exit;
}


// ---------- ACTUALIZAR ----------
try {
    $pdo->beginTransaction();

    if ($password == "") {
        $sqlU = "UPDATE usuarios SET nombre_usuario = :usuario, email = :email
                 WHERE id_usuario = :id_usuario";
        $stmtU = $pdo->prepare($sqlU);
        $stmtU->execute([
            ':usuario' => $usuario,
            ':email' => $email,
            ':id_usuario' => $id_usuario
        ]);
    } else {
        $sqlU = "UPDATE usuarios SET nombre_usuario = :usuario, email = :email,
                 contraseña = SHA2(:password, 256)
                 WHERE id_usuario = :id_usuario";
        $stmtU = $pdo->prepare($sqlU);
        $stmtU->execute([
            ':usuario' => $usuario,
            ':email' => $email,
            ':password' => $password,
            ':id_usuario' => $id_usuario
        ]);
    }

    $sqlA = "UPDATE alumnos SET 
                matricula = :matricula,
                nombre = :nombre,
                apellido = :apellido,
                email = :email,
                telefono = :telefono,
                fecha_nacimiento = :fecha,
                carrera = :carrera,
                estado = :estado
             WHERE id_alumno = :id_alumno";

    $stmtA = $pdo->prepare($sqlA);
    $stmtA->execute([
        ':matricula' => $matricula,
        ':nombre' => $nombre,
        ':apellido' => $apellido,
        ':email' => $email,
        ':telefono' => $telefono,
        ':fecha' => $fecha_nac,
        ':carrera' => $carrera,
        ':estado' => $estado,
        ':id_alumno' => $id_alumno
    ]);

    $pdo->commit();

    echo "<script>
            alert('Alumno actualizado correctamente.');
            window.location.href = '../Presentacion/Administrador/gestion_alumnos.php';
          </script>";
    exit;

} catch (PDOException $e) {
    if ($pdo->inTransaction()) $pdo->rollBack();
    echo "<script>alert('Error: ".addslashes($e->getMessage())."'); window.history.back();</script>";
    exit;
}
