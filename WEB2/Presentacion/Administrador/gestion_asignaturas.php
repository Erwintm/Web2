<?php
session_start();

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
    <title>Gestión de Asignaturas</title>
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
    <h2>Gestión de Asignaturas</h2>

    <div class="filtros">
        <select id="filtroMaestro">
            <option value="">Todos los maestros</option>
            <?php
            $q = $pdo->query("SELECT id_maestro, nombre, apellido FROM maestros");
            while ($m = $q->fetch(PDO::FETCH_ASSOC)) {
                echo "<option value='{$m['id_maestro']}'>
                        {$m['nombre']} {$m['apellido']}
                      </option>";
            }
            ?>
        </select>

        <input type="text" id="buscar" placeholder="Buscar asignatura...">

        <button id="btnAgregar" onclick="location.href='agregar_asignatura.php'">
            Agregar Asignatura
        </button>
    </div>

    <table>
        <thead>
            <tr>
                <th>Código</th>
                <th>Nombre</th>
                <th>Maestro</th>
                <th>Horario</th>
                <th>Salón</th>
                <th>Capacidad</th>
                <th>Estado</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody id="tablaAsignaturas"></tbody>
    </table>
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
    <p><strong>Desarrollado por informática</strong></p>
  </div>
</footer>

<script>
function cargarAsignaturas() {
    let filtro = $('#buscar').val();
    let maestro = $('#filtroMaestro').val();

    $.ajax({
        url: '../../Negocio/obtener_asignaturas.php',
        method: 'GET',
        data: { filtro: filtro, maestro: maestro },
        success: function(response) {
            $('#tablaAsignaturas').html(response);
        }
    });
}

function eliminarAsignatura(id) {
    if (!confirm("¿Seguro que deseas eliminar esta asignatura?")) return;

    $.ajax({
        url: '../../Negocio/eliminar_asignatura.php',
        method: 'POST',
        data: { id_asignatura: id },
        success: function(resp) {
            alert(resp);
            cargarAsignaturas();
        }
    });
}

$(document).ready(function() {
    cargarAsignaturas();
    $('#buscar').on('keyup', cargarAsignaturas);
    $('#filtroMaestro').on('change', cargarAsignaturas);
});
</script>

</body>
</html>
