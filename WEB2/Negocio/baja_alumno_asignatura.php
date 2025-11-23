<?php
require_once '../Datos/conexion.php';

if (!isset($_POST['id_inscripcion'])) {
    echo "Error: no se recibió el alumno";
    exit;
}

$id = $_POST['id_inscripcion'];

try {
    $stmt = $pdo->prepare("DELETE FROM inscripciones WHERE id_inscripcion = :id");
    $stmt->bindParam(':id', $id);

    if ($stmt->execute()) {
        echo "Alumno dado de baja correctamente.";
    } else {
        echo "No se pudo dar de baja.";
    }

} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
