<?php
// Configuración de la base de datos
$host = 'localhost'; // o la IP de tu servidor de BD
$dbname = 'control_escolar';
$user = 'root';//tu usuario de bd
$pass = 'root'; // tu contraseña de bd

// Crear una nueva instancia de PDO
try {
$pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $pass);

// Configurar el modo de error de PDO a excepción
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
// si la conexión falla, muestra el error y termina el script
echo json_encode(["error" => "Error de conexión a la base de datos: " . $e->getMessage()]);
exit;
}
?>