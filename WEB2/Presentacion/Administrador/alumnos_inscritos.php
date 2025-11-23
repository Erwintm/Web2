<?php
session_start();

if (!isset($_SESSION['tipo_usuario']) || $_SESSION['tipo_usuario'] != 'administrador') {
    header("Location: ../../login.html");
    exit;
}

require_once '../../Datos/conexion.php';

if (!isset($_GET['id_asignatura'])) {
    echo "Error: No se recibió la asignatura.";
    exit;
}

$id_asignatura = $_GET['id_asignatura'];

// Obtener datos de la asignatura
$stmt = $pdo->prepare("SELECT a.*, m.nombre AS nom_m, m.apellido AS ape_m
                       FROM asignaturas a
                       INNER JOIN maestros m ON m.id_maestro = a.id_maestro
                       WHERE id_asignatura = ?");
$stmt->execute([$id_asignatura]);
$asignatura = $stmt->fetch(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Alumnos Inscritos</title>

    <link rel="stylesheet" href="../Diseño/Administrador/gestion_alumnos.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<body>

<header>
    <h1>Administración Escolar</h1>
    <nav>
        <a href="menu.php">Inicio</a>
        <a href="gestion_asignaturas.php">Volver</a>
    </nav>
</header>

<div class="container">

    <h2>Alumnos inscritos en: <?= htmlspecialchars($asignatura['nombre']) ?></h2>
    <p><strong>Maestro:</strong> <?= $asignatura['nom_m'] . " " . $asignatura['ape_m'] ?></p>
    <p><strong>Horario:</strong> <?= $asignatura['horario'] ?></p>

    <hr>

    <button onclick="location.href='inscribir_alumno.php?id_asignatura=<?= $id_asignatura ?>'"
            class="btn-agregar">
        Inscribir Alumno
    </button>

    <table>
        <thead>
            <tr>
                <th>Matrícula</th>
                <th>Alumno</th>
                <th>Email</th>
                <th>Acciones</th>
            </tr>
        </thead>

        <tbody id="tablaInscritos"></tbody>
    </table>
</div>

<script>
function cargarAlumnos() {
    $.ajax({
        url: '../../Negocio/obtener_inscritos.php',
        method: 'GET',
        data: { id_asignatura: <?= $id_asignatura ?> },
        success: function(response) {
            $('#tablaInscritos').html(response);
        }
    });
}

function darDeBaja(id_inscripcion) {
    if (!confirm("¿Seguro que deseas dar de baja a este alumno?")) return;

    $.ajax({
        url: '../../Negocio/baja_alumno_asignatura.php',
        method: 'POST',
        data: { id_inscripcion: id_inscripcion },
        success: function(resp) {
            alert(resp);
            cargarAlumnos();
        }
    });
}

$(document).ready(function() {
    cargarAlumnos();
});
</script>

</body>
</html>
