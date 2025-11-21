<?php
session_start();


?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Agregar Alumno</title>

   
    <link rel="stylesheet" href="../Diseño/Administrador/agregar_alumnos.css">
</head>
<body>


<header>
    <h1>Administración Escolar</h1>
    <nav>
        <a href="menu.php">Inicio</a>
        <a href="gestion_alumnos.php">Gestión Alumnos</a>
         <a href="gestion_maestros.php">Gestion Maestros</a>
       <a href="gestion_asignaturas.php">Gestion Materias</a>
        <a href="../index.html">Cerrar sesión</a>
    </nav>
</header>
<div class="container">

    <h2>Agregar Alumno</h2>

    <form action="../../Negocio/agregar_alumno.php" method="POST" class="form-alumno">

        <div class="form-group">
            <label>Carrera:</label>
            <select name="carrera" id="carrera" required>
                <option value="">Seleccione</option>
                <option value="Sistemas Computacionales">Sistemas Computacionales</option>
                <option value="Industrial">Industrial</option>
                <option value="Mecatronica">Mecatronica</option>
                <option value="Gestion">Gestion</option>
                <option value="Gastronomia">Gastronomia</option>
                <option value="Fisica">Fisica</option>
            </select>
        </div>

        <div class="form-group">
            <label>Matrícula:</label>
            <input type="text" name="matricula" id="matricula" readonly required>
        </div>

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
            <label>Fecha de nacimiento:</label>
            <input type="date" name="fecha_nac" required>
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

        <button type="submit" class="btn-guardar">Guardar Alumno</button>

    </form>

</div>

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

<script>
// Genera matrícula dependiendo de la carrera
document.getElementById('carrera').addEventListener('change', function () {
    let inicial = this.value.charAt(0).toUpperCase();
    let random = Math.floor(Math.random() * 90000) + 10000;
    document.getElementById('matricula').value = inicial + random;
});
</script>

</body>
</html>
