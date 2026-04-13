CREATE DATABASE IF NOT EXISTS playcash_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE playcash_db;

DROP TABLE IF EXISTS misiones;
DROP TABLE IF EXISTS videojuegos;
DROP TABLE IF EXISTS usuarioS_APP;

CREATE TABLE usuarioS_APP (
    id_usuario INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    correo VARCHAR(150) NOT NULL,
    NombreUsuario VARCHAR(100) NOT NULL,
    contrasena VARCHAR(255) NOT NULL,
    rol ENUM('admin', 'usuario') NOT NULL DEFAULT 'usuario',
    UNIQUE KEY uq_usuario_correo (correo)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE videojuegos (
    id_juego INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nombreJuego VARCHAR(150) NOT NULL,
    UNIQUE KEY uq_videojuego_nombre (nombreJuego)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE misiones (
    id_mision INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nombreMision VARCHAR(150) NOT NULL,
    descripcion TEXT NOT NULL,
    recompensa DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    id_juego INT UNSIGNED NOT NULL,
    estado ENUM('pendiente', 'aceptada', 'rechazada', 'cobrada') NOT NULL DEFAULT 'pendiente',
    CONSTRAINT fk_misiones_videojuego
        FOREIGN KEY (id_juego) REFERENCES videojuegos(id_juego)
        ON UPDATE CASCADE
        ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO usuarioS_APP (correo, NombreUsuario, contrasena, rol)
VALUES (
    'admin@playcash.local',
    'Administrador',
    '$2y$12$7Ius5PrBkAa2KGc9yLFGUOIWsY3jIv7zD7jU32SetUFl6Ah3SqJFy',
    'admin'
);

-- Contraseña del administrador por defecto: Admin1234
