# 5. TABLA AULAS (Corregido PK)
CREATE TABLE aulas(
    id      INT AUTO_INCREMENT PRIMARY KEY,
    nombre  VARCHAR(100) NOT NULL,
    letra   CHAR(1) NOT NULL,
    numero  INT NOT NULL UNIQUE,
    planta  CHAR(1), # [P]rimera, [S]egunda, [T]ercera

    # DATOS AUDITORÍA
    usuario_alta      VARCHAR(255),
    ip_alta           CHAR(15),
    fecha_sis_alta    TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    usuario_modi      VARCHAR(255),
    ip_modi           CHAR(15),
    fecha_modi        TIMESTAMP
);


# #############################
# 2.1 AULAS
# #############################
INSERT INTO aulas (id, nombre, letra, numero, planta, usuario_alta, ip_alta) VALUES
(1, 'Aula Informática 1', 'D', 101, 'P', 'ADMIN', '127.0.0.1'), # Para 1 DAW
(2, 'Aula Informática 2', 'I', 102, 'P', 'ADMIN', '127.0.0.1'), # Para 2 DAW
(3, 'Taller Hardware 1', 'I', 201, 'S', 'ADMIN', '127.0.0.1'),  # Para 1 SMR
(4, 'Taller Redes 1', 'D', 202, 'S', 'ADMIN', '127.0.0.1');     # Para 2 SMR