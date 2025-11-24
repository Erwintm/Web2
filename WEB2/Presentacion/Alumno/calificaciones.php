<?php
session_start();

if (!isset($_SESSION['tipo_usuario']) || $_SESSION['tipo_usuario'] != 'alumno') {
    header("Location: ../../index.html");
    exit;
}

require_once '../../Datos/conexion.php';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Materias cursadas</title>
    <link rel="stylesheet" href="../Diseño/Alumno/calificaciones.css">
</head>
<body>
    
    <header>
    
    <nav>
      <a href="calificaciones.php">Calificaciones</a>
      <a href="listaMaterias.php">Materias</a>
      <a href="perfil.php"> Mi Perfil</a>
      <a href="../index.html"> Cerrar sesión</a>
    </nav>
  </header>

    <table id="tablaCalificaciones">
        <thead>
            <tr>
                <th>Materia</th>
                <th>U#1</th>
                <th>U#2</th>
                <th>U#3</th>
            </tr>
        </thead>
        <tbody></tbody>
    </table>

</body>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
$(document).ready(function() {

    function cargarCalificaciones() {
        $.ajax({
           url: '../../Negocio/Alumnos/obtener_calificaciones_alumno.php',
            type: 'POST',
            dataType: 'json',
            success: function(data) {
                $('#tablaCalificaciones tbody').empty();
                let materias = {};

                data.forEach(fila => {
                    const materia = fila.asignatura;

                    if (!materias[materia]) {
                        materias[materia] = {
                            u1: '0',
                            u2: '0',
                            u3: '0'
                        };
                    }

                    if (fila.tipo_evaluacion === 'Parcial 1') {
                        materias[materia].u1 = fila.calificacion ?? '0';
                    }
                    if (fila.tipo_evaluacion === 'Parcial 2') {
                        materias[materia].u2 = fila.calificacion ?? '0';
                    }

                    if (fila.tipo_evaluacion === 'Parcial 3') {
                        materias[materia].u3 = fila.calificacion ?? '0';
                    }
                });
                for (let materia in materias) {
                    let fila = `
                        <tr>
                            <td>${materia}</td>
                            <td>${materias[materia].u1}</td>
                            <td>${materias[materia].u2}</td>
                            <td>${materias[materia].u3}</td>
                        </tr>
                    `;
                    $('#tablaCalificaciones tbody').append(fila);
                }
            },
            error: function() {
                alert('Error al cargar las calificaciones.');
            }
        });
    }

    cargarCalificaciones();
});
</script>

</html>
