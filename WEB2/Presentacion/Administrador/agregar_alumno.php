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
    <title>Agregar Alumno</title>
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
            <input type="text" name="nombre" 
                   required 
                   pattern="[A-Za-zÁÉÍÓÚáéíóúñÑ ]{2,40}"
                   title="Solo letras y espacios, mínimo 2 caracteres">
        </div>

        <div class="form-group">
            <label>Apellido:</label>
            <input type="text" name="apellido" 
                   required
                   pattern="[A-Za-zÁÉÍÓÚáéíóúñÑ ]{2,40}"
                   title="Solo letras y espacios, mínimo 2 caracteres">
        </div>

        <div class="form-group">
            <label>Email institucional:</label>
            <input type="email" name="email" 
                   required 
                   pattern="[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$"
                   title="Ingrese un correo válido">
        </div>

        <div class="form-group">
            <label>Teléfono:</label>
            <input type="text" name="telefono"
                   pattern="[0-9]{10}"
                   title="Debe contener 10 dígitos">
        </div>

        <div class="form-group">
            <label>Fecha de nacimiento:</label>
            <input type="date" name="fecha_nac" required>
        </div>

        <h3>Datos de acceso</h3>

        <div class="form-group">
            <label>Usuario:</label>
            <input type="text" name="usuario"
                   required
                   pattern="[A-Za-z0-9._-]{4,20}"
                   title="Letras, números, guión o punto. Mínimo 4 caracteres">
        </div>

        <div class="form-group">
            <label>Contraseña:</label>
            <input type="password" name="password"
                   required
                   minlength="6"
                   title="Mínimo 6 caracteres">
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
// Genera matrícula automáticamente
document.getElementById('carrera').addEventListener('change', function () {
    let inicial = this.value.charAt(0).toUpperCase();
    let random = Math.floor(Math.random() * 90000) + 10000;
    document.getElementById('matricula').value = inicial + random;
});
</script>

</body>
</html>
