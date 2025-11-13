<?php
require_once '../Datos/conexion.php';

$filtro = $_GET['filtro'] ?? '';





if ($filtro) {
    $sql = "SELECT matricula, nombre, apellido, email, telefono, fecha_nacimiento, estado 
            FROM alumnos WHERE nombre LIKE :filtro OR apellido LIKE :filtro";
    $stmt = $pdo->prepare($sql);
    $filtro = "%$filtro%";
    $stmt->bindParam(':filtro', $filtro);
} else {
    $sql = "SELECT matricula, nombre, apellido, email, telefono, fecha_nacimiento, estado FROM alumnos";
    $stmt = $pdo->prepare($sql);
}

$stmt->execute();
$alumnos = $stmt->fetchAll(PDO::FETCH_ASSOC);

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
              </tr>";
    }
} else {
    echo "<tr><td colspan='7'>No se encontraron alumnos</td></tr>";
}
?>
