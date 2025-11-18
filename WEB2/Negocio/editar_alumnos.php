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

$id_alumno  = $_POST['id_alumno'];
$id_usuario = $_POST['id_usuario'];

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

if ($matricula === "" || $nombre === "" || $apellido === "" || $email === "" ||
    $fecha_nac === "" || $carrera === "" || $usuario === "") {

    echo "<script>alert('Faltan campos requeridos'); window.history.back();</script>";
    exit;
}

try {
    $pdo->beginTransaction();

    // UPDATE usuarios
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

    // UPDATE alumnos
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
