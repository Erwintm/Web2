<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
require_once '../Datos/conexion.php';

// Validar sesión: solo maestros
if (!isset($_SESSION['tipo_usuario']) || $_SESSION['tipo_usuario'] != 'maestro') {
    echo "<tr><td colspan='5'>Acceso denegado</td></tr>";
    exit;
}

try {
    // 1. Obtener id_usuario y resolver id_maestro
    $id_usuario = $_SESSION['id_usuario'];
    $sql = "SELECT id_maestro FROM maestros WHERE id_usuario = :id_usuario";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id_usuario', $id_usuario);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$row || empty($row['id_maestro'])) {
        echo "<tr><td colspan='5'>No se encontró información del maestro</td></tr>";
        exit;
    }
    $id_maestro = $row['id_maestro'];

    // 2. Recibir Filtros
    $filtro     = $_GET['filtro'] ?? '';
    $id_materia = $_GET['id_materia'] ?? '';
    $parcial    = $_GET['parcial'] ?? 'Parcial 1';

    // 3. Construir Consulta SQL
    $sql = "SELECT al.id_alumno, al.nombre, al.apellido, a.id_asignatura, a.nombre AS nombre_materia, 
                   c.calificacion, p.promedio
            FROM alumnos al
            INNER JOIN inscripciones i ON al.id_alumno = i.id_alumno
            INNER JOIN asignaturas a ON i.id_asignatura = a.id_asignatura
            -- El LEFT JOIN asegura que el alumno salga aunque no tenga nota en ESTE parcial
            LEFT JOIN calificaciones c ON i.id_inscripcion = c.id_inscripcion AND c.tipo_evaluacion = :parcial
            LEFT JOIN promedios p ON a.id_asignatura = p.id_asignatura AND al.id_alumno = p.id_alumno
            WHERE a.id_maestro = :id_maestro";

    // Filtros dinámicos
    if (!empty($filtro)) {
        $sql .= " AND (al.nombre LIKE :filtro OR al.apellido LIKE :filtro)";
    }

    if (!empty($id_materia)) {
        $sql .= " AND a.id_asignatura = :id_materia";
    }

    $sql .= " ORDER BY a.nombre, al.nombre";

    // 4. Preparar y Vincular
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id_maestro', $id_maestro);
    $stmt->bindParam(':parcial', $parcial);

    if (!empty($filtro)) {
        $paramFiltro = "%{$filtro}%";
        $stmt->bindParam(':filtro', $paramFiltro);
    }

    if (!empty($id_materia)) {
        $stmt->bindParam(':id_materia', $id_materia);
    }

    // 5. Ejecutar
    $stmt->execute();
    $registros = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if ($registros) {
        foreach ($registros as $reg) {
            $id_alumno = (int)$reg['id_alumno'];
            $id_asignatura = (int)$reg['id_asignatura'];
            $nombre_alumno = htmlspecialchars($reg['nombre'] . ' ' . $reg['apellido']);
            $nombre_materia = htmlspecialchars($reg['nombre_materia']);
            
            // Manejo de calificación nula (si no existe en este parcial)
            $calificacion = isset($reg['calificacion']) ? htmlspecialchars($reg['calificacion']) : '';
            
            // Manejo de promedio
            $promedio = isset($reg['promedio']) && is_numeric($reg['promedio']) ? number_format($reg['promedio'], 2) : '0.00';

            echo "<tr data-id-alumno=\"{$id_alumno}\" data-id-materia=\"{$id_asignatura}\">";
            echo "<td>{$nombre_alumno}</td>";
            echo "<td>{$nombre_materia}</td>";
            // Input cargado con la nota del parcial seleccionado
            echo "<td><input type=\"number\" class=\"input-calificacion\" min=\"0\" max=\"100\" value=\"{$calificacion}\" step=\"0.1\" placeholder=\"-\"></td>";
            echo "<td class=\"celda-promedio\">{$promedio}</td>";
            echo "<td><button class=\"btn-registrar\">Registrar</button><div class=\"resultado\"></div></td>";
            echo "</tr>";
        }
    } else {
        // Si entra aquí, es que la consulta funcionó pero no hay alumnos inscritos
        echo "<tr><td colspan='5' style='text-align:center'>No se encontraron alumnos inscritos para la materia seleccionada.</td></tr>";
    }

} catch (PDOException $e) {
    // 6. Captura de errores
    echo "<tr><td colspan='5' style='color:red; font-weight:bold;'>Error SQL: " . $e->getMessage() . "</td></tr>";
}
?>