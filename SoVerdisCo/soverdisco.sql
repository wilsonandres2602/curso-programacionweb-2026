-- ============================================================
--  SoVerdisCo — Base de datos
--  Servidor: XAMPP / MySQL
--  Codificación: UTF-8
-- ============================================================

CREATE DATABASE IF NOT EXISTS soverdisco CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE soverdisco;

-- ------------------------------------------------------------
-- Tabla: categorias
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS categorias (
    id        INT AUTO_INCREMENT PRIMARY KEY,
    nombre    VARCHAR(80)  NOT NULL,
    slug      VARCHAR(80)  NOT NULL UNIQUE,
    color     VARCHAR(7)   NOT NULL DEFAULT '#1B8A5A'
) ENGINE=InnoDB;

INSERT INTO categorias (nombre, slug, color) VALUES
('Proyectos',          'proyectos',          '#1B8A5A'),
('Política energética','politica-energetica','#0D6EFD'),
('Tecnología solar',   'tecnologia-solar',   '#F5A623'),
('Comunidades',        'comunidades',        '#6f42c1'),
('Investigación',      'investigacion',      '#20c997');

-- ------------------------------------------------------------
-- Tabla: usuarios (administradores)
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS usuarios (
    id            INT AUTO_INCREMENT PRIMARY KEY,
    nombre        VARCHAR(100) NOT NULL,
    email         VARCHAR(150) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    rol           ENUM('admin','editor') NOT NULL DEFAULT 'editor',
    creado_en     DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Contraseña por defecto: Admin2024$ (cambiar en producción)
INSERT INTO usuarios (nombre, email, password_hash, rol) VALUES
('Administrador', 'admin@soverdisco.co',
 '$2y$12$QKJNfhV9pCHt8xA3e2Lf0OQv7W1RkPmbXGd4iYnsHtZq5uT6cyBjq', 'admin');

-- ------------------------------------------------------------
-- Tabla: articulos
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS articulos (
    id            INT AUTO_INCREMENT PRIMARY KEY,
    titulo        VARCHAR(255) NOT NULL,
    slug          VARCHAR(255) NOT NULL UNIQUE,
    resumen       TEXT         NOT NULL,
    contenido     LONGTEXT     NOT NULL,
    imagen_url    VARCHAR(500) NOT NULL DEFAULT 'assets/img/default.jpg',
    categoria_id  INT          NOT NULL,
    autor_id      INT          NOT NULL,
    estado        ENUM('publicado','borrador') NOT NULL DEFAULT 'borrador',
    vistas        INT          NOT NULL DEFAULT 0,
    creado_en     DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
    actualizado_en DATETIME    NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (categoria_id) REFERENCES categorias(id),
    FOREIGN KEY (autor_id)     REFERENCES usuarios(id)
) ENGINE=InnoDB;

INSERT INTO articulos (titulo, slug, resumen, contenido, imagen_url, categoria_id, autor_id, estado, vistas) VALUES
(
  'Colombia alcanza 1.594 MW de capacidad solar instalada',
  'colombia-alcanza-1594-mw-capacidad-solar',
  'El país superó una histórica barrera en generación fotovoltaica, consolidándose como uno de los mercados de mayor crecimiento en Latinoamérica.',
  '<p>Colombia cerró el año con un hito energético sin precedentes: <strong>1.594 MW de capacidad fotovoltaica instalada</strong>, equivalente al 7,6% de la matriz eléctrica nacional. Este resultado representa un crecimiento del 187% respecto al año anterior.</p><p>El parque fotovoltaico Guayepo, ubicado en Ponedera, Atlántico, con 486 MW y más de 820.000 paneles, se consolidó como el más grande de América Latina y el motor central de este crecimiento.</p><p>La UPME estima que entre 2025 y 2033 podrían entrar en operación hasta 13,5 GW adicionales de proyectos solares actualmente aprobados, lo que posicionaría a Colombia entre los cinco mercados fotovoltaicos más dinámicos del continente.</p>',
  'assets/img/noticias/guayepo.jpg',
  1, 1, 'publicado', 245
),
(
  'Ley 1715: diez años impulsando la transición energética colombiana',
  'ley-1715-diez-anos-transicion-energetica',
  'Una década después de su promulgación, la Ley 1715 sigue siendo el pilar normativo que sostiene la inversión en energías renovables no convencionales en Colombia.',
  '<p>La <strong>Ley 1715 de 2014</strong> fue el primer instrumento normativo estructurado para incentivar el desarrollo de fuentes no convencionales de energía renovable (FNCER) en Colombia. Su impacto se mide en miles de millones de dólares de inversión movilizada y en la transformación radical de la oferta energética del país.</p><p>Los incentivos tributarios que introdujo — deducción en renta del 50% sobre la inversión, exclusión de IVA y exención de arancel para equipos — redujeron significativamente el período de retorno de los proyectos fotovoltaicos, de 12 años a menos de 7 en algunos casos.</p><p>La Ley 2099 de 2021 complementó este marco ampliando las metas de transición y eliminando barreras administrativas que frenaban la conexión de proyectos de pequeña escala.</p>',
  'assets/img/noticias/ley1715.jpg',
  2, 1, 'publicado', 189
),
(
  'Perovskita y silicio: la próxima generación de paneles solares llega a Colombia',
  'perovskita-silicio-proxima-generacion-paneles',
  'Investigadores de la Universidad Nacional de Colombia estudian celdas tándem de perovskita-silicio con eficiencias superiores al 30%, que podrían reducir el costo de generación solar en un 40%.',
  '<p>El laboratorio de Energía Solar de la Universidad Nacional sede Medellín trabaja en la caracterización de celdas tándem <strong>perovskita-silicio</strong> bajo las condiciones de irradiación específicas del trópico colombiano. Las eficiencias registradas en pruebas de laboratorio superan el 30%, frente al 22% promedio de los paneles monocristalinos comerciales actuales.</p><p>La relevancia de este desarrollo radica en el contexto colombiano: con una radiación horizontal global (GHI) promedio de 4,5 kWh/m² diarios, un aumento de eficiencia del 40% se traduciría en menores áreas de instalación y menores costos de balance de sistema (BOS), haciendo viable la energía solar en zonas urbanas densas con espacio limitado.</p>',
  'assets/img/noticias/perovskita.jpg',
  3, 1, 'publicado', 312
),
(
  'Comunidades energéticas en La Guajira: autonomía solar para el pueblo Wayuu',
  'comunidades-energeticas-guajira-wayuu',
  'El programa Colombia Solar financia microrredes fotovoltaicas en 14 resguardos indígenas Wayuu, llevando electricidad confiable a más de 8.000 familias en zonas no interconectadas.',
  '<p>Las <strong>Zonas No Interconectadas (ZNI)</strong> de La Guajira albergan a miles de familias que históricamente dependían de costosos generadores diésel para acceder a electricidad. El programa Colombia Solar, respaldado con financiación del Banco Mundial, está cambiando esa realidad.</p><p>Las microrredes instaladas combinan paneles fotovoltaicos de 5 kWp por familia con baterías de litio-ferrofosfato (LFP) de ciclo profundo, garantizando autonomía nocturna de hasta 12 horas. La gestión comunitaria de los sistemas, a través de figuras de Gestores Energéticos Comunitarios capacitados por el SENA, asegura la sostenibilidad operativa a largo plazo.</p>',
  'assets/img/noticias/wayuu.jpg',
  4, 1, 'publicado', 178
),
(
  'Resultados del estudio de atlas solar IDEAM 2024',
  'atlas-solar-ideam-2024',
  'El IDEAM publicó la actualización del atlas de radiación solar colombiano con resolución de 1 km², confirmando el enorme potencial de las regiones Caribe y Orinoquía.',
  '<p>El <strong>Instituto de Hidrología, Meteorología y Estudios Ambientales (IDEAM)</strong> presentó la actualización más detallada del Atlas de Radiación Solar de Colombia, con una resolución espacial de 1 km² basada en datos satelitales de 20 años y 847 estaciones de medición en tierra.</p><p>Los resultados confirman que la región Caribe registra los valores más altos de irradiación horizontal global (GHI), con promedios de 5,2 a 5,8 kWh/m²/día en los departamentos de La Guajira y Atlántico. La región de la Orinoquía emerge como una zona de alto potencial previamente subestimada, con valores de 4,8 a 5,1 kWh/m²/día.</p>',
  'assets/img/noticias/atlas.jpg',
  5, 1, 'borrador', 0
);

-- ------------------------------------------------------------
-- Tabla: proyectos (para el panorama)
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS proyectos (
    id         INT AUTO_INCREMENT PRIMARY KEY,
    nombre     VARCHAR(200) NOT NULL,
    capacidad_mw DECIMAL(8,2) NOT NULL,
    region     VARCHAR(100) NOT NULL,
    departamento VARCHAR(100) NOT NULL,
    municipio  VARCHAR(100) NOT NULL,
    estado     ENUM('operacion','pruebas','construccion','aprobado') NOT NULL,
    empresa    VARCHAR(200) NOT NULL,
    lat        DECIMAL(9,6),
    lng        DECIMAL(9,6),
    anio_inicio INT
) ENGINE=InnoDB;

INSERT INTO proyectos (nombre, capacidad_mw, region, departamento, municipio, estado, empresa, lat, lng, anio_inicio) VALUES
('Guayepo',            486.00, 'Caribe',   'Atlántico',  'Ponedera',         'operacion',  'Enel Green Power', 10.6419, -74.8992, 2024),
('La Loma',            150.00, 'Caribe',   'Cesar',      'El Paso',          'operacion',  'Enel Green Power', 9.6550,  -73.7873, 2022),
('Bosques Solares',    100.00, 'Caribe',   'Atlántico',  'Sabanalarga',      'operacion',  'ISA',              10.6307, -74.9223, 2023),
('Portón del Sol',     102.00, 'Andina',   'Caldas',     'La Dorada',        'operacion',  'EPM',              5.4545,  -74.6643, 2023),
('La Unión',           100.00, 'Caribe',   'Córdoba',    'La Unión',         'operacion',  'Celsia',           8.2897,  -75.8811, 2023),
('Windpeshi Solar',     80.00, 'Caribe',   'La Guajira', 'Uribia',           'pruebas',    'AES Colombia',     11.7086, -72.3286, 2024),
('Acacia 2',            80.00, 'Caribe',   'La Guajira', 'Maicao',           'pruebas',    'Isagén',           11.3847, -72.2411, 2024),
('LATAM Solar La Loma',150.00, 'Caribe',   'Cesar',      'La Loma',          'operacion',  'Trina Solar',      9.7102,  -73.7968, 2023),
('El Paso Solar',      200.00, 'Caribe',   'Cesar',      'El Paso',          'construccion','Enel',            9.6468,  -73.7533, 2025),
('Sol de los Llanos',   60.00, 'Orinoquía','Meta',       'Villavicencio',    'aprobado',   'EPM',              4.1420,  -73.6266, 2026);

-- ------------------------------------------------------------
-- Vista útil para el blog (artículo + categoría + autor)
-- ------------------------------------------------------------
CREATE OR REPLACE VIEW vista_articulos AS
SELECT
    a.id,
    a.titulo,
    a.slug,
    a.resumen,
    a.contenido,
    a.imagen_url,
    a.estado,
    a.vistas,
    a.creado_en,
    a.actualizado_en,
    c.nombre   AS categoria_nombre,
    c.slug     AS categoria_slug,
    c.color    AS categoria_color,
    u.nombre   AS autor_nombre
FROM articulos a
JOIN categorias c ON a.categoria_id = c.id
JOIN usuarios   u ON a.autor_id     = u.id;


-- Tabla para almacenar contenido editable de páginas estáticas
CREATE TABLE IF NOT EXISTS contenido_paginas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    pagina VARCHAR(50) NOT NULL UNIQUE,
    titulo VARCHAR(200) NOT NULL,
    contenido TEXT NOT NULL,
    actualizado_en DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Insertar contenido inicial para Inicio y Energía Solar
INSERT INTO contenido_paginas (pagina, titulo, contenido) VALUES
('inicio', 'Colombia avanza hacia la energía del futuro', 
 '<p>Explora el potencial fotovoltaico de nuestro país: datos reales, proyectos activos, calculadora de ahorro y el marco normativo que impulsa la transición energética.</p>'),
('energia-solar', 'Energía Solar en Colombia', 
 '<p>Comprende cómo funciona la energía fotovoltaica, su potencial en nuestro territorio y las configuraciones de instalación disponibles.</p>');