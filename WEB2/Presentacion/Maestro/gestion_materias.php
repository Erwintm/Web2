<?php
session_start();

// Validar que sea maestro
if (!isset($_SESSION['tipo_usuario']) || $_SESSION['tipo_usuario'] != 'maestro') {
    header("Location: ../../index.html");
    exit;
}

require_once '../../Datos/conexion.php';
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestión de Materias</title>
    <link rel="stylesheet" href="../Diseño/Maestro/gestion_materias.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>

<header>
    <h1>Panel del Maestro</h1>
    <nav>
        <a href="menu.php">Inicio</a>
        <a href="gestion_materias.php">Mis Materias</a>
        <a href="calificaciones.php">Calificaciones</a>
        <a href="perfil.php">Mi Perfil</a>
        <a href="../../Negocio/logout.php">Cerrar sesión</a>
    </nav>
</header>

<div class="container">
    <h2>Mis Materias</h2>

    <div class="filtros">
        <input type="text" id="buscar" placeholder="Buscar por nombre o código de materia...">
        
        <select id="filtroEstado">
            <option value="">Todos los estados</option>
            <option value="activa">Activas</option>
            <option value="inactiva">Inactivas</option>
        </select>
    </div>

    <table>
        <thead>
            <tr>
                <th>Código</th>
                <th>Nombre</th>
                <th>Créditos</th>
                <th>Horario</th>
                <th>Salón</th>
                <th>Alumnos Inscritos</th>
                <th>Estado</th>
            </tr>
        </thead>
        <tbody id="tablaMaterias"></tbody>
    </table>

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

</div>



<script>
function cargarMaterias() {
    let filtro = $('#buscar').val();
    let estado = $('#filtroEstado').val();

    $.ajax({
        url: '../../Negocio/obtener_materias.php',
        method: 'GET',
        data: { 
            filtro: filtro,
            estado: estado
        },
        success: function(response) {
            $('#tablaMaterias').html(response);
        },
        error: function() {
            $('#tablaMaterias').html('<tr><td colspan="7">Error al cargar las materias</td></tr>');
        }
    });
}

$(document).ready(function() {
    cargarMaterias(); // Cargar al inicio

    $('#buscar').on('keyup', cargarMaterias);
    $('#filtroEstado').on('change', cargarMaterias);
});
</script>

</body>
</html>
