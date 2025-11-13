<?php
session_start();
require_once '../Datos/conexion.php';

if (!isset($_SESSION['tipo_usuario']) || $_SESSION['tipo_usuario'] != 'alumno') {
    header("Location: ../index.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario = $_POST['usuario'] ?? '';
    $password = $_POST['password'] ?? '';

    if (empty($usuario) || empty($password)) {
        echo "<script>alert('Por favor llena todos los campos'); window.history.back();</script>";
        exit;
    }

    $sql = "SELECT * FROM usuarios WHERE nombre_usuario = :usuario AND contraseña = :password";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':usuario', $usuario);
    $stmt->bindParam(':password', $password);
    $stmt->execute();

    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        $_SESSION['id_usuario'] = $user['id_usuario'];
        $_SESSION['nombre_usuario'] = $user['nombre_usuario'];
        $_SESSION['tipo_usuario'] = $user['tipo_usuario'];

        // Redirigir según el tipo de usuario
        switch ($user['tipo_usuario']) {
            case 'administrador':
                header("Location: ../Presentacion/Administrador/menu.php");
                break;
            case 'maestro':
                header("Location: ../");
                break;
            case 'alumno':
                header("Location: ../Presentacion/Alumno/menu.php");
                break;
            default:
                echo "<script>alert('Tipo de usuario no reconocido');</script>";
        }
        exit;
    } else {
        echo "<script>alert('Usuario o contraseña incorrectos'); window.history.back();</script>";
        exit;
    }
}
?>
