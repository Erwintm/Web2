<?php
require_once '../Datos/conexion.php';

if (!isset($_POST['id_asignatura'])) {
    echo "Error: No se recibió el ID de la asignatura.";
    exit;
}

$id_asignatura = $_POST['id_asignatura'];

try {
    $sql = "DELETE FROM asignaturas WHERE id_asignatura = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id', $id_asignatura);

    if ($stmt->execute()) {
        echo "Asignatura eliminada correctamente.";
    } else {
        echo "No se pudo eliminar.";
    }

} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
