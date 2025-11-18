
CREATE DATABASE IF NOT EXISTS control_escolar;
USE control_escolar;


-- TABLA: Usuarios del Sistema

CREATE TABLE usuarios (
    id_usuario INT PRIMARY KEY AUTO_INCREMENT,
    nombre_usuario VARCHAR(40) UNIQUE NOT NULL,
    contraseña VARCHAR(64) NOT NULL,
    email VARCHAR(40) UNIQUE NOT NULL,
    tipo_usuario ENUM('administrador', 'maestro', 'alumno') NOT NULL
);


-- TABLA: Maestros

CREATE TABLE maestros (
    id_maestro INT PRIMARY KEY AUTO_INCREMENT,
   id_usuario INT NOT NULL,
    nombre VARCHAR(40) NOT NULL,
    apellido VARCHAR(40) NOT NULL,
    email VARCHAR(40) UNIQUE NOT NULL,
    telefono VARCHAR(10),
    especialidad VARCHAR(30),
    estado ENUM('activo', 'inactivo') DEFAULT 'activo',
     FOREIGN KEY (id_usuario) REFERENCES usuarios(id_usuario) ON DELETE CASCADE
);


-- TABLA: Alumnos

CREATE TABLE alumnos (
    id_alumno INT PRIMARY KEY AUTO_INCREMENT,
	id_usuario INT NOT NULL,
    matricula VARCHAR(20) UNIQUE NOT NULL,
    nombre VARCHAR(30) NOT NULL,
    
    apellido VARCHAR(30) NOT NULL,
    email VARCHAR(30) unique not null,
    telefono VARCHAR(15),
    fecha_nacimiento DATE,
    estado ENUM('activo', 'inactivo') DEFAULT 'activo',
     carrera VARCHAR(30),
     FOREIGN KEY (id_usuario) REFERENCES usuarios(id_usuario) ON DELETE CASCADE
);




-- TABLA: Asignaturas/Cursos

CREATE TABLE asignaturas (
    id_asignatura INT PRIMARY KEY AUTO_INCREMENT,
    nombre VARCHAR(30) NOT NULL,
    codigo VARCHAR(20) UNIQUE NOT NULL,
    descripcion TEXT,
    creditos INT,
    id_maestro INT NOT NULL,
    horario VARCHAR(10),
    salon VARCHAR(5),
    capacidad INT DEFAULT 30,
    estado ENUM('activa', 'inactiva') DEFAULT 'activa',
    FOREIGN KEY (id_maestro) REFERENCES maestros(id_maestro) ON DELETE RESTRICT
);


-- TABLA: Inscripción de Alumnos en Asignaturas

CREATE TABLE inscripciones (
    id_inscripcion INT PRIMARY KEY AUTO_INCREMENT,
    id_alumno INT NOT NULL,
    id_asignatura INT NOT NULL,
    FOREIGN KEY (id_alumno) REFERENCES alumnos(id_alumno) ON DELETE CASCADE,
    FOREIGN KEY (id_asignatura) REFERENCES asignaturas(id_asignatura) ON DELETE CASCADE
);


-- TABLA: Calificaciones

CREATE TABLE calificaciones (
    id_calificacion INT PRIMARY KEY AUTO_INCREMENT,
    id_inscripcion INT NOT NULL,
    tipo_evaluacion ENUM('Parcial 1', 'Parcial 2', 'Parcial 3') NOT NULL,
    calificacion DECIMAL(5,2) CHECK (calificacion BETWEEN 0 AND 100),
    fecha_calificacion DATE DEFAULT (CURRENT_DATE),    
    -- Evita duplicar el mismo parcial para una misma inscripción
    UNIQUE KEY unique_calif (id_inscripcion, tipo_evaluacion),
    
    FOREIGN KEY (id_inscripcion) REFERENCES inscripciones(id_inscripcion)
        ON DELETE CASCADE
);



-- TABLA: Promedio de Asignaturas

CREATE TABLE promedios (
    id_promedio INT PRIMARY KEY AUTO_INCREMENT,
    id_alumno INT NOT NULL,
    id_asignatura INT NOT NULL,
    promedio DECIMAL(5,2) CHECK (promedio BETWEEN 0 AND 100),
    estado_asignatura ENUM('Aprobado', 'Reprobado') DEFAULT NULL,
    fecha_calculo TIMESTAMP DEFAULT CURRENT_TIMESTAMP 
        ON UPDATE CURRENT_TIMESTAMP,
    
    -- Evita tener duplicados del mismo alumno y materia
    UNIQUE KEY unique_alumno_asig_promedio (id_alumno, id_asignatura),
    
    FOREIGN KEY (id_alumno) REFERENCES alumnos(id_alumno) 
        ON DELETE CASCADE,
    FOREIGN KEY (id_asignatura) REFERENCES asignaturas(id_asignatura) 
        ON DELETE CASCADE
);



