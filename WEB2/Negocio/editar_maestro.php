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

// Sanitizar entradas
$nombre = trim($_POST['nombre']);
$apellido = trim($_POST['apellido']);
$email = trim($_POST['email']);
$telefono = trim($_POST['telefono']);
$especialidad = trim($_POST['especialidad']);
$usuario = trim($_POST['usuario']);
$password = $_POST['password'];

/* ---------------- VALIDACIONES ---------------- */

if ($nombre === "" || $apellido === "" || $email === "" || $usuario === "" || $especialidad === "") {
    echo "<script>alert('Faltan campos obligatorios'); window.history.back();</script>";
    exit;
}

// Validar nombre
if (!preg_match("/^[a-zA-ZÁÉÍÓÚáéíóúÑñ ]{2,40}$/", $nombre)) {
    echo "<script>alert('El nombre solo debe contener letras y espacios.'); window.history.back();</script>";
    exit;
}

// Validar apellido
if (!preg_match("/^[a-zA-ZÁÉÍÓÚáéíóúÑñ ]{2,40}$/", $apellido)) {
    echo "<script>alert('El apellido solo debe contener letras y espacios.'); window.history.back();</script>";
    exit;
}

// Validar email institucional
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo "<script>alert('Correo electrónico inválido.'); window.history.back();</script>";
    exit;
}

// Validar teléfono (opcional, pero si lo escribe debe ser 10 dígitos)
if ($telefono !== "" && !preg_match("/^[0-9]{10}$/", $telefono)) {
    echo "<script>alert('El teléfono debe contener exactamente 10 números.'); window.history.back();</script>";
    exit;
}

// Validar usuario
if (!preg_match("/^[a-zA-Z0-9_]{4,30}$/", $usuario)) {
    echo "<script>alert('El usuario solo puede contener letras, números y guiones bajos (mínimo 4 caracteres).'); window.history.back();</script>";
    exit;
}

// Validar contraseña si la quiere cambiar
if ($password !== "" && strlen($password) < 6) {
    echo "<script>alert('La contraseña debe tener al menos 6 caracteres.'); window.history.back();</script>";
    exit;
}

/* ---------------- ACTUALIZACIÓN EN BD ---------------- */

try {
    $pdo->beginTransaction();

    // UPDATE usuarios
    if ($password == "") {
        // Sin cambiar contraseña
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
        // Con nueva contraseña
        $hash = password_hash($password, PASSWORD_DEFAULT);

        $sqlU = "UPDATE usuarios 
                 SET nombre_usuario = :usuario, 
                     email = :email,
                     contraseña = :password
                 WHERE id_usuario = :id_usuario";

        $stmtU = $pdo->prepare($sqlU);
        $stmtU->execute([
            ':usuario' => $usuario,
            ':email' => $email,
            ':password' => $hash,
            ':id_usuario' => $id_usuario
        ]);
    }

    // UPDATE maestros
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
