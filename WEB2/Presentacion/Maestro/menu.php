<?php
session_start();

// Validar sesión

?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Panel del Maestro</title>
  <link rel="stylesheet" href="../Diseño/Maestro/panel.css">
</head>
<body>
  <header>
    <h1>Panel del Maestro</h1>
    <nav>
      <a href="gestion_materias.php">Mis Materias</a>
      <a href="calificaciones.php">Calificaciones</a>
      <a href="perfil.php">Mi Perfil</a>
      <a href="../../Presentacion/index.html">Cerrar sesión</a>
    </nav>
  </header>

  <main>
    <h2>Inicio</h2>
    <p>Hola, bienvenido al sistema de control escolar.</p>

    <section class="dashboard">
      <div class="card">
        <h3>Materias asignadas</h3>
        <p><!-- Aquí puedes mostrar el número de materias --></p>
      </div>
      <div class="card">
        <h3>Alumnos inscritos</h3>
        <p><!-- Aquí puedes mostrar el número de alumnos --></p>
      </div>
      <div class="card">
        <h3>Calificaciones pendientes</h3>
        <p><!-- Aquí puedes mostrar el número de calificaciones --></p>
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
