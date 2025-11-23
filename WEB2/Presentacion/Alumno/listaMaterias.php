<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    
    <nav>
        <h3>Materias cursadas</h3>
        <a href="../../Negocio/logout.php"> Cerrar sesión</a>
        <a href="menu.php">
            <button>Regresar</button>
        </a>
    </nav>
    <table id="tablaMaterias">
  <thead>
    <tr>
      <th>ID Materia</th>
      <th>Nombre</th>
      <th>Créditos</th>
      <th>Horario</th>
      <th>Salon</th>
      <th>Profesor</th>
    </tr>
  </thead>
  <tbody>
    
  </tbody>
</table>
</body>
<script>
    $(document).ready(function() {
            
            function cargarMaterias() {
                $.ajax({
                    url: '../../Negocio/obtener_materias_alumnos.php',
                    type: 'POST',
                    dataType: 'json',
                    success: function(data) {
                        // Limpiar la tabla antes de llenarlo
                         $('#tablaMaterias tbody').empty();
                        
                        if (data.length > 0) {
                             // Llenar la tabla con las materias
                            $.each(data, function(index, materia) {
                                let fila = `
                                <tr>
                                    <td>${materia.id_asignatura}</td>
                                    <td>${materia.nombre}</td>
                                    <td>${materia.creditos}</td>
                                    <td>${materia.horario}</td>
                                    <td>${materia.salon}</td>
                                    <td>${materia.profesor}</td>
                                </tr>
                                `;
                            $('#tablaMaterias tbody').append(fila);
                            });
                        } else {
                            $('#tablaMaterias tbody').append('<tr><td colspan="4">No hay materias registradas.</td></tr>');
                        }
                    },
                    error: function() {
                        alert('Error al cargar el alumno.');
                    }
                });
            }

            // Llamar a la función al cargar la página
            cargarMaterias();

            
        });
</script>
</html>