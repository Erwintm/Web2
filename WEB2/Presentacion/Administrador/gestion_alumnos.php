<?php
session_start();

// Solo administradores pueden entrar
if (!isset($_SESSION['tipo_usuario']) || $_SESSION['tipo_usuario'] != 'administrador') {
    header("Location: ../index.html");
    exit;
}

require_once '../../Datos/conexion.php';
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestión de Alumnos</title>
    <link rel="stylesheet" href="../Diseño/Administrador/gestion_alumnos.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
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
    <h2>Gestión de Alumnos</h2>

    <div class="filtros">
        <select id="filtroCarrera">
            <option value="">Todas las carreras</option>
            <option value="Sistemas Computacionales">Sistemas Computaciones</option>
            <option value="Industrial">Industrial</option>
            <option value="Mecatronica">Mecatrónica</option>
            <option value="Gestion">Gestión</option>
             <option value="Gastronomia">Gastronomia</option>
             <option value="Fisica">Fisica</option>
        
        
        </select>

        <input type="text" id="buscar" placeholder="Buscar por nombre de alumno...">

        <button id="btnAgregar" onclick="location.href='agregar_alumno.php'">
            Agregar Alumno
        </button>
    </div>

    <table>
        <thead>
            <tr>
                <th>Matricula</th>
                <th>Nombre</th>
                <th>Apellidos</th>
                <th>Email</th>
                <th>Teléfono</th>
                <th>Fecha de nacimiento</th>
                <th>Estado</th>
                  <th>Acciones</th> 
            </tr>
        </thead>
        <tbody id="tablaAlumnos"></tbody>
    </table>
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
function cargarAlumnos() {
    let filtro = $('#buscar').val();
    let carrera = $('#filtroCarrera').val();

    $.ajax({
        url: '../../Negocio/obtener_alumnos.php',
        method: 'GET',
        data: { 
            filtro: filtro,
            carrera: carrera
        },
        success: function(response) {
            $('#tablaAlumnos').html(response);
        }
    });
}

function eliminarAlumno(matricula) {
    if (!confirm("¿Seguro que deseas eliminar este alumno?")) return;

    $.ajax({
        url: '../../Negocio/eliminar_alumno.php',
        method: 'POST',
        data: { matricula: matricula },
        success: function(resp) {
            alert(resp);
            cargarAlumnos(); 
        }
    });
}

$(document).ready(function() {
    cargarAlumnos(); 

    $('#buscar').on('keyup', cargarAlumnos);
    $('#filtroCarrera').on('change', cargarAlumnos);
});
</script>

</body>
</html>
