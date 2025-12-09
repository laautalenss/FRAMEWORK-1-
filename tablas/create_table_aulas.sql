#\. /var/www/html/lanzarote.lan/tablas/create_table_aulas.sql

DROP TABLE if exists aula;
CREATE TABLE aula(
    id      INT AUTO_INCREMENT PRIMARY KEY,
    nombre  VARCHAR(100) NOT NULL,
    letra   CHAR(1) NOT NULL,
    numero  INT NOT NULL UNIQUE,
    planta  CHAR(1), # [P]rimera, [S]egunda, [T]ercera
    fecha_alta    DATE DEFAULT (CURRENT_DATE),
    fecha_baja    DATE DEFAULT ("99991231"),

    # DATOS AUDITORÍA
    aula_alta      VARCHAR(255),
    ip_alta           CHAR(15),
    fecha_sis_alta    TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    aula_modi      VARCHAR(255),
    ip_modi           CHAR(15),
    fecha_modi        TIMESTAMP
);