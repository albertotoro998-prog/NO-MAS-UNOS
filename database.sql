-- ========================================================
-- Script de Creación de Base de Datos para "No Más Unos"
-- Motor: MySQL / MariaDB (Compatible con XAMPP, phpMyAdmin, WampServer)
-- ========================================================

CREATE DATABASE IF NOT EXISTS `nomasunos`
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE `nomasunos`;

-- --------------------------------------------------------
-- 1. Tabla: datos (Usuarios del sistema)
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `datos` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `nombre` VARCHAR(150) NOT NULL,
  `correo` VARCHAR(150) NOT NULL UNIQUE,
  `telefono` VARCHAR(20) NOT NULL,
  `password` VARCHAR(255) NOT NULL,
  `edad` INT NULL,
  `colegio` VARCHAR(150) NULL,
  `grado` VARCHAR(50) NULL,
  `bio` TEXT NULL,
  `foto` VARCHAR(255) NULL,
  `creado_en` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- 2. Tabla: tareas (Gestión de entregas y pendientes)
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `tareas` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `usuario_correo` VARCHAR(150) NOT NULL,
  `nombre` VARCHAR(255) NOT NULL,
  `materia` VARCHAR(100) DEFAULT 'General',
  `fecha_limite` DATE NULL,
  `hora_limite` TIME NULL,
  `prioridad` VARCHAR(20) DEFAULT 'media',
  `descripcion` TEXT NULL,
  `estado` VARCHAR(30) DEFAULT 'pendiente',
  `creado_en` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  INDEX `idx_tareas_usuario` (`usuario_correo`),
  CONSTRAINT `fk_tareas_usuario` FOREIGN KEY (`usuario_correo`)
    REFERENCES `datos` (`correo`)
    ON DELETE CASCADE
    ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- 3. Tabla: proyectos (Proyectos académicos)
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `proyectos` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `usuario_correo` VARCHAR(150) NOT NULL,
  `nombre` VARCHAR(255) NOT NULL,
  `materia` VARCHAR(100) DEFAULT 'General',
  `fecha_entrega` DATE NULL,
  `prioridad` VARCHAR(20) DEFAULT 'media',
  `descripcion` TEXT NULL,
  `estado` VARCHAR(30) DEFAULT 'planificacion',
  `creado_en` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  INDEX `idx_proyectos_usuario` (`usuario_correo`),
  CONSTRAINT `fk_proyectos_usuario` FOREIGN KEY (`usuario_correo`)
    REFERENCES `datos` (`correo`)
    ON DELETE CASCADE
    ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- 4. Tabla: notas (Calificaciones y firmas de acudientes)
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `notas` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `usuario_correo` VARCHAR(150) NOT NULL,
  `materia` VARCHAR(100) NOT NULL,
  `descripcion` TEXT NULL,
  `tipo` ENUM('nota', 'firma') DEFAULT 'nota',
  `valor` DECIMAL(3, 2) NULL,
  `firmado` TINYINT(1) DEFAULT 0,
  `creado_en` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  INDEX `idx_notas_usuario` (`usuario_correo`),
  CONSTRAINT `fk_notas_usuario` FOREIGN KEY (`usuario_correo`)
    REFERENCES `datos` (`correo`)
    ON DELETE CASCADE
    ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- 5. Tabla: mensajes (Bandeja de avisos / notificaciones)
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `mensajes` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `usuario_correo` VARCHAR(150) NOT NULL,
  `titulo` VARCHAR(255) NOT NULL,
  `contenido` TEXT NULL,
  `leido` TINYINT(1) DEFAULT 0,
  `creado_en` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  INDEX `idx_mensajes_usuario` (`usuario_correo`),
  CONSTRAINT `fk_mensajes_usuario` FOREIGN KEY (`usuario_correo`)
    REFERENCES `datos` (`correo`)
    ON DELETE CASCADE
    ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
