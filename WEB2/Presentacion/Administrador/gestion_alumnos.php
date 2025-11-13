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
    <title>Gestión de Alumnos</title>
    <link rel="stylesheet" href="../Diseño/Administrador/gestion_alumnos.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
</head>
<body>

<header>
    <h1>Adminitracion Escolar</h1>
    <nav>
     <a href="menu.php">Inicio</a>
      <a href="gestion_alumnos.php">Gestion Alumnos</a>
      <a href="listaMaterias.php">Gestion Maestros</a>
      <a href="perfil.php">Gestion Materias</a>
      <a href="../index.html"> Cerrar sesión</a>
    </nav>
  </header>   

    <div class="container">
        <h2>Gestión de Alumnos</h2>

        <input type="text" id="buscar" placeholder="Buscar por nombre de alumno...">

        <table>
            <thead>
                <tr>
                    <th>Matricula</th>
                    <th>Nombre</th>
                    <th>Apellidos</th>
                    <th>Email</th>
                    <th>Teléfono</th>
                    <th>Fecha de nacimiento</th>
                    <th>Estado</th>
                </tr>
            </thead>
            <tbody id="tablaAlumnos">
            
            </tbody>
        </table>
    </div>

  
    <footer>
  <div class="footer-izquierda">
    <img src="../Imagenes/logo_tec.png" alt="Logo Instituto" class="logo-footer">
    
    <div class="footer-info">
      <p><strong>Instituto Tecnológico Superior de San Nicolás de Hidalgo</strong></p>
       <p><strong>Correo:instecsanico@gmail.com</strong></p>
    </div>
  </div>

  <div class="footer-derecha">
    <p><strong>Sistema de Administración Escolar 2025</strong></p>
    <p><strong>Desarrollado por el área de informática</strong></p>
  </div>
</footer>
     

  
    <script>
        function cargarAlumnos(filtro = '') {
            $.ajax({
                url: '../../Negocio/obtener_alumnos.php',
                method: 'GET',
                data: { filtro: filtro },
                success: function(response) {
                    $('#tablaAlumnos').html(response);
                }
            });
        }

        $(document).ready(function() {
            cargarAlumnos(); // Cargar todos al inicio

            $('#buscar').on('keyup', function() {
                let filtro = $(this).val();
                cargarAlumnos(filtro);
            });
        });
    </script>

</body>
</html>
