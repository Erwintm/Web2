
<?php
// Negocio/registrar_calificaciones.php
session_start();
require_once '../Datos/conexion.php';

// 1. Validar sesión
if (!isset($_SESSION['tipo_usuario']) || $_SESSION['tipo_usuario'] !== 'maestro') {
    echo "<span style='color:red; font-weight:bold;'>Error: Acceso denegado</span>";
    exit;
}

// 2. Recibir datos
$id_asignatura = $_POST['id_materia'] ?? null;
$id_alumno     = $_POST['id_alumno'] ?? null;
$calificacion  = $_POST['calificacion'] ?? null;
$parcial       = $_POST['parcial'] ?? 'Parcial 1'; // Valor por defecto si no se envía

// Validaciones
if (!$id_asignatura || !$id_alumno || !is_numeric($calificacion)) {
    echo "<span style='color:red;'>Faltan datos</span>";
    exit;
}

$calificacion = floatval($calificacion);
if ($calificacion < 0 || $calificacion > 100) {
    echo "<span style='color:red;'>0-100 solamente</span>";
    exit;
}

try {
    // BUSCAR ID_INSCRIPCION 
    $sqlInsc = "SELECT id_inscripcion FROM inscripciones WHERE id_alumno = ? AND id_asignatura = ?";
    $stmt = $pdo->prepare($sqlInsc);
    $stmt->execute([$id_alumno, $id_asignatura]);
    $id_inscripcion = $stmt->fetchColumn();

    if (!$id_inscripcion) {
        echo "<span style='color:red;'>Alumno no inscrito</span>";
        exit;
    }

    // INSERTAR / ACTUALIZAR CALIFICACIÓN (Usando ON DUPLICATE KEY UPDATE)
    $sqlCalif = "INSERT INTO calificaciones (id_inscripcion, tipo_evaluacion, calificacion, fecha_calificacion) 
                 VALUES (?, ?, ?, CURDATE())
                 ON DUPLICATE KEY UPDATE calificacion = VALUES(calificacion), fecha_calificacion = CURDATE()";
    $stmt = $pdo->prepare($sqlCalif);
    $stmt->execute([$id_inscripcion, $parcial, $calificacion]);

    // RECALCULAR PROMEDIO
    $sqlProm = "SELECT AVG(calificacion) FROM calificaciones WHERE id_inscripcion = ?";
    $stmt = $pdo->prepare($sqlProm);
    $stmt->execute([$id_inscripcion]);
    $promedio = number_format($stmt->fetchColumn(), 2);
    
    $estado = ($promedio >= 60) ? 'Aprobado' : 'Reprobado';

    // ACTUALIZAR TABLA PROMEDIOS
    $sqlPromUpdate = "INSERT INTO promedios (id_alumno, id_asignatura, promedio, estado_asignatura) 
                      VALUES (?, ?, ?, ?)
                      ON DUPLICATE KEY UPDATE promedio = VALUES(promedio), estado_asignatura = VALUES(estado_asignatura)";
    $stmt = $pdo->prepare($sqlPromUpdate);
    $stmt->execute([$id_alumno, $id_asignatura, $promedio, $estado]);

    // RESPUESTA EXITOSA 
    echo "<span style='color:green; font-weight:bold;'>✔ Guardado</span>";

} catch (PDOException $e) {
    echo "<span style='color:red;'>Error BD</span>";
}
?>