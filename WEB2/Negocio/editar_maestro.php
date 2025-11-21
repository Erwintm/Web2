<?php
session_start();

if (!isset($_SESSION['tipo_usuario']) || $_SESSION['tipo_usuario'] !== 'administrador') {
    header("Location: ../Presentacion/index.php");
    exit;
}

require_once __DIR__ . '/../Datos/conexion.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: ../Presentacion/Administrador/gestion_maestros.php");
    exit;
}

$id_maestro  = $_POST['id_maestro'];
$id_usuario = $_POST['id_usuario'];

$nombre = trim($_POST['nombre']);
$apellido = trim($_POST['apellido']);
$email = trim($_POST['email']);
$telefono = trim($_POST['telefono']);
$especialidad = trim($_POST['especialidad']);
$usuario = trim($_POST['usuario']);
$password = $_POST['password'];

if ($nombre=="" || $apellido=="" || $email=="" || $usuario=="" || $especialidad=="") {
    echo "<script>alert('Faltan campos obligatorios'); window.history.back();</script>";
    exit;
}

try {
    $pdo->beginTransaction();

    // --- UPDATE usuarios ---
    if ($password == "") {
        $sqlU = "UPDATE usuarios 
                 SET nombre_usuario = :usuario, email = :email
                 WHERE id_usuario = :id_usuario";
        $stmtU = $pdo->prepare($sqlU);
        $stmtU->execute([
            ':usuario' => $usuario,
            ':email' => $email,
            ':id_usuario' => $id_usuario
        ]);
    } else {
        $sqlU = "UPDATE usuarios 
                 SET nombre_usuario = :usuario, 
                     email = :email,
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

    // --- UPDATE maestros ---
    $sqlM = "UPDATE maestros SET 
                nombre = :nombre,
                apellido = :apellido,
                email = :email,
                telefono = :telefono,
                especialidad = :especialidad
             WHERE id_maestro = :id_maestro";

    $stmtM = $pdo->prepare($sqlM);
    $stmtM->execute([
        ':nombre' => $nombre,
        ':apellido' => $apellido,
        ':email' => $email,
        ':telefono' => $telefono,
        ':especialidad' => $especialidad,
        ':id_maestro' => $id_maestro
    ]);

    $pdo->commit();

    echo "<script>
        alert('Maestro actualizado correctamente.');
        window.location.href = '../Presentacion/Administrador/gestion_maestros.php';
    </script>";
    exit;

} catch (PDOException $e) {
    if ($pdo->inTransaction()) $pdo->rollBack();
    echo "<script>alert('Error: ".addslashes($e->getMessage())."'); window.history.back();</script>";
    exit;
}
