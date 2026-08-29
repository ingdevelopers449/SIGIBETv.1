CREATE DATABASE IF NOT EXISTS sigibet_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE sigibet_db;

CREATE TABLE IF NOT EXISTS roles (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nombre VARCHAR(50) NOT NULL UNIQUE,
  descripcion VARCHAR(255) NULL,
  estado TINYINT DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS usuarios (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nombre VARCHAR(100) NOT NULL,
  usuario VARCHAR(50) NOT NULL UNIQUE,
  email VARCHAR(100) NULL,
  telefono VARCHAR(30) NULL,
  password VARCHAR(255) NOT NULL,
  rol_id INT NOT NULL DEFAULT 2,
  intentos_fallidos INT DEFAULT 0,
  bloqueado_hasta DATETIME NULL,
  estado TINYINT DEFAULT 1,
  fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (rol_id) REFERENCES roles(id) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS productos (
  id INT AUTO_INCREMENT PRIMARY KEY,
  referencia VARCHAR(50) NOT NULL UNIQUE,
  nombre VARCHAR(120) NOT NULL,
  color VARCHAR(50) NOT NULL DEFAULT 'Palo Rosa',
  categoria VARCHAR(80) NOT NULL DEFAULT 'Moda Femenina',
  descripcion TEXT NULL,
  precio DECIMAL(10,2) NOT NULL DEFAULT 0.00,
  precio_compra DECIMAL(10,2) NOT NULL DEFAULT 0.00,
  stock INT NOT NULL DEFAULT 0,
  stock_minimo INT NOT NULL DEFAULT 5,
  estado TINYINT DEFAULT 1,
  fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS clientes (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nombre VARCHAR(100) NOT NULL,
  documento VARCHAR(30) NOT NULL UNIQUE,
  telefono VARCHAR(30) NULL,
  email VARCHAR(100) NULL,
  direccion VARCHAR(150) NULL,
  fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS ventas (
  id INT AUTO_INCREMENT PRIMARY KEY,
  codigo_factura VARCHAR(30) NOT NULL UNIQUE,
  usuario_id INT NOT NULL,
  cliente_id INT NULL,
  subtotal DECIMAL(10,2) NOT NULL DEFAULT 0.00,
  total DECIMAL(10,2) NOT NULL,
  metodo_pago VARCHAR(50) DEFAULT 'Efectivo',
  fecha TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS detalle_ventas (
  id INT AUTO_INCREMENT PRIMARY KEY,
  venta_id INT NOT NULL,
  producto_id INT NOT NULL,
  cantidad INT NOT NULL,
  precio_unitario DECIMAL(10,2) NOT NULL,
  subtotal DECIMAL(10,2) NOT NULL,
  FOREIGN KEY (venta_id) REFERENCES ventas(id) ON DELETE CASCADE,
  FOREIGN KEY (producto_id) REFERENCES productos(id) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS movimientos (
  id INT AUTO_INCREMENT PRIMARY KEY,
  producto_id INT NOT NULL,
  usuario_id INT NOT NULL,
  tipo ENUM('ENTRADA', 'SALIDA_VENTA', 'AJUSTE', 'CREACION') NOT NULL,
  cantidad INT NOT NULL,
  stock_anterior INT NOT NULL,
  stock_nuevo INT NOT NULL,
  motivo VARCHAR(255) NOT NULL,
  fecha TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (producto_id) REFERENCES productos(id) ON DELETE RESTRICT,
  FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS notificaciones (
  id INT AUTO_INCREMENT PRIMARY KEY,
  producto_id INT NOT NULL,
  usuario_id INT NOT NULL,
  mensaje VARCHAR(255) NOT NULL,
  leida TINYINT DEFAULT 0,
  fecha TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (producto_id) REFERENCES productos(id) ON DELETE RESTRICT,
  FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS configuracion (
  id INT PRIMARY KEY DEFAULT 1,
  nombre_empresa VARCHAR(100) DEFAULT 'SIGIBET Boutique',
  nit VARCHAR(30) DEFAULT '901.458.789-1',
  telefono VARCHAR(30) DEFAULT '+57 300 123 4567',
  email VARCHAR(100) DEFAULT 'contacto@sigibet.com',
  direccion VARCHAR(150) DEFAULT 'Calle 10 # 43E-15, Medellín',
  tema_colores TEXT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS auditoria (
  id INT AUTO_INCREMENT PRIMARY KEY,
  usuario_id INT NOT NULL,
  modulo VARCHAR(50) NOT NULL,
  accion VARCHAR(50) NOT NULL,
  detalles TEXT NULL,
  fecha TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------
-- INSERCIÓN DE DATOS INICIALES (SEMILLA)
-- --------------------------------------------------------

-- 1. Insertar roles (id 1 = Administrador, id 2 = Empleado)
INSERT IGNORE INTO roles (id, nombre, descripcion) VALUES 
(1, 'Administrador', 'Acceso total al sistema'),
(2, 'Empleado', 'Acceso limitado a facturación y caja');

-- 2. Insertar usuario Administrador
-- Credenciales -> Email: admin@gamil.com / Password: admin123 / Usuario: admin
INSERT IGNORE INTO usuarios (nombre, usuario, email, telefono, password, rol_id, estado) VALUES 
('Administrador General', 'admin', 'admin@gamil.com', '3000000000', '$2y$10$LrpiIW3iCGHoeWXn8R4axuONwll/8gEq.kYLgfbEVflhftoqwvcXG', 1, 1);