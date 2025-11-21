<?php
require_once '../Datos/conexion.php';

$nombre = trim($_POST['nombre']);
$codigo = trim($_POST['codigo']);
$id_maestro = $_POST['id_maestro'];
$horario = trim($_POST['horario']);
$salon = trim($_POST['salon']);
$capacidad = trim($_POST['capacidad']);

if ($nombre === '' || $codigo === '' || $id_maestro === '') {
    echo "<script>alert('Faltan campos obligatorios'); window.history.back();</script>";
    exit;
}

try {
    $sql = "INSERT INTO asignaturas
            (nombre, codigo, id_maestro, horario, salon, capacidad)
            VALUES (:nombre, :codigo, :id_maestro, :horario, :salon, :capacidad)";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':nombre' => $nombre,
        ':codigo' => $codigo,
        ':id_maestro' => $id_maestro,
        ':horario' => $horario,
        ':salon' => $salon,
        ':capacidad' => $capacidad
    ]);

    echo "<script>alert('Asignatura creada correctamente'); 
          window.location.href='../Presentacion/Administrador/gestion_asignaturas.php';</script>";

} catch (PDOException $e) {
    echo "<script>alert('Error: ".addslashes($e->getMessage())."'); window.history.back();</script>";
}
