<?php
session_start();
require_once '../Datos/conexion.php';

// Validar sesión: solo maestros
if (!isset($_SESSION['tipo_usuario']) || $_SESSION['tipo_usuario'] != 'maestro') {
	echo "<tr><td colspan='7'>Acceso denegado</td></tr>";
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
	echo "<tr><td colspan='7'>No se encontró información del maestro</td></tr>";
	exit;
}
$id_maestro = $row['id_maestro'];

// Filtros desde GET
$filtro = $_GET['filtro'] ?? '';
$estado = $_GET['estado'] ?? '';
$opciones = $_GET['opciones'] ?? 0;

// Construir consulta SQL
$sql = "SELECT a.id_asignatura, a.codigo, a.nombre, a.creditos, a.horario, a.salon, a.estado, 
			   COUNT(i.id_alumno) AS alumnos_inscritos
		FROM asignaturas a
		LEFT JOIN inscripciones i ON a.id_asignatura = i.id_asignatura
		WHERE a.id_maestro = :id_maestro";

if (!empty($filtro)) {
	$sql .= " AND (a.nombre LIKE :filtro OR a.codigo LIKE :filtro)";
}

if (!empty($estado)) {
	$sql .= " AND a.estado = :estado";
}

$sql .= " GROUP BY a.id_asignatura ORDER BY a.nombre";

$stmt = $pdo->prepare($sql);
$stmt->bindParam(':id_maestro', $id_maestro);

if (!empty($filtro)) {
	$paramFiltro = "%{$filtro}%";
	$stmt->bindParam(':filtro', $paramFiltro);
}

if (!empty($estado)) {
	$stmt->bindParam(':estado', $estado);
}

$stmt->execute();
$materias = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Si se solicitan opciones para un select (opciones=1), devolver <option>
if ($opciones == 1) {
	if ($materias) {
		foreach ($materias as $m) {
			$id = (int)$m['id_asignatura'];
			$nombre = htmlspecialchars($m['nombre']);
			echo "<option value=\"{$id}\">{$nombre}</option>";
		}
	} else {
		echo "<option value=\"\">No hay materias</option>";
	}
	exit;
}

if ($materias) {
	foreach ($materias as $m) {
		$codigo = htmlspecialchars($m['codigo']);
		$nombre = htmlspecialchars($m['nombre']);
		$creditos = htmlspecialchars($m['creditos']);
		$horario = htmlspecialchars($m['horario']);
		$salon = htmlspecialchars($m['salon']);
		$inscritos = (int)$m['alumnos_inscritos'];
		$estado_txt = htmlspecialchars($m['estado']);

		echo "<tr>";
		echo "<td>{$codigo}</td>";
		echo "<td>{$nombre}</td>";
		echo "<td>{$creditos}</td>";
		echo "<td>{$horario}</td>";
		echo "<td>{$salon}</td>";
		echo "<td>{$inscritos}</td>";
		echo "<td>{$estado_txt}</td>";
		echo "</tr>";
	}
} else {
	echo "<tr><td colspan='7'>No se encontraron materias</td></tr>";
}

?>
