<?php
session_start();
require_once '../Datos/conexion.php';

// Validar sesión: solo maestros
if (!isset($_SESSION['tipo_usuario']) || $_SESSION['tipo_usuario'] != 'maestro') {
	echo "<tr><td colspan='5'>Acceso denegado</td></tr>";
	exit;
}

// Obtener id_usuario desde sesión y resolver id_maestro
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

// Filtros desde GET
$filtro = $_GET['filtro'] ?? '';
$id_materia = $_GET['id_materia'] ?? '';

// Construir consulta SQL: alumnos inscritos en materias del maestro
$sql = "SELECT al.id_alumno, al.nombre, al.apellido, a.id_asignatura, a.nombre AS nombre_materia, 
			   c.calificacion, p.promedio
		FROM alumnos al
		INNER JOIN inscripciones i ON al.id_alumno = i.id_alumno
		INNER JOIN asignaturas a ON i.id_asignatura = a.id_asignatura
		LEFT JOIN calificaciones c ON i.id_inscripcion = c.id_inscripcion
		LEFT JOIN promedios p ON a.id_asignatura = p.id_asignatura AND al.id_alumno = p.id_alumno
		WHERE a.id_maestro = :id_maestro";

if (!empty($filtro)) {
	$sql .= " AND (al.nombre LIKE :filtro OR al.apellido LIKE :filtro)";
}

if (!empty($id_materia)) {
	$sql .= " AND a.id_asignatura = :id_materia";
}

$sql .= " ORDER BY a.nombre, al.nombre";

$stmt = $pdo->prepare($sql);
$stmt->bindParam(':id_maestro', $id_maestro);

if (!empty($filtro)) {
	$paramFiltro = "%{$filtro}%";
	$stmt->bindParam(':filtro', $paramFiltro);
}

if (!empty($id_materia)) {
	$stmt->bindParam(':id_materia', $id_materia);
}

$stmt->execute();
$registros = $stmt->fetchAll(PDO::FETCH_ASSOC);

if ($registros) {
	foreach ($registros as $reg) {
		$id_alumno = (int)$reg['id_alumno'];
		$id_asignatura = (int)$reg['id_asignatura'];
		$nombre_alumno = htmlspecialchars($reg['nombre'] . ' ' . $reg['apellido']);
		$nombre_materia = htmlspecialchars($reg['nombre_materia']);
		$calificacion = isset($reg['calificacion']) ? htmlspecialchars($reg['calificacion']) : '';
		$promedio = isset($reg['promedio']) && is_numeric($reg['promedio']) ? number_format($reg['promedio'], 2) : '0.00';

		echo "<tr data-id-alumno=\"{$id_alumno}\" data-id-materia=\"{$id_asignatura}\">";
		echo "<td>{$nombre_alumno}</td>";
		echo "<td>{$nombre_materia}</td>";
		echo "<td><input type=\"number\" class=\"input-calificacion\" min=\"0\" max=\"100\" value=\"{$calificacion}\" step=\"0.1\"></td>";
		echo "<td>{$promedio}</td>";
		echo "<td><button class=\"btn-registrar\">Registrar</button><div class=\"resultado\"></div></td>";
		echo "</tr>";
	}
} else {
	echo "<tr><td colspan='5'>No se encontraron registros</td></tr>";
}

?>
