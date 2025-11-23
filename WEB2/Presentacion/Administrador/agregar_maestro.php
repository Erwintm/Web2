<?php
session_start();

if (!isset($_SESSION['tipo_usuario']) || $_SESSION['tipo_usuario'] != 'administrador') {
    header("Location: ../index.html");
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Agregar Maestro</title>
    <link rel="stylesheet" href="../Diseño/Administrador/agregar_alumnos.css">
</head>

<body>

<header>
    <h1>Administración Escolar</h1>
    <nav>
        <a href="menu.php">Inicio</a>
        <a href="gestion_alumnos.php">Gestión Alumnos</a>
        <a href="gestion_maestros.php">Gestión Maestros</a>
        <a href="gestion_asignaturas.php">Gestión Materias</a>
        <a href="../../Negocio/logout.php">Cerrar sesión</a>
    </nav>
</header>

<div class="container">
    <h2>Agregar Maestro</h2>

    <form action="../../Negocio/agregar_maestro.php" method="POST" class="form-alumno">

        <div class="form-group">
            <label>Nombre:</label>
            <input type="text" name="nombre" placeholder="Escribe el nombre del maestro" required>
        </div>

        <div class="form-group">
            <label>Apellido:</label>
            <input type="text" name="apellido" placeholder="Escribe los apellidos del maestro" required>
        </div>

        <div class="form-group">
            <label>Email institucional:</label>
            <input type="email" name="email" placeholder="Correo institucional válido" required>
        </div>

        <div class="form-group">
            <label>Teléfono:</label>
            <input type="text" name="telefono" placeholder="10 dígitos" minlength="10" maxlength="10">
        </div>

        <div class="form-group">
            <label for="especialidad">Especialidad:</label>
            <select name="especialidad" id="especialidad" required>
                <option value="">-- Selecciona una especialidad --</option>
                <option value="Sistemas Computacionales">Sistemas Computacionales</option>
                <option value="Matemáticas">Matemáticas</option>
                <option value="Física">Física</option>
                <option value="Electrónica">Electrónica</option>
                <option value="Administración">Administración</option>      
            </select>
        </div>

        <h3>Datos de acceso</h3>

        <div class="form-group">
            <label>Usuario:</label>
            <input type="text" name="usuario" placeholder="Nombre de usuario para iniciar sesión" required>
        </div>

        <div class="form-group">
            <label>Contraseña:</label>
            <input type="password" name="password" placeholder="Mínimo 6 caracteres" minlength="6" required>
        </div>

        <button type="submit" class="btn-guardar">Guardar Maestro</button>
    </form>
</div>

<footer>
<div class="footer-izquierda">
    <img src="../Imagenes/logo_tec.png" class="logo-footer">
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
