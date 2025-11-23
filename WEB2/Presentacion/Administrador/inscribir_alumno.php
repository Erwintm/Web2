<?php
session_start();

if (!isset($_SESSION['tipo_usuario']) || $_SESSION['tipo_usuario'] != 'administrador') {
    header("Location: ../../login.html");
    exit;
}

require_once '../../Datos/conexion.php';

if (!isset($_GET['id_asignatura'])) {
    echo "Error: no se recibió asignatura";
    exit;
}

$id_asignatura = $_GET['id_asignatura'];

// Traer alumnos NO inscritos en esta asignatura
$sql = "SELECT * FROM alumnos 
        WHERE id_alumno NOT IN (
            SELECT id_alumno FROM inscripciones WHERE id_asignatura = :id_asig
        )";

$stmt = $pdo->prepare($sql);
$stmt->bindParam(':id_asig', $id_asignatura);
$stmt->execute();
$alumnos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang='es'>
<head>
    <meta charset='UTF-8'>
    <title>Inscribir Alumno</title>
    <link rel='stylesheet' href='../Diseño/Administrador/gestion_alumnos.css'>
</head>

<body>

<div class='container'>
    <h2>Inscribir Alumno</h2>

    <form action='../../Negocio/inscribir_alumno_guardar.php' method='POST'>
        <input type='hidden' name='id_asignatura' value='<?= $id_asignatura ?>'>

        <label>Selecciona un alumno:</label>
        <select name='id_alumno' required>
            <option value=''>-- Selecciona --</option>

            <?php foreach ($alumnos as $a): ?>
                <option value='<?= $a['id_alumno'] ?>'>
                    <?= $a['matricula']." - ".$a['nombre']." ".$a['apellido'] ?>
                </option>
            <?php endforeach; ?>
        </select>

        <br><br>
        <button type='submit' class='btn-agregar'>Inscribir</button>
    </form>
</div>

</body>
</html>
