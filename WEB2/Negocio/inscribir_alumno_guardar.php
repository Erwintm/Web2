<?php
require_once '../Datos/conexion.php';

$id_alumno = $_POST['id_alumno'];
$id_asignatura = $_POST['id_asignatura'];

try {
    $sql = "INSERT INTO inscripciones (id_alumno, id_asignatura)
            VALUES (:id_alumno, :id_asignatura)";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':id_alumno' => $id_alumno,
        ':id_asignatura' => $id_asignatura
    ]);

    echo "<script>
            alert('Alumno inscrito correctamente');
            window.location.href='../Presentacion/Administrador/alumnos_inscritos.php?id_asignatura=$id_asignatura';
          </script>";

} catch (PDOException $e) {
    echo "<script>
            alert('Error: " . addslashes($e->getMessage()) . "');
            window.history.back();
          </script>";
}
