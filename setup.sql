-- ============================================
--  SETUP COMPLETO — mi_proyecto
--  Ejecuta este archivo en phpMyAdmin o MySQL
-- ============================================

CREATE DATABASE IF NOT EXISTS mi_proyecto CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE mi_proyecto;

-- Tabla de usuarios (login)
CREATE TABLE IF NOT EXISTS usuarios (
    id       INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50)  NOT NULL UNIQUE,
    password VARCHAR(100) NOT NULL
);

INSERT IGNORE INTO usuarios (username, password) VALUES
    ('admin', '1234'),
    ('usuario', 'pass');

-- Tabla de países
CREATE TABLE IF NOT EXISTS paises (
    id     INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL
);

INSERT IGNORE INTO paises (nombre) VALUES
    ('Colombia'),
    ('México'),
    ('Argentina'),
    ('España'),
    ('Chile');

-- Tabla de ciudades
CREATE TABLE IF NOT EXISTS ciudades (
    id      INT AUTO_INCREMENT PRIMARY KEY,
    nombre  VARCHAR(100) NOT NULL,
    id_pais INT NOT NULL,
    FOREIGN KEY (id_pais) REFERENCES paises(id)
);

INSERT IGNORE INTO ciudades (nombre, id_pais) VALUES
    ('Bogotá',        1),
    ('Medellín',      1),
    ('Cali',          1),
    ('Ciudad de México', 2),
    ('Guadalajara',   2),
    ('Buenos Aires',  3),
    ('Córdoba',       3),
    ('Madrid',        4),
    ('Barcelona',     4),
    ('Santiago',      5);

-- Tabla de registros de viajes
CREATE TABLE IF NOT EXISTS registros_viajes (
    id             INT AUTO_INCREMENT PRIMARY KEY,
    nombre_usuario VARCHAR(100) NOT NULL,
    id_pais        INT NOT NULL,
    id_ciudad      INT NOT NULL,
    fecha          TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
