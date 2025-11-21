<?php
session_start();



// Obtener ID
if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("IaD inválido");
}

$id = (int)$_GET['id'];

// Cargar conexión
require_once __DIR__ . '/../../Datos/conexion.php';

// Consultar datos del alumno + usuario
$sql = "SELECT a.*, u.nombre_usuario, u.email AS email_usuario, u.id_usuario
        FROM alumnos a
        INNER JOIN usuarios u ON a.id_usuario = u.id_usuario
        WHERE a.id_alumno = :id";

$stmt = $pdo->prepare($sql);
$stmt->execute([':id' => $id]);
$alumno = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$alumno) {
    die("Alumno no encontrado.");
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Alumno</title>
    <link rel="stylesheet" href="../Diseño/Administrador/agregar_alumnos.css">
</head>
<body>

<header>
    <h1>Administración Escolar</h1>
    <nav>
        <a href="menu.php">Inicio</a>
        <a href="gestion_alumnos.php">Gestión Alumnos</a>
        <a href="gestion_maestros.php">Gestion Maestros</a>
        <a href="gestion_asignaturas.php">Gestion Materias</a>
        <a href="../index.html">Cerrar sesión</a>
    </nav>
</header>

<div class="container">

    <h2>Editar Alumno</h2>

    <form action="../../Negocio/editar_alumnos.php" method="POST" class="form-alumno">

        <input type="hidden" name="id_alumno" value="<?= $alumno['id_alumno'] ?>">
        <input type="hidden" name="id_usuario" value="<?= $alumno['id_usuario'] ?>">

        <div class="form-group">
            <label>Carrera:</label>
            <select name="carrera" required>
                <option value="Sistemas Computacionales" <?= $alumno['carrera']=="Sistemas Computacionales"?'selected':'' ?>>Sistemas Computacionales</option>
                <option value="Industrial" <?= $alumno['carrera']=="Industrial"?'selected':'' ?>>Industrial</option>
                <option value="Mecatronica" <?= $alumno['carrera']=="Mecatronica"?'selected':'' ?>>Mecatronica</option>
                <option value="Gestion" <?= $alumno['carrera']=="Gestion"?'selected':'' ?>>Gestion</option>
                <option value="Gastronomia" <?= $alumno['carrera']=="Gastronomia"?'selected':'' ?>>Gastronomia</option>
                <option value="Fisica" <?= $alumno['carrera']=="Fisica"?'selected':'' ?>>Fisica</option>
            </select>
        </div>

        <div class="form-group">
            <label>Matrícula:</label>
            <input type="text" name="matricula" value="<?= $alumno['matricula'] ?>" required>
        </div>

        <div class="form-group">
            <label>Nombre:</label>
            <input type="text" name="nombre" value="<?= $alumno['nombre'] ?>" required>
        </div>

        <div class="form-group">
            <label>Apellido:</label>
            <input type="text" name="apellido" value="<?= $alumno['apellido'] ?>" required>
        </div>

        <div class="form-group">
            <label>Email institucional:</label>
            <input type="email" name="email" value="<?= $alumno['email'] ?>" required>
        </div>

        <div class="form-group">
            <label>Teléfono:</label>
            <input type="text" name="telefono" value="<?= $alumno['telefono'] ?>">
        </div>

        <div class="form-group">
            <label>Fecha de nacimiento:</label>
            <input type="date" name="fecha_nacimiento" value="<?= $alumno['fecha_nacimiento'] ?>" required>
        </div>

        <h3>Datos de acceso</h3>

        <div class="form-group">
            <label>Usuario:</label>
            <input type="text" name="usuario" value="<?= $alumno['nombre_usuario'] ?>" required>
        </div>

        <div class="form-group">
            <label>Nueva contraseña (opcional):</label>
            <input type="password" name="password">
            <small>Déjalo vacío si no deseas cambiarla</small>
        </div>

        <div class="form-group">
            <label>Estado:</label>
            <select name="estado" required>
                <option value="activo" <?= $alumno['estado']=="activo"?'selected':'' ?>>Activo</option>
                <option value="inactivo" <?= $alumno['estado']=="inactivo"?'selected':'' ?>>Inactivo</option>
            </select>
        </div>

        <button type="submit" class="btn-guardar">Guardar Cambios</button>

    </form>

    

</div>

<footer>
  <div class="footer-izquierda">
    <img src="../Imagenes/logo_tec.png" alt="Logo Instituto" class="logo-footer">
    <div class="footer-info">
      <p><strong>Instituto Tecnológico Superior de San Nicolás de Hidalgo</strong></p>
      <p><strong>Correo: instecsanico@gmail.com</strong></p>
    </div>
  </div>

  <div class="footer-derecha">
    <p><strong>Sistema de Administración Escolar 2025</strong></p>
    <p><strong>Desarrollado por el área de informática</strong></p>
  </div>
</footer>

</body>
</html>
