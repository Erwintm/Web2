<?php
require_once '../Datos/conexion.php';

// Validar datos obligatorios
if (
    empty($_POST['nombre']) ||
    empty($_POST['apellido']) ||
    empty($_POST['email']) ||
    empty($_POST['usuario']) ||
    empty($_POST['password']) ||
    empty($_POST['especialidad'])
) {
    echo "Error: Todos los campos son obligatorios.";
    exit;
}

$nombre = $_POST['nombre'];
$apellido = $_POST['apellido'];
$email = $_POST['email'];
$telefono = $_POST['telefono'];
$usuario = $_POST['usuario']; // nombre_usuario
$password = password_hash($_POST['password'], PASSWORD_DEFAULT);
$especialidad = $_POST['especialidad'];

try {
    // Iniciar transacción
    $pdo->beginTransaction();

    // 1. Insertar en usuarios
    $sqlUser = "INSERT INTO usuarios (nombre_usuario, contraseña, email, tipo_usuario)
                VALUES (:usuario, :password, :email, 'maestro')";
    $stmtUser = $pdo->prepare($sqlUser);

    $stmtUser->bindParam(':usuario', $usuario);
    $stmtUser->bindParam(':password', $password);
    $stmtUser->bindParam(':email', $email);

    $stmtUser->execute();

    // Obtener ID del usuario creado
    $idUsuario = $pdo->lastInsertId();

    // 2. Insertar maestro
    $sqlMaestro = "INSERT INTO maestros (id_usuario, nombre, apellido, email, telefono, especialidad)
                   VALUES (:id_usuario, :nombre, :apellido, :email, :telefono, :especialidad)";
    $stmtMaestro = $pdo->prepare($sqlMaestro);

    $stmtMaestro->bindParam(':id_usuario', $idUsuario);
    $stmtMaestro->bindParam(':nombre', $nombre);
    $stmtMaestro->bindParam(':apellido', $apellido);
    $stmtMaestro->bindParam(':email', $email); // MISMO email que en usuarios
    $stmtMaestro->bindParam(':telefono', $telefono);
    $stmtMaestro->bindParam(':especialidad', $especialidad);

    $stmtMaestro->execute();

    // Confirmar cambios
    $pdo->commit();

    echo "<script>
            alert('Maestro registrado correctamente. Usuario: {$usuario}');
            window.location.href = '../Presentacion/Administrador/gestion_maestros.php';
          </script>";
    exit;

} catch (Exception $e) {
    $pdo->rollBack();
    echo "Error: " . $e->getMessage();
}
?>
