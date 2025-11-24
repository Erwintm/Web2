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



$sql = "select a.codigo,a.nombre,a.creditos,a.horario,a.salon,concat(m.nombre, ' ', m.apellido) AS profesor from inscripciones i inner join asignaturas a on i.id_asignatura = a.id_asignatura inner join maestros m on a.id_maestro = m.id_maestro
        where i.id_alumno = :id_alumno";

$stmt = $pdo->prepare($sql);
$stmt->execute([':id_alumno' => $id_alumno]);

$materias = $stmt->fetchAll(PDO::FETCH_ASSOC);


echo json_encode($materias);
?>