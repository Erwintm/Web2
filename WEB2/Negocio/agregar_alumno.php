<?php
// Negocio/agregar_alumno.php
session_start();

// restringir acceso
if (!isset($_SESSION['tipo_usuario']) || $_SESSION['tipo_usuario'] !== 'administrador') {
    header("Location: ../Presentacion/index.php");
    exit;
}

// ajustar la ruta a tu conexion.php
require_once __DIR__ . '/../Datos/conexion.php'; // asegura que $pdo esté definido

// Validar método
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: ../Presentacion/Administrador/agregar_alumno.php");
    exit;
}

// Recibir y limpiar datos
$matricula = trim($_POST['matricula'] ?? '');
$nombre = trim($_POST['nombre'] ?? '');
$apellido = trim($_POST['apellido'] ?? '');
$email = trim($_POST['email'] ?? '');
$telefono = trim($_POST['telefono'] ?? '');
$fecha_nacimiento = trim($_POST['fecha_nac'] ?? '');
$carrera = trim($_POST['carrera'] ?? '');
$usuario = trim($_POST['usuario'] ?? '');
$password = $_POST['password'] ?? ''; // no trim para password

// Validaciones mínimas
$errors = [];
if ($matricula === '' || $nombre === '' || $apellido === '' || $email === '' || $fecha_nacimiento === '' || $carrera === '' || $usuario === '' || $password === '') {
    $errors[] = "Faltan campos requeridos.";
}

if (!empty($errors)) {
    echo "<script>alert('".implode("\\n", $errors)."'); window.history.back();</script>";
    exit;
}

try {
    // Iniciar transacción
    $pdo->beginTransaction();

    // 1) Insertar en usuarios (contraseña con SHA2 en MySQL)
    $sqlUser = "INSERT INTO usuarios (nombre_usuario, contraseña, email, tipo_usuario)
                VALUES (:usuario, SHA2(:password,256), :email, 'alumno')";
    $stmtUser = $pdo->prepare($sqlUser);
    $stmtUser->execute([
        ':usuario' => $usuario,
        ':password' => $password,
        ':email' => $email
    ]);

    $id_usuario = $pdo->lastInsertId();

    // 2) Insertar en alumnos
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

    // Confirmar transacción
    $pdo->commit();

    // Redirigir con mensaje (ajusta la URL según tu estructura)
    echo "<script>
            alert('Alumno registrado correctamente. Usuario: {$usuario}');
            window.location.href = '../Presentacion/Administrador/gestion_alumnos.php';
          </script>";
    exit;

} catch (PDOException $e) {
    // Revertir y mostrar error (en producción loguea en vez de mostrar)
    if ($pdo->inTransaction()) $pdo->rollBack();
    echo "<script>alert('Error: ".addslashes($e->getMessage())."'); window.history.back();</script>";
    exit;
}
