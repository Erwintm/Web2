<?php


// 1. Validar sesión (por seguridad si se accede directo)
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['tipo_usuario']) || $_SESSION['tipo_usuario'] != 'maestro') {
    return; 
}

$id_usuario = $_SESSION['id_usuario'];

// Variables iniciales
$nombre_maestro = "Maestro";
$cant_materias = 0;
$cant_alumnos = 0;
$cant_calificaciones = 0;


try {
    // 2. Obtener ID del Maestro
    $sql = "SELECT id_maestro, nombre, apellido FROM maestros WHERE id_usuario = :id";
    // Usamos $pdo directamente
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':id' => $id_usuario]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($row) {
        $id_maestro = $row['id_maestro'];
        $nombre_maestro = $row['nombre'] . " " . $row['apellido'];

        // 3. Contar Materias
        $sql1 = "SELECT COUNT(*) FROM asignaturas WHERE id_maestro = :id";
        $stmt1 = $pdo->prepare($sql1);
        $stmt1->execute([':id' => $id_maestro]);
        $cant_materias = $stmt1->fetchColumn();

        // 4. Contar Alumnos
        $sql2 = "SELECT COUNT(DISTINCT i.id_alumno) 
                 FROM inscripciones i
                 INNER JOIN asignaturas a ON i.id_asignatura = a.id_asignatura
                 WHERE a.id_maestro = :id";
        $stmt2 = $pdo->prepare($sql2);
        $stmt2->execute([':id' => $id_maestro]);
        $cant_alumnos = $stmt2->fetchColumn();

        // 5. Contar Calificaciones
        $sql3 = "SELECT COUNT(*) 
                 FROM calificaciones c
                 INNER JOIN inscripciones i ON c.id_inscripcion = i.id_inscripcion
                 INNER JOIN asignaturas a ON i.id_asignatura = a.id_asignatura
                 WHERE a.id_maestro = :id";
        $stmt3 = $pdo->prepare($sql3);
        $stmt3->execute([':id' => $id_maestro]);
        $cant_calificaciones = $stmt3->fetchColumn();
    }

} catch (PDOException $e) {
    
}
?>