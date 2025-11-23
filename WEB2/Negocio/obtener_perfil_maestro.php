<?php
session_start();
require_once '../Datos/conexion.php';

// Validar sesión
if (!isset($_SESSION['tipo_usuario']) || $_SESSION['tipo_usuario'] != 'maestro') {
    echo "<p>Acceso denegado</p>";
    exit;
}

$id_usuario = $_SESSION['id_usuario'];

// Consultar datos
$sql = "SELECT m.nombre, m.apellido, m.telefono, m.especialidad, u.email, u.nombre_usuario 
        FROM maestros m 
        INNER JOIN usuarios u ON m.id_usuario = u.id_usuario 
        WHERE m.id_usuario = :id";

$stmt = $pdo->prepare($sql);
$stmt->bindParam(':id', $id_usuario);
$stmt->execute();
$row = $stmt->fetch(PDO::FETCH_ASSOC);

if ($row) {
    
    // Usuario
    echo '<div class="fila-dato">';
    echo '<label>Usuario:</label>';
    echo '<input type="text" class="input-lectura" value="' . htmlspecialchars($row['nombre_usuario']) . '" readonly>';
    echo '</div>';

    // Nombre Completo
    echo '<div class="fila-dato">';
    echo '<label>Nombre Completo:</label>';
    echo '<input type="text" class="input-lectura" value="' . htmlspecialchars($row['nombre'] . ' ' . $row['apellido']) . '" readonly>';
    echo '</div>';

    // Email
    echo '<div class="fila-dato">';
    echo '<label>Correo Electrónico:</label>';
    echo '<input type="text" class="input-lectura" value="' . htmlspecialchars($row['email']) . '" readonly>';
    echo '</div>';

    // Teléfono
    echo '<div class="fila-dato">';
    echo '<label>Teléfono:</label>';
    echo '<input type="text" class="input-lectura" value="' . htmlspecialchars($row['telefono']) . '" readonly>';
    echo '</div>';

    // Especialidad
    echo '<div class="fila-dato">';
    echo '<label>Especialidad:</label>';
    echo '<input type="text" class="input-lectura" value="' . htmlspecialchars($row['especialidad']) . '" readonly>';
    echo '</div>';
    
} else {
    echo "<p>No se encontraron datos del perfil.</p>";
}
?>