<?php
// Presentacion/Maestro/menu.php
session_start();

// Validar sesión
if (!isset($_SESSION['tipo_usuario']) || $_SESSION['tipo_usuario'] != 'maestro') {
    header("Location: ../../index.html");
    exit;
}


require_once '../../Datos/conexion.php';

require_once '../../Negocio/obtener_resumen_maestro.php';
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
      <a href="menu.php">Inicio</a>
      <a href="gestion_materias.php">Mis Materias</a>
      <a href="calificaciones.php">Calificaciones</a>
      <a href="perfil.php">Mi Perfil</a>
      <a href="../../Presentacion/index.html">Cerrar sesión</a>
    </nav>
  </header>

  <main>
    <h2>Inicio</h2>
    <!-- Mostramos el nombre del profesor -->
    <p>Hola, <strong><?php echo htmlspecialchars($nombre_maestro); ?></strong>, bienvenido al sistema de control escolar.</p>

    <section class="dashboard">
      <div class="card">
        <h3>Materias asignadas</h3>
        <!-- Variable calculada en Negocio -->
        <p style="font-size: 40px; font-weight: bold; color: #2c4c8a;">
            <?php echo $cant_materias; ?>
        </p>
      </div>
      <div class="card">
        <h3>Alumnos inscritos</h3>
        <p style="font-size: 40px; font-weight: bold; color: #2c4c8a;">
            <?php echo $cant_alumnos; ?>
        </p>
      </div>
      <div class="card">
        <h3>Calificaciones registradas</h3>
        <p style="font-size: 40px; font-weight: bold; color: #2c4c8a;">
            <?php echo $cant_calificaciones; ?>
        </p>
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