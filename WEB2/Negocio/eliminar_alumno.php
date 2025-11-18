<?php
require_once '../Datos/conexion.php'; 

if (!isset($_POST['matricula'])) {
    echo "Error: No se recibió la matrícula.";
    exit;
}

$matricula = $_POST['matricula'];

try {

    $sql = "SELECT id_usuario FROM alumnos WHERE matricula = :matricula";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':matricula' => $matricula]);

    $id_usuario = $stmt->fetchColumn();

    if (!$id_usuario) {
        echo "Alumno no encontrado.";
        exit;
    }

   
    $sqlDel = "DELETE FROM usuarios WHERE id_usuario = :id";
    $stmtDel = $pdo->prepare($sqlDel);
    $stmtDel->execute([':id' => $id_usuario]);

    echo "Alumno eliminado correctamente.";

} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
