-- Dolice Decoration - initial schema

CREATE DATABASE IF NOT EXISTS dolice_decoration
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE dolice_decoration;

CREATE TABLE IF NOT EXISTS users (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(120) NOT NULL,
  email VARCHAR(190) NOT NULL,
  role ENUM('admin','agent','read') NOT NULL DEFAULT 'admin',
  password_hash VARCHAR(255) NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  UNIQUE KEY uq_users_email (email)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Default admin: admin@dolice.local / Admin@1234
INSERT INTO users (name, email, role, password_hash)
VALUES ('Admin', 'admin@dolice.local', 'admin', '$2y$10$GZzSQ5V7LZ6uK7W8Y3O3mO1Cxb0lHynR8r0xWmY8VxL2QdQyP0Z3y')
ON DUPLICATE KEY UPDATE email = email;
