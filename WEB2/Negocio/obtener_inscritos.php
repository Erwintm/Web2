<?php
require_once '../Datos/conexion.php';

if (!isset($_GET['id_asignatura'])) {
    echo "<tr><td colspan='4'>Error: asignatura no recibida</td></tr>";
    exit;
}

$id_asignatura = $_GET['id_asignatura'];

$sql = "SELECT i.id_inscripcion, a.matricula, a.nombre, a.apellido, a.email
        FROM inscripciones i
        INNER JOIN alumnos a ON a.id_alumno = i.id_alumno
        WHERE i.id_asignatura = :id";

$stmt = $pdo->prepare($sql);
$stmt->bindParam(':id', $id_asignatura);
$stmt->execute();
$data = $stmt->fetchAll(PDO::FETCH_ASSOC);

if ($data) {
    foreach ($data as $al) {
        echo "<tr>
                <td>{$al['matricula']}</td>
                <td>{$al['nombre']} {$al['apellido']}</td>
                <td>{$al['email']}</td>

                <td style='text-align:center;'>
                    <button class='btn-eliminar'
                        onclick=\"darDeBaja({$al['id_inscripcion']})\">
                        Dar de baja
                    </button>
                </td>
              </tr>";
    }
} else {
    echo "<tr><td colspan='4'>No hay alumnos inscritos</td></tr>";
}
