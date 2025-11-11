
CREATE DATABASE IF NOT EXISTS control_escolar;
USE control_escolar;


-- TABLA: Maestros

CREATE TABLE maestros (
    id_maestro INT PRIMARY KEY AUTO_INCREMENT,
    nombre VARCHAR(100) NOT NULL,
    apellido VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    telefono VARCHAR(15),
    especialidad VARCHAR(100),
    fecha_contratacion DATE,
    estado ENUM('activo', 'inactivo') DEFAULT 'activo',
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);


-- TABLA: Alumnos

CREATE TABLE alumnos (
    id_alumno INT PRIMARY KEY AUTO_INCREMENT,
    matricula VARCHAR(20) UNIQUE NOT NULL,
    nombre VARCHAR(100) NOT NULL,
    apellido VARCHAR(100) NOT NULL,
    email VARCHAR(100),
    telefono VARCHAR(15),
    fecha_nacimiento DATE,
    genero ENUM('M', 'F', 'Otro') DEFAULT 'M',
    domicilio TEXT,
    ciudad VARCHAR(50),
    estado_provincia VARCHAR(50),
    codigo_postal VARCHAR(10),
    fecha_inscripcion DATE NOT NULL,
    estado ENUM('activo', 'inactivo', 'egresado') DEFAULT 'activo',
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);


-- TABLA: Asignaturas/Cursos

CREATE TABLE asignaturas (
    id_asignatura INT PRIMARY KEY AUTO_INCREMENT,
    nombre VARCHAR(100) NOT NULL,
    codigo VARCHAR(20) UNIQUE NOT NULL,
    descripcion TEXT,
    creditos INT,
    id_maestro INT NOT NULL,
    horario VARCHAR(100),
    salon VARCHAR(50),
    capacidad INT DEFAULT 30,
    estado ENUM('activa', 'inactiva') DEFAULT 'activa',
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_maestro) REFERENCES maestros(id_maestro) ON DELETE RESTRICT
);


-- TABLA: Inscripción de Alumnos en Asignaturas

CREATE TABLE inscripciones (
    id_inscripcion INT PRIMARY KEY AUTO_INCREMENT,
    id_alumno INT NOT NULL,
    id_asignatura INT NOT NULL,
    fecha_inscripcion DATE NOT NULL,
    estado ENUM('inscrito', 'retirado', 'completado') DEFAULT 'inscrito',
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY unique_alumno_asignatura (id_alumno, id_asignatura),
    FOREIGN KEY (id_alumno) REFERENCES alumnos(id_alumno) ON DELETE CASCADE,
    FOREIGN KEY (id_asignatura) REFERENCES asignaturas(id_asignatura) ON DELETE CASCADE
);


-- TABLA: Calificaciones

CREATE TABLE calificaciones (
    id_calificacion INT PRIMARY KEY AUTO_INCREMENT,
    id_inscripcion INT NOT NULL,
    id_asignatura INT NOT NULL,
    id_maestro INT NOT NULL,
    tipo_evaluacion VARCHAR(50), -- Parcial 1, Parcial 2, Final, Tarea, etc.
    calificacion DECIMAL(5, 2),
    fecha_calificacion DATE,
    comentarios TEXT,
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_inscripcion) REFERENCES inscripciones(id_inscripcion) ON DELETE CASCADE,
    FOREIGN KEY (id_asignatura) REFERENCES asignaturas(id_asignatura) ON DELETE CASCADE,
    FOREIGN KEY (id_maestro) REFERENCES maestros(id_maestro) ON DELETE RESTRICT
);


-- TABLA: Promedio de Asignaturas

CREATE TABLE promedios (
    id_promedio INT PRIMARY KEY AUTO_INCREMENT,
    id_alumno INT NOT NULL,
    id_asignatura INT NOT NULL,
    promedio DECIMAL(5, 2),
    estado_asignatura VARCHAR(20), -- Aprobado, 
    fecha_calculo TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY unique_alumno_asig_promedio (id_alumno, id_asignatura),
    FOREIGN KEY (id_alumno) REFERENCES alumnos(id_alumno) ON DELETE CASCADE,
    FOREIGN KEY (id_asignatura) REFERENCES asignaturas(id_asignatura) ON DELETE CASCADE
);


-- TABLA: Usuarios del Sistema

CREATE TABLE usuarios (
    id_usuario INT PRIMARY KEY AUTO_INCREMENT,
    nombre_usuario VARCHAR(50) UNIQUE NOT NULL,
    contraseña VARCHAR(255) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    tipo_usuario ENUM('administrador', 'maestro', 'alumno') NOT NULL,
    id_maestro INT,
    id_alumno INT,
    estado ENUM('activo', 'inactivo') DEFAULT 'activo',
    ultimo_acceso DATETIME,
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_maestro) REFERENCES maestros(id_maestro) ON DELETE SET NULL,
    FOREIGN KEY (id_alumno) REFERENCES alumnos(id_alumno) ON DELETE SET NULL
);


CREATE INDEX idx_alumno_estado ON alumnos(estado);
CREATE INDEX idx_maestro_estado ON maestros(estado);
CREATE INDEX idx_asignatura_maestro ON asignaturas(id_maestro);
CREATE INDEX idx_calificacion_alumno ON calificaciones(id_inscripcion);
CREATE INDEX idx_calificacion_fecha ON calificaciones(fecha_calificacion);
CREATE INDEX idx_usuario_tipo ON usuarios(tipo_usuario);
CREATE INDEX idx_usuario_estado ON usuarios(estado);



-- Insertar maestros de ejemplo
INSERT INTO maestros (nombre, apellido, email, telefono, especialidad, fecha_contratacion, estado) VALUES
('Juan', 'García', 'juan.garcia@escuela.com', '5551234567', 'Matemáticas', '2023-01-15', 'activo'),
('María', 'López', 'maria.lopez@escuela.com', '5552345678', 'Literatura', '2023-02-20', 'activo'),
('Carlos', 'Rodríguez', 'carlos.rodriguez@escuela.com', '5553456789', 'Ciencias', '2023-03-10', 'activo');

-- Insertar alumnos de ejemplo
INSERT INTO alumnos (matricula, nombre, apellido, email, telefono, fecha_nacimiento, genero, domicilio, ciudad, estado_provincia, codigo_postal, fecha_inscripcion, estado) VALUES
('MAT001', 'Pedro', 'Martínez', 'pedro.martinez@email.com', '5554567890', '2005-05-15', 'M', 'Calle Principal 123', 'México', 'CDMX', '28001', '2024-09-01', 'activo'),
('MAT002', 'Ana', 'Hernández', 'ana.hernandez@email.com', '5555678901', '2005-08-22', 'F', 'Avenida Secundaria 456', 'México', 'CDMX', '28002', '2024-09-01', 'activo'),
('MAT003', 'Luis', 'González', 'luis.gonzalez@email.com', '5556789012', '2006-03-10', 'M', 'Calle Tercera 789', 'México', 'CDMX', '28003', '2024-09-01', 'activo');

-- Insertar asignaturas de ejemplo
INSERT INTO asignaturas (nombre, codigo, descripcion, creditos, id_maestro, horario, salon, capacidad, estado) VALUES
('Matemáticas I', 'MAT101', 'Introducción al Cálculo', 4, 1, 'Lunes-Miércoles 08:00-10:00', 'Aula 101', 30, 'activa'),
('Literatura Española', 'LIT201', 'Historia y análisis de la literatura española', 3, 2, 'Martes-Jueves 10:00-12:00', 'Aula 202', 25, 'activa'),
('Biología General', 'BIO101', 'Conceptos básicos de biología', 4, 3, 'Lunes-Miércoles 14:00-16:00', 'Aula 305', 28, 'activa');

-- Insertar inscripciones de ejemplo
INSERT INTO inscripciones (id_alumno, id_asignatura, fecha_inscripcion, estado) VALUES
(1, 1, '2024-09-01', 'inscrito'),
(1, 2, '2024-09-01', 'inscrito'),
(2, 1, '2024-09-01', 'inscrito'),
(2, 3, '2024-09-01', 'inscrito'),
(3, 2, '2024-09-01', 'inscrito'),
(3, 3, '2024-09-01', 'inscrito');

-- Insertar calificaciones de ejemplo
INSERT INTO calificaciones (id_inscripcion, id_asignatura, id_maestro, tipo_evaluacion, calificacion, fecha_calificacion, comentarios) VALUES
(1, 1, 1, 'Parcial 1', 85.50, '2024-10-10', 'Buen desempeño'),
(1, 1, 1, 'Parcial 2', 88.00, '2024-11-15', 'Mejora notable'),
(2, 2, 2, 'Parcial 1', 90.00, '2024-10-12', 'Excelente trabajo'),
(3, 1, 1, 'Parcial 1', 76.50, '2024-10-10', 'Necesita refuerzo');

-- Insertar usuarios de ejemplo
INSERT INTO usuarios (nombre_usuario, contraseña, email, tipo_usuario, id_maestro, id_alumno, estado) VALUES
('admin', SHA2('admin123', 256), 'admin@escuela.com', 'administrador', NULL, NULL, 'activo'),
('jgarcia', SHA2('password123', 256), 'juan.garcia@escuela.com', 'maestro', 1, NULL, 'activo'),
('pmartinez', SHA2('password123', 256), 'pedro.martinez@email.com', 'alumno', NULL, 1, 'activo'),
('ahernadez', SHA2('password123', 256), 'ana.hernandez@email.com', 'alumno', NULL, 2, 'activo');
