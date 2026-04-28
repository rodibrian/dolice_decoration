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
VALUES ('Admin', 'admin@dolice.local', 'admin', '$2y$10$QvOC3u9SXVYISUzBZX/WGOV/XEplLzwzUkqYj8k1bpJRjvmeeQ4Iu')
ON DUPLICATE KEY UPDATE email = email;

-- Services
CREATE TABLE IF NOT EXISTS services (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  title VARCHAR(190) NOT NULL,
  slug VARCHAR(190) NOT NULL,
  category VARCHAR(120) NULL,
  description MEDIUMTEXT NULL,
  image_path VARCHAR(255) NULL,
  display_order INT NOT NULL DEFAULT 0,
  is_published TINYINT(1) NOT NULL DEFAULT 1,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  UNIQUE KEY uq_services_slug (slug),
  KEY idx_services_published_order (is_published, display_order, id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Réalisations (projets)
CREATE TABLE IF NOT EXISTS projects (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  title VARCHAR(190) NOT NULL,
  slug VARCHAR(190) NOT NULL,
  category VARCHAR(120) NULL,
  work_type VARCHAR(190) NULL,
  location VARCHAR(190) NULL,
  project_date DATE NULL,
  description MEDIUMTEXT NULL,
  is_featured TINYINT(1) NOT NULL DEFAULT 0,
  status ENUM('draft','published') NOT NULL DEFAULT 'draft',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  UNIQUE KEY uq_projects_slug (slug),
  KEY idx_projects_status (status, is_featured, id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS project_images (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  project_id INT UNSIGNED NOT NULL,
  image_path VARCHAR(255) NOT NULL,
  sort_order INT NOT NULL DEFAULT 0,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  KEY idx_project_images_project (project_id, sort_order, id),
  CONSTRAINT fk_project_images_project
    FOREIGN KEY (project_id) REFERENCES projects(id)
    ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Articles / Blog
CREATE TABLE IF NOT EXISTS posts (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  title VARCHAR(190) NOT NULL,
  slug VARCHAR(190) NOT NULL,
  excerpt TEXT NULL,
  content MEDIUMTEXT NULL,
  featured_image VARCHAR(255) NULL,
  author VARCHAR(120) NULL,
  keywords VARCHAR(255) NULL,
  status ENUM('draft','published') NOT NULL DEFAULT 'draft',
  published_at DATETIME NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  UNIQUE KEY uq_posts_slug (slug),
  KEY idx_posts_status_published_at (status, published_at, id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Témoignages
CREATE TABLE IF NOT EXISTS testimonials (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  client_name VARCHAR(190) NOT NULL,
  client_company VARCHAR(190) NULL,
  content TEXT NOT NULL,
  rating TINYINT UNSIGNED NULL,
  logo_path VARCHAR(255) NULL,
  status ENUM('pending','approved') NOT NULL DEFAULT 'pending',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  KEY idx_testimonials_status (status, id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Demandes de devis
CREATE TABLE IF NOT EXISTS quote_requests (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(190) NOT NULL,
  phone VARCHAR(60) NULL,
  email VARCHAR(190) NULL,
  project_type VARCHAR(190) NULL,
  message TEXT NULL,
  status ENUM('new','in_progress','replied','done','archived') NOT NULL DEFAULT 'new',
  internal_notes TEXT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  KEY idx_quote_requests_status (status, created_at, id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Messages de contact
CREATE TABLE IF NOT EXISTS contact_messages (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(190) NOT NULL,
  email VARCHAR(190) NULL,
  phone VARCHAR(60) NULL,
  subject VARCHAR(190) NULL,
  message TEXT NOT NULL,
  status ENUM('new','read','archived') NOT NULL DEFAULT 'new',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  KEY idx_contact_messages_status (status, created_at, id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Partenaires
CREATE TABLE IF NOT EXISTS partners (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(190) NOT NULL,
  logo_path VARCHAR(255) NULL,
  url VARCHAR(255) NULL,
  category VARCHAR(120) NULL,
  display_order INT NOT NULL DEFAULT 0,
  is_published TINYINT(1) NOT NULL DEFAULT 1,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  KEY idx_partners_published_order (is_published, display_order, id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Pages statiques (contenu éditable)
CREATE TABLE IF NOT EXISTS pages (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  page_key VARCHAR(60) NOT NULL,
  title VARCHAR(190) NOT NULL,
  content MEDIUMTEXT NULL,
  updated_at TIMESTAMP NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  UNIQUE KEY uq_pages_key (page_key)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Paramètres généraux
CREATE TABLE IF NOT EXISTS settings (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  setting_key VARCHAR(120) NOT NULL,
  setting_value MEDIUMTEXT NULL,
  updated_at TIMESTAMP NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  UNIQUE KEY uq_settings_key (setting_key)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
