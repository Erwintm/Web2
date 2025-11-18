<?php
session_start();

// Validar sesión

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Calificaciones</title>
    <link rel="stylesheet" href="../Diseño/Maestro/panel.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>

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
    <title>Calificaciones - Maestro</title>
    <link rel="stylesheet" href="../Diseño/Maestro/panel.css">
    <link rel="stylesheet" href="../Diseño/Maestro/calificaciones_maestro.css">
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
        <a href="../../Presentacion/index.html">Cerrar sesión</a>
    </nav>
</header>

<div class="container">
    <div class="panel">
        <h2>Registro de Calificaciones</h2>

        <div class="filtros">
            <input type="text" id="buscar" placeholder="Buscar por alumno...">
            <select id="filtroEstado">
                <option value="">Todas las materias</option>
                <!-- Las materias se cargarán por JS -->
            </select>
        </div>

        <table id="tabla-calificaciones">
            <thead>
                <tr>
                    <th>Alumno</th>
                    <th>Materia</th>
                    <th>Calificación</th>
                    <th>Promedio</th>
                    <th>Acción</th>
                </tr>
            </thead>
            <tbody>
                <!-- Filas cargadas por AJAX -->
            </tbody>
        </table>
    </div>
</div>

<footer>
    <div class="footer-izquierda">
        <img src="../Imagenes/logo_tec.png" alt="Logo Instituto" class="logo-footer">
        <div class="footer-info">
            <p><strong>Instituto Tecnológico Superior</strong></p>
            <p><strong>Correo: instecsanico@gmail.com</strong></p>
        </div>
    </div>
    <div class="footer-derecha">
        <p><strong>Sistema de Administración Escolar 2025</strong></p>
    </div>
</footer>

<script>
function cargarMateriasEnFiltro() {
    $.ajax({
        url: '../../Negocio/obtener_materias.php',
        method: 'GET',
        dataType: 'html',
        success: function(response) {
            // Esperamos que el backend devuelva <option>...</option> o filas; si devuelve filas, transformamos.
            $('#filtroMateria').append(response);
        },
        error: function() {
            console.error('Error cargando materias');
        }
    });
}

function cargarAlumnosMaterias() {
    let filtro = $('#buscar').val();
    let id_materia = $('#filtroEstado').val();

    $.ajax({
        url: '../../Negocio/obtener_alumnos_materia.php',
        method: 'GET',
        data: {
            filtro: filtro,
            id_materia: id_materia
        },
        success: function(data) {
            $('#tabla-calificaciones tbody').html(data);
        },
        error: function() {
            $('#tabla-calificaciones tbody').html('<tr><td colspan="5">Error al cargar los registros</td></tr>');
        }
    });
}

$(document).ready(function() {
    cargarMateriasEnFiltro();
    cargarAlumnosMaterias();

    $('#buscar').on('keyup', function() { cargarAlumnosMaterias(); });
    $('#filtroEstado').on('change', function() { cargarAlumnosMaterias(); });

    // Evento para registrar calificación
    $('#tabla-calificaciones').on('click', '.btn-registrar', function() {
        var fila = $(this).closest('tr');
        var id_materia = fila.data('id-materia');
        var id_alumno = fila.data('id-alumno');
        var calificacion = fila.find('.input-calificacion').val();

        if (!calificacion || isNaN(calificacion)) {
            alert('Ingrese una calificación válida');
            return;
        }

        $.ajax({
            url: '../../Negocio/registrar_calificaciones.php',
            method: 'POST',
            data: {
                id_materia: id_materia,
                id_alumno: id_alumno,
                calificacion: calificacion
            },
            success: function(respuesta) {
                // reemplazamos la columna de resultado o mostramos mensaje
                fila.find('.resultado').html(respuesta);
                cargarAlumnosMaterias();
            },
            error: function() {
                alert('Error al registrar la calificación');
            }
        });
    });
});
</script>

</body>
</html>

    <script>
    $(document).ready(function() {
        // Cargar alumnos y materias asignadas al maestro
        function cargarCalificaciones() {
            $.ajax({
                url: '../../Negocio/obtener_alumnos_materia.php',
                method: 'GET',
                success: function(data) {
                    $('#tabla-calificaciones tbody').html(data);
                }
            });
        }

        cargarCalificaciones();

        // Registrar calificación
        $('#tabla-calificaciones').on('click', '.btn-registrar', function() {
            var fila = $(this).closest('tr');
            var id_materia = fila.data('id-materia');
            var id_alumno = fila.data('id-alumno');
            var calificacion = fila.find('.input-calificacion').val();

            $.ajax({
                url: '../../Negocio/registrar_calificaciones.php',
                method: 'POST',
                data: {
                    id_materia: id_materia,
                    id_alumno: id_alumno,
                    calificacion: calificacion
                },
                success: function(respuesta) {
                    // Actualiza la fila con el resultado
                    fila.find('.resultado').html(respuesta);
                    cargarCalificaciones(); // Refresca la tabla
                }
            });
        });
    });
    </script>
</body>
</html>
