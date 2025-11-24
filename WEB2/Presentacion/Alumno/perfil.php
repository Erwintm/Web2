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
    <link rel="stylesheet" href="../Diseño/Alumno/perfil.css">
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

    <table id="tablaPerfil">
        <thead>
            <tr>
                <th>Alumno</th>
                <th>Matrícula</th>
                <th>Estado de inscripción</th>
                <th>Carrera</th>
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
           url: '../../Negocio/Alumnos/obtener_perfil.php',
            type: 'POST',
            dataType: 'json',
            success: function(data) {
                $('#tablaPerfil tbody').empty();
                
                if (data.length > 0) {
                    $.each(data, function(index, perfil) {
                        let fila = `
                        <tr>
                            <td>${perfil.alumno}</td>
                            <td>${perfil.matricula}</td>
                            <td>${perfil.estado}</td>
                            <td>${perfil.carrera}</td>
                            
                        </tr>`;
                        $('#tablaPerfil tbody').append(fila);
                    });
                } else {
                    $('#tablaPerfil tbody').append('<tr><td colspan="6">No hay perfil.</td></tr>');
                }
            },
            error: function() {
                alert('Error al cargar el perfil.');
            }
        });
    }

    cargarMaterias();
});
</script>

</html>
