<?php
require_once '../Datos/conexion.php';


$nombre = trim($_POST['nombre']);
$codigo = trim($_POST['codigo']);
$id_maestro = $_POST['id_maestro']; // Es un valor de SELECT, no se usa trim
$horario = trim($_POST['horario']);
$salon = trim($_POST['salon']);
$capacidad = trim($_POST['capacidad']);


if ($nombre === '' || $codigo === '' || $id_maestro === '' || $horario === '' || $salon === '') {
    echo "<script>alert('Error: Faltan campos obligatorios.'); window.history.back();</script>";
    exit;
}

if (!preg_match('/^[A-Z0-9.\-]{1,20}$/i', $codigo)) {
    echo "<script>alert('Error: El Código debe ser alfanumérico y tener entre 1 y 20 caracteres.'); window.history.back();</script>";
    exit;
}


$sql_check = "SELECT COUNT(*) FROM asignaturas WHERE codigo = :codigo";
$stmt_check = $pdo->prepare($sql_check);
$stmt_check->execute([':codigo' => $codigo]);
if ($stmt_check->fetchColumn() > 0) {
    echo "<script>alert('Error: El Código de asignatura ya existe.'); window.history.back();</script>";
    exit;
}

if (strlen($nombre) > 100 || !preg_match('/^[a-zA-ZáéíóúÁÉÍÓÚñÑ0-9\s.,\-]{1,100}$/', $nombre)) {
    echo "<script>alert('Error: El Nombre tiene un formato o longitud no válido (max 100 caracteres).'); window.history.back();</script>";
    exit;
}


if (!filter_var($capacidad, FILTER_VALIDATE_INT, ["options" => ["min_range" => 1, "max_range" => 150]])) {
    echo "<script>alert('Error: La Capacidad debe ser un número entero entre 1 y 150.'); window.history.back();</script>";
    exit;
}



try {
 
    $sql = "INSERT INTO asignaturas
            (nombre, codigo, id_maestro, horario, salon, capacidad)
            VALUES (:nombre, :codigo, :id_maestro, :horario, :salon, :capacidad)";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':nombre' => $nombre,
        ':codigo' => $codigo,
        ':id_maestro' => $id_maestro,
        ':horario' => $horario,
        ':salon' => $salon,
        ':capacidad' => $capacidad
    ]);

    echo "<script>alert('Asignatura creada correctamente'); 
          window.location.href='../Presentacion/Administrador/gestion_asignaturas.php';</script>";

} catch (PDOException $e) {
    // Manejo de otros errores de base de datos
    echo "<script>alert('Error en BD: ".addslashes($e->getMessage())."'); window.history.back();</script>";
}
?>