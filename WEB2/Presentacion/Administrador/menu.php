<?php
session_start();

// 1. Verificación de Sesión (Seguridad)
if (!isset($_SESSION['tipo_usuario']) || $_SESSION['tipo_usuario'] != 'administrador') {
    header("Location: ../index.html");
    exit;
}

// 2. Conexión a la Base de Datos
require_once '../../Datos/conexion.php';

// --- 3. OBTENCIÓN DE CONTEOS REALES ---

// Contar Alumnos
$stmt_alumnos = $pdo->query("SELECT COUNT(*) FROM alumnos");
$total_alumnos = $stmt_alumnos->fetchColumn();

// Contar Maestros
$stmt_maestros = $pdo->query("SELECT COUNT(*) FROM maestros");
$total_maestros = $stmt_maestros->fetchColumn();

// Contar Asignaturas (Materias activas)
$stmt_asignaturas = $pdo->query("SELECT COUNT(*) FROM asignaturas");
$total_asignaturas = $stmt_asignaturas->fetchColumn();

// Contar Inscripciones
$stmt_inscripciones = $pdo->query("SELECT COUNT(*) FROM inscripciones");
$total_inscripciones = $stmt_inscripciones->fetchColumn();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Panel de Administración</title>
    <link rel="stylesheet" href="../Diseño/Administrador/panel.css">
</head>
<body>
    <header>
        <h1>Administración Escolar</h1>
        <nav>
            <a href="gestion_alumnos.php">Gestión Alumnos</a>
            <a href="gestion_maestros.php">Gestión Maestros</a>
            <a href="gestion_asignaturas.php">Gestión Materias</a>
            <a href="../../Negocio/logout.php"> Cerrar sesión</a>
        </nav>
    </header>

    <main>
        <h2>Inicio</h2>
        <p>Hola, bienvenido al sistema de control escolar.</p>

        <section class="dashboard">
            
            <div class="card">
                <h3>Alumnos registrados</h3>
                <p><?= htmlspecialchars($total_alumnos) ?></p>
            </div>
            
            <div class="card">
                <h3>Maestros</h3>
                <p><?= htmlspecialchars($total_maestros) ?></p>
            </div>
            
            <div class="card">
                <h3>Materias activas</h3>
                <p><?= htmlspecialchars($total_asignaturas) ?></p>
            </div>
            
            <div class="card">
                <h3>Inscripciones</h3>
                <p><?= htmlspecialchars($total_inscripciones) ?></p>
            </div>
        </section>
    </main>

    <footer>
        <div class="footer-izquierda">
            <img src="../Imagenes/logo_tec.png" alt="Logo Instituto" class="logo-footer">
            
            <div class="footer-info">
                <p><strong>Instituto Tecnológico Superior de San Nicolás de Hidalgo</strong></p>
                <p><strong>Correo: instecsanico@gmail.com</strong></p>
            </div>
        </div>

        <div class="footer-derecha">
            <p><strong>Sistema de Administración Escolar 2025</strong></p>
            <p><strong>Desarrollado por el área de informática</strong></p>
        </div>
    </footer>
</body>
</html>