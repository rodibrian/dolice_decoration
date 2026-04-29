-- Dolice Decoration - schema + données fictives complètes
-- Objectif: remplir toutes les tables avec des données de démonstration.

CREATE DATABASE IF NOT EXISTS dolice_decoration
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE dolice_decoration;

-- ---------------------------------------------------------------------
-- 1) Schéma (copie du schema principal)
-- ---------------------------------------------------------------------

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

CREATE TABLE IF NOT EXISTS pages (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  page_key VARCHAR(60) NOT NULL,
  title VARCHAR(190) NOT NULL,
  content MEDIUMTEXT NULL,
  updated_at TIMESTAMP NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  UNIQUE KEY uq_pages_key (page_key)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS settings (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  setting_key VARCHAR(120) NOT NULL,
  setting_value MEDIUMTEXT NULL,
  updated_at TIMESTAMP NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  UNIQUE KEY uq_settings_key (setting_key)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ---------------------------------------------------------------------
-- 2) Reset données
-- ---------------------------------------------------------------------
-- Note: TRUNCATE peut échouer selon la config FK/phpMyAdmin.
-- On utilise DELETE en ordre FK-safe, puis reset auto_increment.
DELETE FROM project_images;
DELETE FROM projects;
DELETE FROM services;
DELETE FROM posts;
DELETE FROM testimonials;
DELETE FROM quote_requests;
DELETE FROM contact_messages;
DELETE FROM partners;
DELETE FROM pages;
DELETE FROM settings;
DELETE FROM users;

ALTER TABLE project_images AUTO_INCREMENT = 1;
ALTER TABLE projects AUTO_INCREMENT = 1;
ALTER TABLE services AUTO_INCREMENT = 1;
ALTER TABLE posts AUTO_INCREMENT = 1;
ALTER TABLE testimonials AUTO_INCREMENT = 1;
ALTER TABLE quote_requests AUTO_INCREMENT = 1;
ALTER TABLE contact_messages AUTO_INCREMENT = 1;
ALTER TABLE partners AUTO_INCREMENT = 1;
ALTER TABLE pages AUTO_INCREMENT = 1;
ALTER TABLE settings AUTO_INCREMENT = 1;
ALTER TABLE users AUTO_INCREMENT = 1;

-- ---------------------------------------------------------------------
-- 3) Données fictives
-- ---------------------------------------------------------------------

-- Users (mot de passe demo: Admin@1234)
INSERT INTO users (name, email, role, password_hash) VALUES
('Admin Principal', 'admin@dolice.local', 'admin', '$2y$10$QvOC3u9SXVYISUzBZX/WGOV/XEplLzwzUkqYj8k1bpJRjvmeeQ4Iu'),
('Agent Nord', 'agent1@dolice.local', 'agent', '$2y$10$QvOC3u9SXVYISUzBZX/WGOV/XEplLzwzUkqYj8k1bpJRjvmeeQ4Iu'),
('Agent Sud', 'agent2@dolice.local', 'agent', '$2y$10$QvOC3u9SXVYISUzBZX/WGOV/XEplLzwzUkqYj8k1bpJRjvmeeQ4Iu'),
('Consultant', 'read1@dolice.local', 'read', '$2y$10$QvOC3u9SXVYISUzBZX/WGOV/XEplLzwzUkqYj8k1bpJRjvmeeQ4Iu'),
('Superviseur', 'admin2@dolice.local', 'admin', '$2y$10$QvOC3u9SXVYISUzBZX/WGOV/XEplLzwzUkqYj8k1bpJRjvmeeQ4Iu');

-- Services (24)
INSERT INTO services (title, slug, category, description, image_path, display_order, is_published) VALUES
('Plafond design placo', 'plafond-design-placo', 'Plafond', 'Installation de plafonds modernes en placo avec finitions haut de gamme.', '/uploads/placeholders/services/service-01.jpg', 1, 1),
('Faux plafond acoustique', 'faux-plafond-acoustique', 'Plafond', 'Améliore le confort acoustique dans les bureaux et espaces commerciaux.', '/uploads/placeholders/services/service-02.jpg', 2, 1),
('Cloisonnement intérieur', 'cloisonnement-interieur', 'Murs & cloisons', 'Séparation intelligente des espaces avec cloisons robustes.', '/uploads/placeholders/services/service-03.jpg', 3, 1),
('Habillage mural décoratif', 'habillage-mural-decoratif', 'Murs & cloisons', 'Revêtements muraux élégants pour une finition premium.', '/uploads/placeholders/services/service-04.jpg', 4, 1),
('Peinture intérieure premium', 'peinture-interieure-premium', 'Peinture', 'Peinture de qualité professionnelle pour surfaces intérieures.', '/uploads/placeholders/services/service-05.jpg', 5, 1),
('Peinture extérieure durable', 'peinture-exterieure-durable', 'Peinture', 'Protection extérieure contre humidité, UV et intempéries.', '/uploads/placeholders/services/service-06.jpg', 6, 1),
('Pose carrelage sol', 'pose-carrelage-sol', 'Sol', 'Pose de carrelage précis pour maisons, hôtels et commerces.', '/uploads/placeholders/services/service-07.jpg', 7, 1),
('Pose parquet stratifié', 'pose-parquet-stratifie', 'Sol', 'Parquet esthétique et facile d’entretien.', '/uploads/placeholders/services/service-08.jpg', 8, 1),
('Ragréage et nivellement', 'ragreage-et-nivellement', 'Sol', 'Préparation du support pour un sol stable et durable.', '/uploads/placeholders/services/service-09.jpg', 9, 1),
('Installation électrique résidentielle', 'installation-electrique-residentielle', 'Électricité', 'Mise en place complète des circuits électriques maison.', '/uploads/placeholders/services/service-10.jpg', 10, 1),
('Mise aux normes électriques', 'mise-aux-normes-electriques', 'Électricité', 'Audit et correction des installations existantes.', '/uploads/placeholders/services/service-11.jpg', 11, 1),
('Éclairage architectural', 'eclairage-architectural', 'Électricité', 'Conception lumière pour valoriser les volumes et matériaux.', '/uploads/placeholders/services/service-12.jpg', 12, 1),
('Mobilier sur mesure cuisine', 'mobilier-sur-mesure-cuisine', 'Mobilier', 'Conception de meubles cuisine optimisés et modernes.', '/uploads/placeholders/services/service-13.jpg', 13, 1),
('Mobilier dressing personnalisé', 'mobilier-dressing-personnalise', 'Mobilier', 'Dressings sur mesure adaptés à chaque espace.', '/uploads/placeholders/services/service-14.jpg', 14, 1),
('Mobilier bureau professionnel', 'mobilier-bureau-professionnel', 'Mobilier', 'Aménagement mobilier pour bureaux performants.', '/uploads/placeholders/services/service-15.jpg', 15, 1),
('Rénovation salle de bain', 'renovation-salle-de-bain', 'Rénovation', 'Rénovation complète avec finitions soignées.', '/uploads/placeholders/services/service-16.jpg', 16, 1),
('Rénovation appartement complet', 'renovation-appartement-complet', 'Rénovation', 'Pilotage global de la rénovation intérieure.', '/uploads/placeholders/services/service-17.jpg', 17, 1),
('Aménagement open-space', 'amenagement-open-space', 'Bureaux', 'Conception de bureaux ouverts fonctionnels et esthétiques.', '/uploads/placeholders/services/service-18.jpg', 18, 1),
('Aménagement boutique', 'amenagement-boutique', 'Commerces', 'Mise en valeur de l’expérience client en point de vente.', '/uploads/placeholders/services/service-19.jpg', 19, 1),
('Aménagement hôtelier', 'amenagement-hotelier', 'Hôtellerie', 'Travaux d’ambiance et décoration intérieure hôtelière.', '/uploads/placeholders/services/service-20.jpg', 20, 1),
('Pose revêtement mural 3D', 'pose-revetement-mural-3d', 'Décoration', 'Décoration murale moderne avec effet relief.', '/uploads/placeholders/services/service-21.jpg', 21, 1),
('Finition luxe villa', 'finition-luxe-villa', 'Finition', 'Prestations de finition premium pour villas haut standing.', '/uploads/placeholders/services/service-22.jpg', 22, 1),
('Isolation intérieure', 'isolation-interieure', 'Isolation', 'Confort thermique et phonique renforcé.', '/uploads/placeholders/services/service-23.jpg', 23, 1),
('Maintenance post-chantier', 'maintenance-post-chantier', 'Maintenance', 'Service de suivi et corrections après livraison.', '/uploads/placeholders/services/service-24.jpg', 24, 1);

-- Projects (24)
INSERT INTO projects (title, slug, category, work_type, location, project_date, description, is_featured, status) VALUES
('Villa Ivandry - plafonds & peinture', 'villa-ivandry-plafonds-peinture', 'Résidentiel', 'Plafond + peinture', 'Ivandry', '2025-01-12', 'Refonte complète des plafonds et mise en peinture premium.', 1, 'published'),
('Bureau Ankorondrano - open space', 'bureau-ankorondrano-open-space', 'Entreprise', 'Cloisons + éclairage', 'Ankorondrano', '2025-01-20', 'Réaménagement d’un étage complet en open-space moderne.', 1, 'published'),
('Appartement Ambatobe - rénovation', 'appartement-ambatobe-renovation', 'Résidentiel', 'Rénovation globale', 'Ambatobe', '2025-02-05', 'Cuisine, salon, chambres et salles d’eau modernisés.', 0, 'published'),
('Hôtel centre-ville - halls', 'hotel-centre-ville-halls', 'Hôtellerie', 'Décoration intérieure', 'Analakely', '2025-02-18', 'Travaux de finition dans les halls et zones d’accueil.', 1, 'published'),
('Boutique luxe - agencement', 'boutique-luxe-agencement', 'Commerce', 'Agencement + mobilier', 'Tsaralalana', '2025-03-03', 'Parcours client optimisé avec mobilier sur mesure.', 0, 'published'),
('Résidence C - faux plafonds', 'residence-c-faux-plafonds', 'Résidentiel', 'Faux plafonds', 'Androhibe', '2025-03-09', 'Création de faux plafonds avec intégration LED.', 0, 'published'),
('Maison F - parquet & peinture', 'maison-f-parquet-peinture', 'Résidentiel', 'Sol + peinture', 'Ambohijanahary', '2025-03-20', 'Pose parquet et peinture murale ton neutre.', 0, 'published'),
('Siège société K - électricité', 'siege-societe-k-electricite', 'Entreprise', 'Électricité', 'Alarobia', '2025-04-02', 'Mise aux normes et nouvelle distribution électrique.', 0, 'published'),
('Villa M - dressing sur mesure', 'villa-m-dressing-sur-mesure', 'Résidentiel', 'Mobilier', 'Ivato', '2025-04-10', 'Conception et pose de dressings personnalisés.', 0, 'published'),
('Restaurant M - ambiance déco', 'restaurant-m-ambiance-deco', 'Commerce', 'Décoration', 'Antsahavola', '2025-04-26', 'Travaux de décoration pour identité visuelle forte.', 1, 'published'),
('Bureaux P - cloisonnement', 'bureaux-p-cloisonnement', 'Entreprise', 'Cloisons', 'Anosy', '2025-05-06', 'Séparation des espaces de travail et salles réunion.', 0, 'published'),
('Villa R - salle de bain', 'villa-r-salle-de-bain', 'Résidentiel', 'Rénovation SDB', 'Andoharanofotsy', '2025-05-18', 'Rénovation haut de gamme de 2 salles de bain.', 0, 'published'),
('Immeuble T - parties communes', 'immeuble-t-parties-communes', 'Immobilier', 'Finition', '67Ha', '2025-06-02', 'Finitions peintures et habillages des parties communes.', 0, 'published'),
('Clinique U - zones techniques', 'clinique-u-zones-techniques', 'Santé', 'Finition + électricité', 'Ankadifotsy', '2025-06-12', 'Optimisation des espaces techniques et salles.', 0, 'published'),
('Maison W - isolation', 'maison-w-isolation', 'Résidentiel', 'Isolation', 'Ambohibao', '2025-06-28', 'Isolation intérieure complète et confort thermique.', 0, 'published'),
('Showroom X - murs 3D', 'showroom-x-murs-3d', 'Commerce', 'Décoration 3D', 'Behoririka', '2025-07-11', 'Mise en place de revêtements muraux 3D.', 0, 'published'),
('Hôtel Y - suites', 'hotel-y-suites', 'Hôtellerie', 'Finition suites', 'Ivato', '2025-07-21', 'Finition et décoration de 12 suites.', 1, 'published'),
('Bureau Z - éclairage design', 'bureau-z-eclairage-design', 'Entreprise', 'Éclairage', 'Ankadimbahoaka', '2025-08-05', 'Mise en lumière architecturale des espaces.', 0, 'published'),
('Villa AA - cuisine premium', 'villa-aa-cuisine-premium', 'Résidentiel', 'Cuisine sur mesure', 'Itaosy', '2025-08-16', 'Cuisine personnalisée avec rangements optimisés.', 0, 'published'),
('Magasin BB - rénovation sol', 'magasin-bb-renovation-sol', 'Commerce', 'Sol', 'Andravoahangy', '2025-09-01', 'Réfection complète du sol en carrelage technique.', 0, 'published'),
('Résidence CC - maintenance', 'residence-cc-maintenance', 'Résidentiel', 'Maintenance', 'Mahazo', '2025-09-13', 'Corrections et maintenance post-livraison.', 0, 'published'),
('Projet DD - pilote', 'projet-dd-pilote', 'Entreprise', 'Prototype', 'Nanisana', '2025-10-01', 'Projet pilote en phase de tests.', 0, 'draft'),
('Projet EE - étude', 'projet-ee-etude', 'Résidentiel', 'Étude', 'Talatamaty', '2025-10-09', 'Étude de faisabilité avant lancement travaux.', 0, 'draft'),
('Projet FF - attente client', 'projet-ff-attente-client', 'Commerce', 'Préparation', 'Ampefiloha', '2025-10-21', 'Dossier en attente de validation client.', 0, 'draft');

-- 3 images par projet publié (1..21 => 63 images)
INSERT INTO project_images (project_id, image_path, sort_order) VALUES
(1, '/uploads/placeholders/projects/p01-01.jpg', 1),(1, '/uploads/placeholders/projects/p01-02.jpg', 2),(1, '/uploads/placeholders/projects/p01-03.jpg', 3),
(2, '/uploads/placeholders/projects/p02-01.jpg', 1),(2, '/uploads/placeholders/projects/p02-02.jpg', 2),(2, '/uploads/placeholders/projects/p02-03.jpg', 3),
(3, '/uploads/placeholders/projects/p03-01.jpg', 1),(3, '/uploads/placeholders/projects/p03-02.jpg', 2),(3, '/uploads/placeholders/projects/p03-03.jpg', 3),
(4, '/uploads/placeholders/projects/p04-01.jpg', 1),(4, '/uploads/placeholders/projects/p04-02.jpg', 2),(4, '/uploads/placeholders/projects/p04-03.jpg', 3),
(5, '/uploads/placeholders/projects/p05-01.jpg', 1),(5, '/uploads/placeholders/projects/p05-02.jpg', 2),(5, '/uploads/placeholders/projects/p05-03.jpg', 3),
(6, '/uploads/placeholders/projects/p06-01.jpg', 1),(6, '/uploads/placeholders/projects/p06-02.jpg', 2),(6, '/uploads/placeholders/projects/p06-03.jpg', 3),
(7, '/uploads/placeholders/projects/p07-01.jpg', 1),(7, '/uploads/placeholders/projects/p07-02.jpg', 2),(7, '/uploads/placeholders/projects/p07-03.jpg', 3),
(8, '/uploads/placeholders/projects/p08-01.jpg', 1),(8, '/uploads/placeholders/projects/p08-02.jpg', 2),(8, '/uploads/placeholders/projects/p08-03.jpg', 3),
(9, '/uploads/placeholders/projects/p09-01.jpg', 1),(9, '/uploads/placeholders/projects/p09-02.jpg', 2),(9, '/uploads/placeholders/projects/p09-03.jpg', 3),
(10, '/uploads/placeholders/projects/p10-01.jpg', 1),(10, '/uploads/placeholders/projects/p10-02.jpg', 2),(10, '/uploads/placeholders/projects/p10-03.jpg', 3),
(11, '/uploads/placeholders/projects/p11-01.jpg', 1),(11, '/uploads/placeholders/projects/p11-02.jpg', 2),(11, '/uploads/placeholders/projects/p11-03.jpg', 3),
(12, '/uploads/placeholders/projects/p12-01.jpg', 1),(12, '/uploads/placeholders/projects/p12-02.jpg', 2),(12, '/uploads/placeholders/projects/p12-03.jpg', 3),
(13, '/uploads/placeholders/projects/p13-01.jpg', 1),(13, '/uploads/placeholders/projects/p13-02.jpg', 2),(13, '/uploads/placeholders/projects/p13-03.jpg', 3),
(14, '/uploads/placeholders/projects/p14-01.jpg', 1),(14, '/uploads/placeholders/projects/p14-02.jpg', 2),(14, '/uploads/placeholders/projects/p14-03.jpg', 3),
(15, '/uploads/placeholders/projects/p15-01.jpg', 1),(15, '/uploads/placeholders/projects/p15-02.jpg', 2),(15, '/uploads/placeholders/projects/p15-03.jpg', 3),
(16, '/uploads/placeholders/projects/p16-01.jpg', 1),(16, '/uploads/placeholders/projects/p16-02.jpg', 2),(16, '/uploads/placeholders/projects/p16-03.jpg', 3),
(17, '/uploads/placeholders/projects/p17-01.jpg', 1),(17, '/uploads/placeholders/projects/p17-02.jpg', 2),(17, '/uploads/placeholders/projects/p17-03.jpg', 3),
(18, '/uploads/placeholders/projects/p18-01.jpg', 1),(18, '/uploads/placeholders/projects/p18-02.jpg', 2),(18, '/uploads/placeholders/projects/p18-03.jpg', 3),
(19, '/uploads/placeholders/projects/p19-01.jpg', 1),(19, '/uploads/placeholders/projects/p19-02.jpg', 2),(19, '/uploads/placeholders/projects/p19-03.jpg', 3),
(20, '/uploads/placeholders/projects/p20-01.jpg', 1),(20, '/uploads/placeholders/projects/p20-02.jpg', 2),(20, '/uploads/placeholders/projects/p20-03.jpg', 3),
(21, '/uploads/placeholders/projects/p21-01.jpg', 1),(21, '/uploads/placeholders/projects/p21-02.jpg', 2),(21, '/uploads/placeholders/projects/p21-03.jpg', 3);

-- Posts (24)
INSERT INTO posts (title, slug, excerpt, content, featured_image, author, keywords, status, published_at) VALUES
('Choisir la bonne peinture intérieure', 'choisir-bonne-peinture-interieure', 'Guide rapide pour sélectionner la peinture adaptée.', 'Contenu fictif long: conseils, préparation support, finition et entretien.', '/uploads/placeholders/posts/post-01.jpg', 'Equipe Dolice', 'peinture,intérieur,finition', 'published', '2025-01-15 09:00:00'),
('5 erreurs à éviter en rénovation', '5-erreurs-eviter-renovation', 'Les pièges les plus fréquents en rénovation.', 'Contenu fictif long: budget, délais, matériaux, coordination artisans.', '/uploads/placeholders/posts/post-02.jpg', 'Equipe Dolice', 'renovation,conseils', 'published', '2025-01-28 11:30:00'),
('Plafond placo: avantages clés', 'plafond-placo-avantages-cles', 'Pourquoi le placo reste un excellent choix.', 'Contenu fictif long: isolation, esthétique, rapidité de pose.', '/uploads/placeholders/posts/post-03.jpg', 'Equipe Dolice', 'plafond,placo', 'published', '2025-02-06 10:10:00'),
('Comment réussir son éclairage intérieur', 'comment-reussir-eclairage-interieur', 'Astuces pour une ambiance lumineuse efficace.', 'Contenu fictif long sur températures de couleur, zones, intensité.', '/uploads/placeholders/posts/post-04.jpg', 'Equipe Dolice', 'eclairage,interieur', 'published', '2025-02-14 08:45:00'),
('Ragréage: quand et pourquoi ?', 'ragreage-quand-et-pourquoi', 'Comprendre le nivellement des sols.', 'Contenu fictif long sur types de supports et préparation.', '/uploads/placeholders/posts/post-05.jpg', 'Equipe Dolice', 'sol,ragreage', 'published', '2025-02-23 14:20:00'),
('Bien planifier une rénovation complète', 'bien-planifier-renovation-complete', 'Méthode simple de planification travaux.', 'Contenu fictif long sur étapes, jalons et priorités.', '/uploads/placeholders/posts/post-06.jpg', 'Equipe Dolice', 'renovation,planning', 'published', '2025-03-05 09:40:00'),
('Tendance déco minimaliste 2026', 'tendance-deco-minimaliste-2026', 'Tons neutres, textures et lignes propres.', 'Contenu fictif long sur palettes, matériaux et mobilier.', '/uploads/placeholders/posts/post-07.jpg', 'Equipe Dolice', 'deco,tendance', 'published', '2025-03-12 13:15:00'),
('Sécuriser son installation électrique', 'securiser-installation-electrique', 'Bonnes pratiques de sécurité et conformité.', 'Contenu fictif long sur tableau, protections et maintenance.', '/uploads/placeholders/posts/post-08.jpg', 'Equipe Dolice', 'electricite,securite', 'published', '2025-03-25 16:00:00'),
('Comment choisir son parquet', 'comment-choisir-son-parquet', 'Comparatif des options les plus populaires.', 'Contenu fictif long sur résistance, pose et entretien.', '/uploads/placeholders/posts/post-09.jpg', 'Equipe Dolice', 'parquet,sol', 'published', '2025-04-03 10:05:00'),
('Optimiser un petit espace', 'optimiser-petit-espace', 'Astuces d’agencement pour gagner en confort.', 'Contenu fictif long sur rangement et circulation.', '/uploads/placeholders/posts/post-10.jpg', 'Equipe Dolice', 'amenagement,espace', 'published', '2025-04-15 12:10:00'),
('Mur décoratif: idées et coûts', 'mur-decoratif-idees-couts', 'Inspirations et budget estimatif.', 'Contenu fictif long sur matériaux décoratifs.', '/uploads/placeholders/posts/post-11.jpg', 'Equipe Dolice', 'murs,deco', 'published', '2025-04-22 09:00:00'),
('Rénover une salle de bain moderne', 'renover-salle-de-bain-moderne', 'Étapes clés d’une rénovation réussie.', 'Contenu fictif long sur humidité, matériaux et esthétique.', '/uploads/placeholders/posts/post-12.jpg', 'Equipe Dolice', 'salle de bain,renovation', 'published', '2025-05-02 11:00:00'),
('Peinture extérieure: durée de vie', 'peinture-exterieure-duree-vie', 'Facteurs qui influencent la durabilité.', 'Contenu fictif long sur climat et entretien.', '/uploads/placeholders/posts/post-13.jpg', 'Equipe Dolice', 'peinture,exterieur', 'published', '2025-05-13 08:00:00'),
('Créer une ambiance premium en boutique', 'ambiance-premium-boutique', 'Design intérieur pour valoriser les ventes.', 'Contenu fictif long sur parcours client et vitrines.', '/uploads/placeholders/posts/post-14.jpg', 'Equipe Dolice', 'boutique,design', 'published', '2025-05-21 15:30:00'),
('Isolation intérieure efficace', 'isolation-interieure-efficace', 'Comment améliorer le confort thermique.', 'Contenu fictif long sur solutions isolantes.', '/uploads/placeholders/posts/post-15.jpg', 'Equipe Dolice', 'isolation,confort', 'published', '2025-06-04 10:50:00'),
('Budget travaux: méthode de calcul', 'budget-travaux-methode-calcul', 'Construire un budget réaliste.', 'Contenu fictif long sur lots, marges et imprévus.', '/uploads/placeholders/posts/post-16.jpg', 'Equipe Dolice', 'budget,travaux', 'published', '2025-06-16 09:10:00'),
('Quand refaire son plafond ?', 'quand-refaire-son-plafond', 'Signes d’usure et solutions.', 'Contenu fictif long sur fissures, humidité et rénovation.', '/uploads/placeholders/posts/post-17.jpg', 'Equipe Dolice', 'plafond,renovation', 'published', '2025-07-01 12:00:00'),
('Les finitions qui changent tout', 'finitions-qui-changent-tout', 'Détails visuels qui valorisent un intérieur.', 'Contenu fictif long sur jonctions, angles et teintes.', '/uploads/placeholders/posts/post-18.jpg', 'Equipe Dolice', 'finition,qualite', 'published', '2025-07-18 14:00:00'),
('Aménager un bureau productif', 'amenager-bureau-productif', 'Organisation et ergonomie de l’espace.', 'Contenu fictif long sur circulation et acoustique.', '/uploads/placeholders/posts/post-19.jpg', 'Equipe Dolice', 'bureau,ergonomie', 'published', '2025-08-02 08:45:00'),
('Checklist avant démarrage chantier', 'checklist-avant-demarrage-chantier', 'Préparer le chantier efficacement.', 'Contenu fictif long sur documents, accès et planning.', '/uploads/placeholders/posts/post-20.jpg', 'Equipe Dolice', 'chantier,checklist', 'published', '2025-08-19 10:35:00'),
('Post brouillon A', 'post-brouillon-a', 'Brouillon en attente.', 'Contenu fictif brouillon.', '/uploads/placeholders/posts/post-21.jpg', 'Equipe Dolice', 'draft', 'draft', NULL),
('Post brouillon B', 'post-brouillon-b', 'Brouillon en attente.', 'Contenu fictif brouillon.', '/uploads/placeholders/posts/post-22.jpg', 'Equipe Dolice', 'draft', 'draft', NULL),
('Post brouillon C', 'post-brouillon-c', 'Brouillon en attente.', 'Contenu fictif brouillon.', '/uploads/placeholders/posts/post-23.jpg', 'Equipe Dolice', 'draft', 'draft', NULL),
('Post brouillon D', 'post-brouillon-d', 'Brouillon en attente.', 'Contenu fictif brouillon.', '/uploads/placeholders/posts/post-24.jpg', 'Equipe Dolice', 'draft', 'draft', NULL);

-- Testimonials (30)
INSERT INTO testimonials (client_name, client_company, content, rating, logo_path, status) VALUES
('Rakoto A.', 'Client particulier', 'Travail soigné et équipe très professionnelle.', 5, '/uploads/placeholders/testimonials/logo-01.png', 'approved'),
('Rabe B.', 'Hôtel Eden', 'Respect des délais et très bonne qualité de finition.', 5, '/uploads/placeholders/testimonials/logo-02.png', 'approved'),
('Andry C.', 'TechCorp', 'Communication claire du début à la fin.', 4, '/uploads/placeholders/testimonials/logo-03.png', 'approved'),
('Soa D.', 'Client particulier', 'Bonne écoute et propositions adaptées.', 5, '/uploads/placeholders/testimonials/logo-04.png', 'approved'),
('Lova E.', 'Boutique Nova', 'Résultat final au-dessus de nos attentes.', 5, '/uploads/placeholders/testimonials/logo-05.png', 'approved'),
('Faniry F.', 'Client particulier', 'Finitions impeccables.', 4, '/uploads/placeholders/testimonials/logo-06.png', 'approved'),
('Kanto G.', 'Résidence Atlas', 'Equipe réactive et chantier propre.', 5, '/uploads/placeholders/testimonials/logo-07.png', 'approved'),
('Hery H.', 'Client particulier', 'Très bon suivi pendant le chantier.', 4, '/uploads/placeholders/testimonials/logo-08.png', 'approved'),
('Miora I.', 'Bureau Link', 'Service fiable et professionnel.', 5, '/uploads/placeholders/testimonials/logo-09.png', 'approved'),
('Tahina J.', 'Client particulier', 'Je recommande sans hésiter.', 5, '/uploads/placeholders/testimonials/logo-10.png', 'approved'),
('Tiana K.', 'K-Store', 'Travail de qualité et respect du budget.', 4, '/uploads/placeholders/testimonials/logo-11.png', 'approved'),
('Onja L.', 'Client particulier', 'Très bonne expérience globale.', 5, '/uploads/placeholders/testimonials/logo-12.png', 'approved'),
('Fetra M.', 'Cabinet M', 'Intervention rapide et efficace.', 4, '/uploads/placeholders/testimonials/logo-13.png', 'approved'),
('Hanta N.', 'Client particulier', 'Excellent rendu final.', 5, '/uploads/placeholders/testimonials/logo-14.png', 'approved'),
('Tojo O.', 'Immo O', 'Très bon rapport qualité/prix.', 4, '/uploads/placeholders/testimonials/logo-15.png', 'approved'),
('Client 16', 'Entreprise 16', 'Avis fictif en attente de validation.', 4, '/uploads/placeholders/testimonials/logo-16.png', 'pending'),
('Client 17', 'Entreprise 17', 'Avis fictif en attente de validation.', 3, '/uploads/placeholders/testimonials/logo-17.png', 'pending'),
('Client 18', 'Entreprise 18', 'Avis fictif en attente de validation.', 4, '/uploads/placeholders/testimonials/logo-18.png', 'pending'),
('Client 19', 'Entreprise 19', 'Avis fictif en attente de validation.', 5, '/uploads/placeholders/testimonials/logo-19.png', 'pending'),
('Client 20', 'Entreprise 20', 'Avis fictif en attente de validation.', 4, '/uploads/placeholders/testimonials/logo-20.png', 'pending'),
('Client 21', 'Entreprise 21', 'Avis fictif en attente de validation.', 4, '/uploads/placeholders/testimonials/logo-21.png', 'pending'),
('Client 22', 'Entreprise 22', 'Avis fictif en attente de validation.', 5, '/uploads/placeholders/testimonials/logo-22.png', 'pending'),
('Client 23', 'Entreprise 23', 'Avis fictif en attente de validation.', 4, '/uploads/placeholders/testimonials/logo-23.png', 'pending'),
('Client 24', 'Entreprise 24', 'Avis fictif en attente de validation.', 4, '/uploads/placeholders/testimonials/logo-24.png', 'pending'),
('Client 25', 'Entreprise 25', 'Avis fictif en attente de validation.', 3, '/uploads/placeholders/testimonials/logo-25.png', 'pending'),
('Client 26', 'Entreprise 26', 'Avis fictif en attente de validation.', 5, '/uploads/placeholders/testimonials/logo-26.png', 'pending'),
('Client 27', 'Entreprise 27', 'Avis fictif en attente de validation.', 4, '/uploads/placeholders/testimonials/logo-27.png', 'pending'),
('Client 28', 'Entreprise 28', 'Avis fictif en attente de validation.', 4, '/uploads/placeholders/testimonials/logo-28.png', 'pending'),
('Client 29', 'Entreprise 29', 'Avis fictif en attente de validation.', 5, '/uploads/placeholders/testimonials/logo-29.png', 'pending'),
('Client 30', 'Entreprise 30', 'Avis fictif en attente de validation.', 4, '/uploads/placeholders/testimonials/logo-30.png', 'pending');

-- Quote requests (36)
INSERT INTO quote_requests (name, phone, email, project_type, message, status, internal_notes) VALUES
('Client Q01', '0340000001', 'q01@example.com', 'Peinture intérieure', 'Demande devis appartement T3.', 'new', NULL),
('Client Q02', '0340000002', 'q02@example.com', 'Faux plafond', 'Projet bureau 120m2.', 'new', NULL),
('Client Q03', '0340000003', 'q03@example.com', 'Rénovation complète', 'Maison familiale à rénover.', 'in_progress', 'Visite prévue vendredi.'),
('Client Q04', '0340000004', 'q04@example.com', 'Carrelage', 'Pose carrelage salon + cuisine.', 'replied', 'Devis envoyé par email.'),
('Client Q05', '0340000005', 'q05@example.com', 'Électricité', 'Mise aux normes.', 'done', 'Travaux terminés.'),
('Client Q06', '0340000006', 'q06@example.com', 'Mobilier sur mesure', 'Dressing 3m.', 'archived', 'Sans suite.'),
('Client Q07', '0340000007', 'q07@example.com', 'Peinture extérieure', 'Façade villa.', 'new', NULL),
('Client Q08', '0340000008', 'q08@example.com', 'Plafond placo', 'Installation complète.', 'in_progress', 'En attente dimensions.'),
('Client Q09', '0340000009', 'q09@example.com', 'Isolation', 'Isolation intérieure chambre.', 'new', NULL),
('Client Q10', '0340000010', 'q10@example.com', 'Rénovation SDB', 'SDB 8m2.', 'replied', 'Relance client.'),
('Client Q11', '0340000011', 'q11@example.com', 'Cloisonnement', 'Open space 200m2.', 'new', NULL),
('Client Q12', '0340000012', 'q12@example.com', 'Éclairage', 'Bureau design.', 'new', NULL),
('Client Q13', '0340000013', 'q13@example.com', 'Plafond acoustique', 'Salle réunion.', 'new', NULL),
('Client Q14', '0340000014', 'q14@example.com', 'Parquet', 'Maison neuve.', 'in_progress', 'Rendez-vous pris.'),
('Client Q15', '0340000015', 'q15@example.com', 'Peinture premium', 'Villa 2 niveaux.', 'new', NULL),
('Client Q16', '0340000016', 'q16@example.com', 'Décoration murale', 'Murs 3D showroom.', 'new', NULL),
('Client Q17', '0340000017', 'q17@example.com', 'Ragréage', 'Sol irrégulier.', 'new', NULL),
('Client Q18', '0340000018', 'q18@example.com', 'Mobilier cuisine', 'Cuisine moderne.', 'new', NULL),
('Client Q19', '0340000019', 'q19@example.com', 'Rénovation globale', 'Appartement ancien.', 'in_progress', 'Préparation devis détaillé.'),
('Client Q20', '0340000020', 'q20@example.com', 'Finition luxe', 'Villa premium.', 'new', NULL),
('Client Q21', '0340000021', 'q21@example.com', 'Peinture', 'Simple rafraîchissement.', 'new', NULL),
('Client Q22', '0340000022', 'q22@example.com', 'Électricité', 'Bâtiment R+1.', 'new', NULL),
('Client Q23', '0340000023', 'q23@example.com', 'Cloisons', 'Aménagement bureau.', 'replied', 'Devis validé.'),
('Client Q24', '0340000024', 'q24@example.com', 'Sol', 'Carrelage et plinthes.', 'new', NULL),
('Client Q25', '0340000025', 'q25@example.com', 'Plafond', 'Plafond LED salon.', 'new', NULL),
('Client Q26', '0340000026', 'q26@example.com', 'Mobilier', 'Bibliothèque sur mesure.', 'new', NULL),
('Client Q27', '0340000027', 'q27@example.com', 'Isolation', 'Isolation thermique.', 'new', NULL),
('Client Q28', '0340000028', 'q28@example.com', 'Rénovation SDB', 'Douche italienne.', 'new', NULL),
('Client Q29', '0340000029', 'q29@example.com', 'Décoration', 'Restaurant ambiance.', 'new', NULL),
('Client Q30', '0340000030', 'q30@example.com', 'Peinture extérieure', 'Ravalement façade.', 'new', NULL),
('Client Q31', '0340000031', 'q31@example.com', 'Éclairage', 'Hôtel couloirs.', 'new', NULL),
('Client Q32', '0340000032', 'q32@example.com', 'Parquet', 'Parquet bureau.', 'new', NULL),
('Client Q33', '0340000033', 'q33@example.com', 'Carrelage', 'Entrée + cuisine.', 'new', NULL),
('Client Q34', '0340000034', 'q34@example.com', 'Cloisons', 'Séparation studio.', 'new', NULL),
('Client Q35', '0340000035', 'q35@example.com', 'Plafond', 'Faux plafond chambre.', 'new', NULL),
('Client Q36', '0340000036', 'q36@example.com', 'Rénovation', 'Maison familiale.', 'new', NULL);

-- Contact messages (30)
INSERT INTO contact_messages (name, email, phone, subject, message, status) VALUES
('Messageur 01', 'm01@example.com', '0321000001', 'Info service', 'Avez-vous un service de rénovation complète ?', 'new'),
('Messageur 02', 'm02@example.com', '0321000002', 'Délais', 'Quels sont vos délais moyens ?', 'new'),
('Messageur 03', 'm03@example.com', '0321000003', 'Tarifs', 'Pouvez-vous partager une fourchette de prix ?', 'read'),
('Messageur 04', 'm04@example.com', '0321000004', 'Zone couverte', 'Intervenez-vous à Tamatave ?', 'archived'),
('Messageur 05', 'm05@example.com', '0321000005', 'Partenariat', 'Proposition de partenariat commercial.', 'new'),
('Messageur 06', 'm06@example.com', '0321000006', 'Peinture', 'Demande d’informations sur la peinture premium.', 'new'),
('Messageur 07', 'm07@example.com', '0321000007', 'Plafond', 'Je souhaite un faux plafond LED.', 'new'),
('Messageur 08', 'm08@example.com', '0321000008', 'Carrelage', 'Vous faites la pose grand format ?', 'new'),
('Messageur 09', 'm09@example.com', '0321000009', 'Électricité', 'Disponibilité la semaine prochaine ?', 'new'),
('Messageur 10', 'm10@example.com', '0321000010', 'Mobilier', 'Meubles sur mesure pour cuisine.', 'read'),
('Messageur 11', 'm11@example.com', '0321000011', 'Rénovation', 'Demande rendez-vous chantier.', 'new'),
('Messageur 12', 'm12@example.com', '0321000012', 'Infos', 'Je veux voir vos réalisations similaires.', 'new'),
('Messageur 13', 'm13@example.com', '0321000013', 'Contact', 'Merci de me rappeler.', 'new'),
('Messageur 14', 'm14@example.com', '0321000014', 'Support', 'Question technique sur matériaux.', 'new'),
('Messageur 15', 'm15@example.com', '0321000015', 'Service', 'Intervention urgente possible ?', 'new'),
('Messageur 16', 'm16@example.com', '0321000016', 'Projet', 'Projet hôtelier à discuter.', 'new'),
('Messageur 17', 'm17@example.com', '0321000017', 'Demande', 'Besoin d’un devis rapide.', 'new'),
('Messageur 18', 'm18@example.com', '0321000018', 'Infos', 'Quel est votre process ?', 'new'),
('Messageur 19', 'm19@example.com', '0321000019', 'Question', 'Travaillez-vous les week-ends ?', 'new'),
('Messageur 20', 'm20@example.com', '0321000020', 'Suivi', 'Relance après précédent email.', 'read'),
('Messageur 21', 'm21@example.com', '0321000021', 'Rdv', 'Je propose un rendez-vous mardi.', 'new'),
('Messageur 22', 'm22@example.com', '0321000022', 'Finition', 'Besoin finition villa.', 'new'),
('Messageur 23', 'm23@example.com', '0321000023', 'Peinture', 'Choix des couleurs ?', 'new'),
('Messageur 24', 'm24@example.com', '0321000024', 'Cloisons', 'Séparation espace open space.', 'new'),
('Messageur 25', 'm25@example.com', '0321000025', 'Placo', 'Estimation prix placo.', 'new'),
('Messageur 26', 'm26@example.com', '0321000026', 'Parquet', 'Pose parquet possible ce mois ?', 'new'),
('Messageur 27', 'm27@example.com', '0321000027', 'Revêtement', 'Habillage mural déco.', 'new'),
('Messageur 28', 'm28@example.com', '0321000028', 'SAV', 'Question après livraison.', 'read'),
('Messageur 29', 'm29@example.com', '0321000029', 'Partenaire', 'Proposition fournisseur.', 'archived'),
('Messageur 30', 'm30@example.com', '0321000030', 'Autre', 'Message divers.', 'new');

-- Partners (24)
INSERT INTO partners (name, logo_path, url, category, display_order, is_published) VALUES
('Partenaire Alpha', '/uploads/placeholders/partners/partner-01.png', 'https://example.com/alpha', 'Fournisseur', 1, 1),
('Partenaire Beta', '/uploads/placeholders/partners/partner-02.png', 'https://example.com/beta', 'Fournisseur', 2, 1),
('Partenaire Gamma', '/uploads/placeholders/partners/partner-03.png', 'https://example.com/gamma', 'Immobilier', 3, 1),
('Partenaire Delta', '/uploads/placeholders/partners/partner-04.png', 'https://example.com/delta', 'Hôtellerie', 4, 1),
('Partenaire Epsilon', '/uploads/placeholders/partners/partner-05.png', 'https://example.com/epsilon', 'Entreprise', 5, 1),
('Partenaire Zeta', '/uploads/placeholders/partners/partner-06.png', 'https://example.com/zeta', 'Fournisseur', 6, 1),
('Partenaire Eta', '/uploads/placeholders/partners/partner-07.png', 'https://example.com/eta', 'Construction', 7, 1),
('Partenaire Theta', '/uploads/placeholders/partners/partner-08.png', 'https://example.com/theta', 'Immobilier', 8, 1),
('Partenaire Iota', '/uploads/placeholders/partners/partner-09.png', 'https://example.com/iota', 'Entreprise', 9, 1),
('Partenaire Kappa', '/uploads/placeholders/partners/partner-10.png', 'https://example.com/kappa', 'Fournisseur', 10, 1),
('Partenaire Lambda', '/uploads/placeholders/partners/partner-11.png', 'https://example.com/lambda', 'Fournisseur', 11, 1),
('Partenaire Mu', '/uploads/placeholders/partners/partner-12.png', 'https://example.com/mu', 'Entreprise', 12, 1),
('Partenaire Nu', '/uploads/placeholders/partners/partner-13.png', 'https://example.com/nu', 'Construction', 13, 1),
('Partenaire Xi', '/uploads/placeholders/partners/partner-14.png', 'https://example.com/xi', 'Entreprise', 14, 1),
('Partenaire Omicron', '/uploads/placeholders/partners/partner-15.png', 'https://example.com/omicron', 'Fournisseur', 15, 1),
('Partenaire Pi', '/uploads/placeholders/partners/partner-16.png', 'https://example.com/pi', 'Fournisseur', 16, 1),
('Partenaire Rho', '/uploads/placeholders/partners/partner-17.png', 'https://example.com/rho', 'Immobilier', 17, 1),
('Partenaire Sigma', '/uploads/placeholders/partners/partner-18.png', 'https://example.com/sigma', 'Entreprise', 18, 1),
('Partenaire Tau', '/uploads/placeholders/partners/partner-19.png', 'https://example.com/tau', 'Construction', 19, 1),
('Partenaire Upsilon', '/uploads/placeholders/partners/partner-20.png', 'https://example.com/upsilon', 'Fournisseur', 20, 1),
('Partenaire Phi', '/uploads/placeholders/partners/partner-21.png', 'https://example.com/phi', 'Entreprise', 21, 1),
('Partenaire Chi', '/uploads/placeholders/partners/partner-22.png', 'https://example.com/chi', 'Fournisseur', 22, 1),
('Partenaire Psi', '/uploads/placeholders/partners/partner-23.png', 'https://example.com/psi', 'Immobilier', 23, 1),
('Partenaire Omega', '/uploads/placeholders/partners/partner-24.png', 'https://example.com/omega', 'Hôtellerie', 24, 1);

-- Pages
INSERT INTO pages (page_key, title, content) VALUES
('about', 'Notre histoire', 'Dolice Decoration accompagne les projets résidentiels et professionnels avec une approche orientée qualité et délais.'),
('faq', 'FAQ', 'Q: Quels travaux réalisez-vous ?\nR: Plafond, peinture, sol, électricité, mobilier et rénovation.\n\nQ: Comment demander un devis ?\nR: Via la page Devis ou Contact.'),
('contact', 'Contact', 'Notre équipe répond rapidement à toutes les demandes de devis et d’information.'),
('zones', 'Zones d’intervention', 'Interventions principales: Antananarivo et alentours. Extensions possibles selon projet.'),
('legal', 'Mentions utiles', 'Document fictif de mentions utiles, politique de confidentialité et conditions générales.');

-- Settings
INSERT INTO settings (setting_key, setting_value) VALUES
('phone', '+261 34 00 000 00'),
('whatsapp', '+261 34 00 000 00'),
('email', 'contact@dolice-decoration.mg'),
('address', 'Lot 101 Ivandry, Antananarivo'),
('hours', 'Lun-Ven 08:00-18:00, Sam 08:00-12:00'),
('service_area', 'Antananarivo, Ivato, Itaosy, Talatamaty, Andoharanofotsy'),
('facebook', 'https://facebook.com/dolice.decoration'),
('instagram', 'https://instagram.com/dolice.decoration'),
('site_name', 'Dolice Decoration'),
('hero_tagline', 'Finition et décoration professionnelle à Madagascar');

-- ---------------------------------------------------------------------
-- 4) Régénération chemins images (relatifs au projet, sans images externes)
-- ---------------------------------------------------------------------
-- Convention demandée:
-- - services: public/assets/uploads/services/services_idService.png
-- - realisations: public/assets/uploads/realisations/realisations_idRealisation_1|2|3.png
-- - posts: public/assets/uploads/posts/post_idPost.png
-- - testimonials: public/assets/uploads/testimonials/testimonial_id.png
-- - partners: public/assets/uploads/partners/partner_id.png

UPDATE services
SET image_path = CONCAT(
  'public/assets/uploads/services/services_',
  id,
  '.png'
);

UPDATE project_images
SET image_path = CONCAT(
  'public/assets/uploads/realisations/realisations_',
  project_id,
  '_',
  sort_order,
  '.png'
);

UPDATE posts
SET featured_image = CONCAT(
  'public/assets/uploads/posts/post_',
  id,
  '.png'
);

UPDATE testimonials
SET logo_path = CONCAT(
  'public/assets/uploads/testimonials/testimonial_',
  id,
  '.png'
);

UPDATE partners
SET logo_path = CONCAT(
  'public/assets/uploads/partners/partner_',
  id,
  '.png'
);

-- Fin
SELECT 'schema-fictif-complet.sql importé avec succès.' AS status;

