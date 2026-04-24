-- ============================================================
--  SCRIPT SQL COMPLETO - base de datos: mi_proyecto
--  INSTRUCCIONES:
--  1. Abre phpMyAdmin → selecciona "mi_proyecto"
--  2. Pestaña SQL → pega todo esto → Ejecutar
-- ============================================================

USE mi_proyecto;

-- ============================================================
-- RÚBRICA #2 - Tabla paises (id, nombre)
-- ============================================================
CREATE TABLE IF NOT EXISTS paises (
    id     INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL
);

-- ============================================================
-- RÚBRICA #2 - Tabla ciudades (id, nombre, id_pais)
-- ============================================================
CREATE TABLE IF NOT EXISTS ciudades (
    id      INT AUTO_INCREMENT PRIMARY KEY,
    nombre  VARCHAR(100) NOT NULL,
    id_pais INT NOT NULL,
    FOREIGN KEY (id_pais) REFERENCES paises(id)
);

-- ============================================================
-- RÚBRICA #3 - Tabla donde se insertan los registros del formulario
-- ============================================================
CREATE TABLE IF NOT EXISTS registros_viajes (
    id             INT AUTO_INCREMENT PRIMARY KEY,
    nombre_usuario VARCHAR(100) NOT NULL,
    id_pais        INT NOT NULL,
    id_ciudad      INT NOT NULL,
    fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_pais)   REFERENCES paises(id),
    FOREIGN KEY (id_ciudad) REFERENCES ciudades(id)
);

-- ============================================================
-- RÚBRICA #2 - Insertar 3 países
-- ============================================================
INSERT INTO paises (nombre) VALUES
    ('Colombia'),
    ('México'),
    ('Argentina');

-- ============================================================
-- RÚBRICA #2 - Insertar 5 ciudades
-- Colombia=1 | México=2 | Argentina=3
-- ============================================================
INSERT INTO ciudades (nombre, id_pais) VALUES
    ('Medellín',         1),
    ('Bogotá',           1),
    ('Ciudad de México', 2),
    ('Guadalajara',      2),
    ('Buenos Aires',     3);
