<?php
session_start();

// Solo administradores pueden entrar
if (!isset($_SESSION['tipo_usuario']) || $_SESSION['tipo_usuario'] != 'administrador') {
    header("Location: ../../login.html");
    exit;
}

require_once '../../Datos/conexion.php';
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestión de Maestros</title>
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
        <a href="perfil.php">Gestión Materias</a>
        <a href="../index.html">Cerrar sesión</a>
    </nav>
</header>

<div class="container">
    <h2>Gestión de Maestros</h2>

    <div class="filtros">

        <!-- Combo de especialidades -->
        <select id="filtroEspecialidad">
    <option value="">Todas las especialidades</option>
    <option value="Sistemas Computacionales">Sistemas Computacionales</option>
    <option value="Matemáticas">Matemáticas</option>
    <option value="Física">Física</option>
    <option value="Electrónica">Electrónica</option>
    <option value="Administración">Administración</option>
</select>
        <input type="text" id="buscar" placeholder="Buscar por nombre...">

        <button id="btnAgregar" onclick="location.href='agregar_maestro.php'">
            Agregar Maestro
        </button>
    </div>

    <table>
        <thead>
            <tr>
                <th>Nombre</th>
                <th>Apellidos</th>
                <th>Email</th>
                <th>Teléfono</th>
                <th>Especialidad</th>
                <th>Estado</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody id="tablaMaestros"></tbody>
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

function cargarMaestros() {
    let filtro = $('#buscar').val();
    let especialidad = $('#filtroEspecialidad').val();

    $.ajax({
        url: '../../Negocio/obtener_maestros.php',
        method: 'GET',
        data: { 
            filtro: filtro,
            especialidad: especialidad
        },
        success: function(response) {
            $('#tablaMaestros').html(response);
        }
    });
}

function eliminarMaestro(id) {
    if (!confirm("¿Seguro que deseas eliminar este maestro?")) return;

    $.ajax({
        url: '../../Negocio/eliminar_maestro.php',
        method: 'POST',
        data: { id_maestro: id },
        success: function(resp) {
            alert(resp);
            cargarMaestros();
        }
    });
}

$(document).ready(function() {
    cargarMaestros();

    $('#buscar').on('keyup', cargarMaestros);
    $('#filtroEspecialidad').on('change', cargarMaestros);
});
</script>

</body>
</html>
