<?php
session_start();

// Obtener ID del maestro
if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("ID inválido");
}

$id = (int)$_GET['id'];

require_once __DIR__ . '/../../Datos/conexion.php';

// Cargar maestro + usuario
$sql = "SELECT m.*, 
               u.nombre_usuario, 
               u.email AS email_usuario, 
               u.id_usuario
        FROM maestros m
        INNER JOIN usuarios u ON m.id_usuario = u.id_usuario
        WHERE m.id_maestro = :id";

$stmt = $pdo->prepare($sql);
$stmt->execute([':id' => $id]);
$maestro = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$maestro) {
    die("Maestro no encontrado.");
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Maestro</title>
    <link rel="stylesheet" href="../Diseño/Administrador/agregar_alumnos.css">
</head>
<body>

<header>
    <h1>Administración Escolar</h1>
    <nav>
        <a href="menu.php">Inicio</a>
        <a href="gestion_alumnos.php">Gestión Alumnos</a>
        <a href="gestion_maestros.php">Gestión Maestros</a>
        <a href="gestion_asignaturas.php">Gestion Materias</a>
        <a href="../index.html">Cerrar sesión</a>
    </nav>
</header>

<div class="container">

<h2>Editar Maestro</h2>

<form action="../../Negocio/editar_maestro.php" method="POST" class="form-alumno">

    <input type="hidden" name="id_maestro" value="<?= $maestro['id_maestro'] ?>">
    <input type="hidden" name="id_usuario" value="<?= $maestro['id_usuario'] ?>">

    <div class="form-group">
        <label>Nombre:</label>
        <input type="text" name="nombre" value="<?= $maestro['nombre'] ?>" required>
    </div>

    <div class="form-group">
        <label>Apellido:</label>
        <input type="text" name="apellido" value="<?= $maestro['apellido'] ?>" required>
    </div>

    <div class="form-group">
        <label>Email institucional:</label>
        <input type="email" name="email" value="<?= $maestro['email'] ?>" required>
    </div>

    <div class="form-group">
        <label>Teléfono:</label>
        <input type="text" name="telefono" value="<?= $maestro['telefono'] ?>">
    </div>

    <div class="form-group">
        <label>Especialidad:</label>
        <select name="especialidad" required>
            <option value="Sistemas Computacionales" <?= $maestro['especialidad']=="Sistemas Computacionales"?'selected':'' ?>>Sistemas Computacionales</option>
            <option value="Matematicas" <?= $maestro['especialidad']=="Matematicas"?'selected':'' ?>>Matemáticas</option>
            <option value="Fisica" <?= $maestro['especialidad']=="Fisica"?'selected':'' ?>>Física</option>
            <option value="Electronica" <?= $maestro['especialidad']=="Electronica"?'selected':'' ?>>Electrónica</option>
            <option value="Administracion" <?= $maestro['especialidad']=="Administracion"?'selected':'' ?>>Administración</option>
        </select>
    </div>

    <h3>Datos de acceso</h3>

    <div class="form-group">
        <label>Usuario:</label>
        <input type="text" name="usuario" value="<?= $maestro['nombre_usuario'] ?>" required>
    </div>

    <div class="form-group">
        <label>Nueva contraseña (opcional):</label>
        <input type="password" name="password">
        <small>Déjalo vacío si no deseas cambiarla</small>
    </div>

    <button type="submit" class="btn-guardar">Guardar Cambios</button>

</form>

</div>

</body>
</html>
