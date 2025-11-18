<?php
require_once '../Datos/conexion.php';

if (!isset($_POST['id_maestro'])) {
    echo "Error: No se recibió el ID del maestro.";
    exit;
}

$id_maestro = $_POST['id_maestro'];

try {

    // Primero obtener id_usuario
    $sql = "SELECT id_usuario FROM maestros WHERE id_maestro = :id_maestro";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id_maestro', $id_maestro);
    $stmt->execute();

    $resultado = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$resultado) {
        echo "Error: El maestro no existe.";
        exit;
    }

    $id_usuario = $resultado['id_usuario'];

    // Cuando eliminas el usuario, la FK elimina el maestro automáticamente
    $sqlDel = "DELETE FROM usuarios WHERE id_usuario = :id_usuario";
    $stmtDel = $pdo->prepare($sqlDel);
    $stmtDel->bindParam(':id_usuario', $id_usuario);

    if ($stmtDel->execute()) {
        echo "Maestro eliminado correctamente.";
    } else {
        echo "No se pudo eliminar el maestro.";
    }

} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
?>
