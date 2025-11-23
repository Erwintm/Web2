<?php
session_start();

if (!isset($_SESSION['tipo_usuario']) || $_SESSION['tipo_usuario'] != 'administrador') {
    header("Location: ../index.html");
    exit;
}

require_once '../../Datos/conexion.php';

// Obtener maestros
$maestros = $pdo->query("SELECT id_maestro, nombre, apellido FROM maestros")->fetchAll();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Agregar Asignatura</title>

    <link rel="stylesheet" href="../Diseño/Administrador/agregar_alumnos.css">
</head>
<body>

<header>
    <h1>Administración Escolar</h1>
    <nav>
        <a href="menu.php">Inicio</a>
        <a href="gestion_alumnos.php">Gestión Alumnos</a>
        <a href="gestion_maestros.php">Gestión Maestros</a>
        <a href="gestion_asignaturas.php">Gestión Materias</a>
         <a href="../../Negocio/logout.php"> Cerrar sesión</a>
    </nav>
</header>

<div class="container">

    <h2>Agregar Asignatura</h2>

    <form action="../../Negocio/agregar_asignatura.php" method="POST" class="form-alumno">

        <div class="form-group">
            <label>Nombre de la asignatura:</label>
            <input type="text" name="nombre" required>
        </div>

        <div class="form-group">
            <label>Código:</label>
            <input type="text" name="codigo" required>
        </div>

        <div class="form-group">
            <label>Maestro:</label>
            <select name="id_maestro" required>
                <option value="">Seleccione un maestro</option>
                <?php foreach ($maestros as $m): ?>
                    <option value="<?= $m['id_maestro'] ?>">
                        <?= $m['nombre'] . " " . $m['apellido'] ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group">
            <label>Horario:</label>
            <select name="horario" required>
                <option value="">Seleccione un horario</option>
                <option>8:00 am</option>
                <option>9:00 am</option>
                <option>10:00 am</option>
                <option>11:00 am</option>
                <option>12:00 pm</option>
                <option>1:00 pm</option>
                <option>2:00 pm</option>
            </select>
        </div>

        <div class="form-group">
            <label>Salón:</label>
            <select name="salon" required>
                <option value="">Seleccione un salón</option>
                <option>B15</option>
                <option>A1</option>
                <option>B5</option>
                <option>A11</option>
                <option>B7</option>
                <option>A2</option>
                <option>B8</option>
                <option>B10</option>
                <option>A5</option>
            </select>
        </div>

        <div class="form-group">
            <label>Capacidad:</label>
            <input type="number" name="capacidad" value="30" min="1">
        </div>

        <button type="submit" class="btn-guardar">Guardar Asignatura</button>

    </form>

</div>

<footer>
    <div class="footer-izquierda">
        <img src="../Imagenes/logo_tec.png" class="logo-footer" alt="Tec">
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
</html>
