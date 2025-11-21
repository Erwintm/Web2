<?php
require_once '../Datos/conexion.php';

$filtro = $_GET['filtro'] ?? '';
$maestro = $_GET['maestro'] ?? '';

$sql = "SELECT a.*, m.nombre AS nom_m, m.apellido AS ape_m
        FROM asignaturas a
        INNER JOIN maestros m ON m.id_maestro = a.id_maestro
        WHERE 1";

if ($filtro !== '') {
    $sql .= " AND (a.nombre LIKE :filtro OR a.codigo LIKE :filtro)";
}

if ($maestro !== '') {
    $sql .= " AND a.id_maestro = :maestro";
}

$stmt = $pdo->prepare($sql);

if ($filtro !== '') {
    $param = "%$filtro%";
    $stmt->bindParam(':filtro', $param);
}

if ($maestro !== '') {
    $stmt->bindParam(':maestro', $maestro);
}

$stmt->execute();
$data = $stmt->fetchAll(PDO::FETCH_ASSOC);

if ($data) {
    foreach ($data as $a) {
        echo "<tr>
                <td>{$a['codigo']}</td>
                <td>{$a['nombre']}</td>
                <td>{$a['nom_m']} {$a['ape_m']}</td>
                <td>{$a['horario']}</td>
                <td>{$a['salon']}</td>
                <td>{$a['capacidad']}</td>
                <td>{$a['estado']}</td>

                <td style='text-align:center;'>
                    <button class='btn-editar' onclick=\"location.href='editar_asignatura.php?id={$a['id_asignatura']}'\">Editar</button>
                    <button class='btn-eliminar' onclick=\"eliminarAsignatura({$a['id_asignatura']})\">Eliminar</button>
                </td>
            </tr>";
    }
} else {
    echo "<tr><td colspan='8'>No se encontraron asignaturas</td></tr>";
}
