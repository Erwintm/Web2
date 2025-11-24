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
    <h1>Bienvenido, Alumno</h1>
    <nav>
      <a href="menu.php">Inicio</a>
      <a href="calificaciones.php">Calificaciones</a>
      <a href="listaMaterias.php">Materias</a>
      <a href="perfil.php"> Mi Perfil</a>
     <a href="../../Negocio/logout.php"> Cerrar sesión</a>
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
