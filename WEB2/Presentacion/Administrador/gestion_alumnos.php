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

   
   <!--  <header>
        <div class="logo">Administración Escolar</div>
        <nav>
            <ul>
                <li><a href="menu.php">Inicio</a></li>
                <li><a href="gestion_alumnos.php" >Gestión Alumnos</a></li>
                <li><a href="gestion_maestros.php">Gestión Maestros</a></li>
                <li><a href="gestion_materias.php">Gestión Materias</a></li>
                <li><a href="../index.html">Cerrar sesión</a></li>
            </ul>
        </nav>
    </header>
 -->
    <!-- CONTENIDO -->
    <div class="container">
        <h2>Gestión de Alumnos</h2>

        <input type="text" id="buscar" placeholder="Buscar por nombre de alumno...">

        <table>
            <thead>
                <tr>
                    <th>Matricula</th>
                    <th>Nombre</th>
                    <th>Apellido</th>
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

    <!-- 
    <footer>
        <div class="footer-left">
            <img src="../Imagenes/logo_tec.png" alt="Logo Instituto" class="logo-footer">
            <div class="footer-info">
                <p><strong>Instituto Tecnológico Superior de San Nicolás de Hidalgo</strong></p>
                <p>Teléfono: 445 192 8872</p>
            </div>
        </div>
        <div class="footer-right">
            <p>Sistema de Administración Escolar © 2025</p>
            <p>Desarrollado por el área de informática</p>
        </div>
    </footer>
     -->

  
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
