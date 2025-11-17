<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    
    <nav>
        <h3>Materias cursadas</h3>
        <button>Regresar</button>
    </nav>
    <table id="tablaMaterias">
  <thead>
    <tr>
      <th>ID Materia</th>
      <th>Nombre</th>
      <th>Créditos</th>
      <th>Profesor</th>
    </tr>
  </thead>
  <tbody>
    
  </tbody>
</table>
</body>
<script>
    $(document).ready(function() {
            // Función para llenar el combo al cargar la página
            function cargarMaterias() {
                $.ajax({
                    url: 'obtener_materias.php',
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
                                    <td>${materia.idmateria}</td>
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