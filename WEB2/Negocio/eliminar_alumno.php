<?php
require_once '../Datos/conexion.php'; // aquí se crea $pdo

if (!isset($_POST['matricula'])) {
    echo "Error: No se recibió la matrícula.";
    exit;
}

$matricula = $_POST['matricula'];

try {
    $sql = "DELETE FROM alumnos WHERE matricula = :matricula";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':matricula', $matricula);

    if ($stmt->execute()) {
        echo "Alumno eliminado correctamente.";
    } else {
        echo "No se pudo eliminar el alumno.";
    }

} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
?>
