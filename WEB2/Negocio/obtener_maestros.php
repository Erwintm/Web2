<?php
require_once '../Datos/conexion.php';

$filtro = $_GET['filtro'] ?? '';
$especialidad = $_GET['especialidad'] ?? '';

$sql = "SELECT id_maestro, nombre, apellido, email, telefono, especialidad, estado
        FROM maestros 
        WHERE 1";

if (!empty($filtro)) {
    $sql .= " AND (nombre LIKE :filtro OR apellido LIKE :filtro)";
}

if (!empty($especialidad)) {
    $sql .= " AND especialidad = :especialidad";
}

$stmt = $pdo->prepare($sql);

if (!empty($filtro)) {
    $paramFiltro = "%$filtro%";
    $stmt->bindParam(':filtro', $paramFiltro);
}

if (!empty($especialidad)) {
    $stmt->bindParam(':especialidad', $especialidad);
}

$stmt->execute();
$maestros = $stmt->fetchAll(PDO::FETCH_ASSOC);

if ($maestros) {
    foreach ($maestros as $m) {
        echo "<tr>
                <td>{$m['nombre']}</td>
                <td>{$m['apellido']}</td>
                <td>{$m['email']}</td>
                <td>{$m['telefono']}</td>
                <td>{$m['especialidad']}</td>
                <td>{$m['estado']}</td>

                <td style='text-align:center;'>
                    <button class='btn-editar' 
                        onclick=\"location.href='editar_maestro.php?id={$m['id_maestro']}'\">
                        Editar
                    </button>

                    <button class='btn-eliminar'
    onclick='eliminarMaestro({$m['id_maestro']})'>
    Eliminar
</button>
                </td>
              </tr>";
    }
} else {
    echo "<tr><td colspan='7'>No se encontraron maestros</td></tr>";
}
