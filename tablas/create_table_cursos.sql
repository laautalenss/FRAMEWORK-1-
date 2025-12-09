# 2. TABLA CURSOS
CREATE TABLE cursos (
    id              INT AUTO_INCREMENT PRIMARY KEY,
    nombre_grado    VARCHAR(50) NOT NULL,           # Ej: "DAW", "SMR"
    curso_numero    INT NOT NULL,                   # Ej: 1, 2
    letra           CHAR(1) NOT NULL,               # Ej: 'A'
    id_tutor    INT NULL, 
    FOREIGN KEY (id_tutor) REFERENCES personas(id),
    UNIQUE(nombre_grado, curso_numero, letra),

    # DATOS AUDITORÍA
    usuario_alta      VARCHAR(255),
    ip_alta           CHAR(15),
    fecha_sis_alta    TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    usuario_modi      VARCHAR(255),
    ip_modi           CHAR(15),
    fecha_modi        TIMESTAMP
);

# #############################
# 2.2 CURSOS
# #############################
INSERT INTO cursos (id, nombre_grado, curso_numero, letra, id_tutor, usuario_alta, ip_alta) VALUES
(1, 'DAW', 1, 'A', 1, 'ADMIN', '127.0.0.1'),
(2, 'DAW', 2, 'A', 2, 'ADMIN', '127.0.0.1'),
(3, 'SMR', 1, 'A', 3, 'ADMIN', '127.0.0.1'),
(4, 'SMR', 2, 'A', 4, 'ADMIN', '127.0.0.1');