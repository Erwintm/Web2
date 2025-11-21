<?php
require_once '../Datos/conexion.php';

$id = $_POST['id_asignatura'];
$nombre = trim($_POST['nombre']);
$codigo = trim($_POST['codigo']);
$id_maestro = $_POST['id_maestro'];
$horario = $_POST['horario'];
$salon = $_POST['salon'];
$capacidad = $_POST['capacidad'];

try {
    $sql = "UPDATE asignaturas
            SET nombre = :nombre,
                codigo = :codigo,
                id_maestro = :id_maestro,
                horario = :horario,
                salon = :salon,
                capacidad = :capacidad
            WHERE id_asignatura = :id";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':nombre' => $nombre,
        ':codigo' => $codigo,
        ':id_maestro' => $id_maestro,
        ':horario' => $horario,
        ':salon' => $salon,
        ':capacidad' => $capacidad,
        ':id' => $id
    ]);

    echo "<script>alert('Asignatura actualizada correctamente'); 
          window.location.href='../Presentacion/Administrador/gestion_asignaturas.php';</script>";

} catch (PDOException $e) {
    echo "<script>alert('Error: ".addslashes($e->getMessage())."'); window.history.back();</script>";
}
