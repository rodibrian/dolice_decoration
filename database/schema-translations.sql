-- Traductions génériques (FR = contenu principal des tables ; EN/MG = surcharges)
-- Exécuter ce script sur la base `dolice_decoration` si la table n'existe pas encore.

USE dolice_decoration;

CREATE TABLE IF NOT EXISTS translations (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  entity_type VARCHAR(60) NOT NULL,
  entity_id INT UNSIGNED NOT NULL DEFAULT 0,
  locale VARCHAR(10) NOT NULL,
  field VARCHAR(120) NOT NULL,
  value MEDIUMTEXT NULL,
  updated_at TIMESTAMP NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  UNIQUE KEY uq_translation (entity_type, entity_id, locale, field),
  KEY idx_translation_lookup (entity_type, entity_id, locale)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
