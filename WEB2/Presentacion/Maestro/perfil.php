<?php
session_start();
// Validar sesión arriba como siempre
if (!isset($_SESSION['tipo_usuario']) || $_SESSION['tipo_usuario'] != 'maestro') {
    header("Location: ../../index.html");
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Mi Perfil</title>
    
    <link rel="stylesheet" href="../Diseño/Maestro/perfil.css">
    
    <link rel="stylesheet" href="../Diseño/Maestro/gestion_materias.css">
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
        <a href="../../Negocio/logout.php">Cerrar sesión</a>
    </nav>
</header>

<div class="contenedor-perfil">
    <h2 class="titulo-perfil">Información del Profesor</h2>
    
    <!-- AQUÍ SE CARGARÁ EL HTML QUE VIENE DEL PHP -->
    <div id="datos-perfil">
        Cargando información...
    </div>

    <div class="mensaje-info">
        Nota: Para actualizar sus datos personales, contacte al Administrador.
    </div>
</div>

<footer>
    <div class="footer-izquierda">
        <img src="../Imagenes/logo_tec.png" alt="Logo" class="logo-footer">
        <div class="footer-info">
            <p><strong>Instituto Tecnológico Superior de San Nicolás de Hidalgo</strong></p>
            <p><strong>Desarrollado por el área de informática</strong></p>
        </div>
    </div>
    <div class="footer-derecha">
        <p><strong>Sistema de Administración Escolar 2025</strong></p>
        <p><strong>Desarrollado por el área de informática</strong></p>
    </div>
</footer>

<script>
    
    function cargarDatosPerfil() {
        $.ajax({
            url: '../../Negocio/obtener_perfil_maestro.php',
            method: 'GET',
            success: function(respuesta) {
                
                $('#datos-perfil').html(respuesta);
            },
            error: function() {
                $('#datos-perfil').html('<p style="color:red">Error al cargar perfil</p>');
            }
        });
    }

    
    cargarDatosPerfil();
</script>

</body>
</html>