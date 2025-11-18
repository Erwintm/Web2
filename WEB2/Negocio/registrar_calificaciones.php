
<?php
// registrar_calificaciones.php
// Recibe id_materia, id_alumno, calificacion y registra la calificación, luego actualiza el promedio

require_once '../Datos/conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	$id_materia = $_POST['id_materia'] ?? null;
	$id_alumno = $_POST['id_alumno'] ?? null;
	$calificacion = $_POST['calificacion'] ?? null;

	if ($id_materia && $id_alumno && is_numeric($calificacion)) {
		try {
			// Registrar calificación (insertar o actualizar)
			$stmt = $conn->prepare('SELECT id_calificacion FROM calificaciones WHERE id_materia = ? AND id_alumno = ?');
			$stmt->execute([$id_materia, $id_alumno]);
			$row = $stmt->fetch(PDO::FETCH_ASSOC);

			if ($row) {
				// Actualizar calificación existente
				$stmt = $conn->prepare('UPDATE calificaciones SET calificacion = ? WHERE id_calificacion = ?');
				$stmt->execute([$calificacion, $row['id_calificacion']]);
			} else {
				// Insertar nueva calificación
				$stmt = $conn->prepare('INSERT INTO calificaciones (id_materia, id_alumno, calificacion) VALUES (?, ?, ?)');
				$stmt->execute([$id_materia, $id_alumno, $calificacion]);
			}

			// Calcular nuevo promedio del alumno en la materia
			$stmt = $conn->prepare('SELECT AVG(calificacion) AS promedio FROM calificaciones WHERE id_materia = ? AND id_alumno = ?');
			$stmt->execute([$id_materia, $id_alumno]);
			$promedio = $stmt->fetchColumn();

			// Actualizar o insertar en la tabla promedios
			$stmt = $conn->prepare('SELECT id_promedio FROM promedios WHERE id_materia = ? AND id_alumno = ?');
			$stmt->execute([$id_materia, $id_alumno]);
			$row = $stmt->fetch(PDO::FETCH_ASSOC);

			if ($row) {
				$stmt = $conn->prepare('UPDATE promedios SET promedio = ? WHERE id_promedio = ?');
				$stmt->execute([$promedio, $row['id_promedio']]);
			} else {
				$stmt = $conn->prepare('INSERT INTO promedios (id_materia, id_alumno, promedio) VALUES (?, ?, ?)');
				$stmt->execute([$id_materia, $id_alumno, $promedio]);
			}

			echo '<tr><td>Calificación registrada</td><td>' . htmlspecialchars($calificacion) . '</td><td>Promedio actualizado: ' . number_format($promedio, 2) . '</td></tr>';
		} catch (PDOException $e) {
			echo '<tr><td colspan="3">Error: ' . htmlspecialchars($e->getMessage()) . '</td></tr>';
		}
	} else {
		echo '<tr><td colspan="3">Datos incompletos o inválidos</td></tr>';
	}
} else {
	echo '<tr><td colspan="3">Método no permitido</td></tr>';
}
