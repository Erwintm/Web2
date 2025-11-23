<?php
session_start();

if (!isset($_SESSION['tipo_usuario']) || $_SESSION['tipo_usuario'] != 'administrador') {
    header("Location: ../login.html");
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
       <a href="../../Negocio/logout.php"> Cerrar sesión</a>
    </nav>
</header>

<div class="container">
    <h2>Agregar Maestro</h2>

    <form action="../../Negocio/agregar_maestro.php" method="POST" class="form-alumno">

        <div class="form-group">
            <label>Nombre:</label>
            <input type="text" name="nombre" required>
        </div>

        <div class="form-group">
            <label>Apellido:</label>
            <input type="text" name="apellido" required>
        </div>

        <div class="form-group">
            <label>Email institucional:</label>
            <input type="email" name="email" required>
        </div>

        <div class="form-group">
            <label>Teléfono:</label>
            <input type="text" name="telefono">
        </div>

        <div class="form-group">
            <label for="especialidad">Especialidad:</label>
            <select name="especialidad" id="especialidad" class="form-select" required>
            <option value="">-- Selecciona una especialidad --</option>
            <option value="Sistemas Computacionales">Sistemas Computacionales</option>
            <option value="Matematicas">Matemáticas</option>
            <option value="Fisica">Física</option>
            <option value="Electronica">Electrónica</option>
            <option value="Administracion">Administración</option>      
        </select>
        </div>

        <h3>Datos de acceso</h3>

        <div class="form-group">
            <label>Usuario:</label>
            <input type="text" name="usuario" required>
        </div>

        <div class="form-group">
            <label>Contraseña:</label>
            <input type="password" name="password" required>
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
