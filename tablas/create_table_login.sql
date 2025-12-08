CREATE TABLE IF NOT EXISTS usuarios_login (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    nombre VARCHAR(100),
    email VARCHAR(100),
    fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    activo TINYINT(1) DEFAULT 1
);

-- Insertar un usuario de prueba
INSERT INTO usuarios_login (usuario, password, nombre, email) 
VALUES ('admin', 'admin123', 'Administrador', 'admin@instituto.com');