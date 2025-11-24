<?php
header('Content-Type: application/json');

session_start();

require '../../Datos/conexion.php';

if (!isset($_SESSION['tipo_usuario']) || $_SESSION['tipo_usuario'] != 'alumno') {
    echo json_encode(['error' => 'Acceso denegado']);
    exit;
}

$id_usuario = $_SESSION['id_usuario'];

$sql = "SELECT id_alumno FROM alumnos WHERE id_usuario = :id_usuario";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':id_usuario', $id_usuario);
$stmt->execute();
$row = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$row) {
    echo json_encode(['error' => 'No se encontró información del alumno']);
    exit;
}

$id_alumno = $row['id_alumno'];



$sql = "select concat(nombre, ' ', apellido) AS alumno,matricula,estado,carrera from alumnos where id_alumno = :id_alumno";

$stmt = $pdo->prepare($sql);
$stmt->execute([':id_alumno' => $id_alumno]);

$perfil  = $stmt->fetchAll(PDO::FETCH_ASSOC);


echo json_encode($perfil );
?>