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
    <link rel="stylesheet" href="../Diseño/Alumno/lista_materias.css">
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

    <table id="tablaMaterias">
        <thead>
            <tr>
                <th>Clave</th>
                <th>Nombre</th>
                <th>Créditos</th>
                <th>Horario</th>
                <th>Salon</th>
                <th>Profesor</th>
            </tr>
        </thead>
        <tbody></tbody>
    </table>

</body>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
$(document).ready(function() {

    function cargarMaterias() {
        $.ajax({
           url: '../../Negocio/Alumnos/obtener_materias_alumnos.php',
            type: 'POST',
            dataType: 'json',
            success: function(data) {
                $('#tablaMaterias tbody').empty();
                
                if (data.length > 0) {
                    $.each(data, function(index, materia) {
                        let fila = `
                        <tr>
                            <td>${materia.codigo}</td>
                            <td>${materia.nombre}</td>
                            <td>${materia.creditos}</td>
                            <td>${materia.horario}</td>
                            <td>${materia.salon}</td>
                            <td>${materia.profesor}</td>
                        </tr>`;
                        $('#tablaMaterias tbody').append(fila);
                    });
                } else {
                    $('#tablaMaterias tbody').append('<tr><td colspan="6">No hay materias registradas.</td></tr>');
                }
            },
            error: function() {
                alert('Error al cargar el alumno.');
            }
        });
    }

    cargarMaterias();
});
</script>

</html>
