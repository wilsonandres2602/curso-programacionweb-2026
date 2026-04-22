-- =====================================================
--  ACTIVIDAD SEMANA 12 — Script SQL completo
--  Base de datos: curso_db
-- =====================================================

USE curso_db;

-- ===== TABLA: paises =====
CREATE TABLE IF NOT EXISTS paises (
    id     INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL
);

-- ===== TABLA: ciudades =====
CREATE TABLE IF NOT EXISTS ciudades (
    id      INT AUTO_INCREMENT PRIMARY KEY,
    nombre  VARCHAR(100) NOT NULL,
    id_pais INT NOT NULL,
    FOREIGN KEY (id_pais) REFERENCES paises(id)
);

-- ===== TABLA: registros_viajes =====
CREATE TABLE IF NOT EXISTS registros_viajes (
    id             INT AUTO_INCREMENT PRIMARY KEY,
    nombre_usuario VARCHAR(100) NOT NULL,
    id_pais        INT NOT NULL,
    id_ciudad      INT NOT NULL,
    fecha          TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_pais)   REFERENCES paises(id),
    FOREIGN KEY (id_ciudad) REFERENCES ciudades(id)
);

-- ===== DATOS: 3 países =====
INSERT INTO paises (nombre) VALUES
    ('Colombia'),
    ('México'),
    ('Argentina');

-- ===== DATOS: 5+ ciudades =====
INSERT INTO ciudades (nombre, id_pais) VALUES
    ('Bogotá',          1),
    ('Medellín',        1),
    ('Cali',            1),
    ('Ciudad de México', 2),
    ('Guadalajara',     2),
    ('Buenos Aires',    3),
    ('Córdoba',         3);
