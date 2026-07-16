CREATE DATABASE IF NOT EXISTS fries_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE fries_db;

CREATE TABLE IF NOT EXISTS usuarios (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nombre VARCHAR(100) NOT NULL,
  email VARCHAR(150) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL,
  rol ENUM('admin','user') NOT NULL DEFAULT 'user',
  estado TINYINT(1) NOT NULL DEFAULT 1,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS ventas (
  id INT AUTO_INCREMENT PRIMARY KEY,
  cliente VARCHAR(150) DEFAULT NULL,
  descripcion VARCHAR(255) NOT NULL,
  monto DECIMAL(10,2) NOT NULL,
  fecha DATE NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

INSERT INTO usuarios (nombre, email, password, rol, estado) VALUES
('Administrador', 'admin@fries.com', '123456', 'admin', 1),
('Usuario Demo', 'user@fries.com', '123456', 'user', 1);

INSERT INTO ventas (cliente, descripcion, monto, fecha) VALUES
('Ana', 'Pedido de combo', 18.50, CURDATE()),
('Luis', 'Envío express', 24.00, CURDATE()),
('Marta', 'Dos hamburguesas', 32.75, DATE_SUB(CURDATE(), INTERVAL 1 DAY));
