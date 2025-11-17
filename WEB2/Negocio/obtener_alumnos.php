<?php
require_once '../Datos/conexion.php';

$filtro = $_GET['filtro'] ?? '';
$carrera = $_GET['carrera'] ?? '';

$sql = "SELECT matricula, nombre, apellido, email, telefono, fecha_nacimiento, estado 
        FROM alumnos 
        WHERE 1";


if (!empty($filtro)) {
    $sql .= " AND (nombre LIKE :filtro OR apellido LIKE :filtro)";
}

if (!empty($carrera)) {
    $sql .= " AND carrera = :carrera";
}

$stmt = $pdo->prepare($sql);

if (!empty($filtro)) {
    $paramFiltro = "%$filtro%";
    $stmt->bindParam(':filtro', $paramFiltro);
}

if (!empty($carrera)) {
    $stmt->bindParam(':carrera', $carrera);
}

$stmt->execute();
$alumnos = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Mostrar resultados
if ($alumnos) {
    foreach ($alumnos as $a) {
        echo "<tr>
                <td>{$a['matricula']}</td>
                <td>{$a['nombre']}</td>
                <td>{$a['apellido']}</td>
                <td>{$a['email']}</td>
                <td>{$a['telefono']}</td>
                <td>{$a['fecha_nacimiento']}</td>
                <td>{$a['estado']}</td>
                 <td style='text-align:center;'>
                    

                        <button class='btn-eliminar' onclick=\"eliminarAlumno('{$a['matricula']}')\">
                        Eliminar
                    </button>
                   </td>
                
              </tr>";
    }
} else {
    echo "<tr><td colspan='7'>No se encontraron alumnos</td></tr>";
}
?>
