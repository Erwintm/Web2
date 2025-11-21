<?php
session_start();

if (!isset($_SESSION['tipo_usuario']) || $_SESSION['tipo_usuario'] != 'administrador') {
    header("Location: ../../login.html");
    exit;
}

require_once '../../Datos/conexion.php';

// Validar ID
if (!isset($_GET['id'])) {
    echo "<script>alert('ID de asignatura no recibido'); window.location.href='gestion_asignaturas.php';</script>";
    exit;
}

$id = $_GET['id'];

// Obtener asignatura a editar
$stmt = $pdo->prepare("SELECT * FROM asignaturas WHERE id_asignatura = ?");
$stmt->execute([$id]);
$asig = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$asig) {
    echo "<script>alert('Asignatura no encontrada'); window.location.href='gestion_asignaturas.php';</script>";
    exit;
}

// Obtener lista de maestros
$maestros = $pdo->query("SELECT id_maestro, nombre, apellido FROM maestros")->fetchAll();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Asignatura</title>

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
        <a href="../index.html">Cerrar sesión</a>
    </nav>
</header>

<div class="container">

    <h2>Editar Asignatura</h2>

    <form action="../../Negocio/editar_asignatura.php" method="POST" class="form-alumno">

        <input type="hidden" name="id_asignatura" value="<?= $asig['id_asignatura'] ?>">

        <div class="form-group">
            <label>Nombre de la asignatura:</label>
            <input type="text" name="nombre" value="<?= $asig['nombre'] ?>" required>
        </div>

        <div class="form-group">
            <label>Código:</label>
            <input type="text" name="codigo" value="<?= $asig['codigo'] ?>" required>
        </div>

        <div class="form-group">
            <label>Maestro:</label>
            <select name="id_maestro" required>
                <option value="">Seleccione un maestro</option>
                <?php foreach ($maestros as $m): ?>
                    <option value="<?= $m['id_maestro'] ?>"
                        <?= ($asig['id_maestro'] == $m['id_maestro']) ? 'selected' : '' ?>>
                        <?= $m['nombre'] . " " . $m['apellido'] ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group">
            <label>Horario:</label>
            <select name="horario" required>
                <option value="">Seleccione un horario</option>
                <?php 
                $horas = ["8:00 am","9:00 am","10:00 am","11:00 am","12:00 pm","1:00 pm","2:00 pm"];
                foreach ($horas as $h):
                ?>
                    <option value="<?= $h ?>" <?= ($asig['horario'] == $h) ? 'selected' : '' ?>>
                        <?= $h ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group">
            <label>Salón:</label>
            <select name="salon" required>
                <?php 
                $salones = ["B15","A1","B5","A11","B7","A2","B8","B10","A5"];
                ?>
                <option value="">Seleccione un salón</option>
                <?php foreach ($salones as $s): ?>
                    <option value="<?= $s ?>" <?= ($asig['salon'] == $s) ? 'selected' : '' ?>>
                        <?= $s ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group">
            <label>Capacidad:</label>
            <input type="number" name="capacidad" value="<?= $asig['capacidad'] ?>" min="1">
        </div>

        <button type="submit" class="btn-guardar">Guardar Cambios</button>

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
