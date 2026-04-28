# Dolice Decoration (MVC PHP + MySQL)

Base MVC simple et propre pour XAMPP (local) + déploiement facile plus tard.

## Prérequis

- XAMPP (Apache + MySQL) + phpMyAdmin

## Installation (local XAMPP)

1. **Placer le projet** dans `C:\xampp\htdocs\dolice_decoration` (ou créer un alias Apache).

2. **Créer le fichier `.env`**
   - Copier `.env.example` → `.env`
   - Adapter `APP_URL` selon ton accès local, par exemple :
     - `APP_URL=http://localhost/dolice_decoration/public`

3. **Créer la base**
   - Ouvrir phpMyAdmin
   - Importer `database/schema.sql`

4. **Ouvrir le site**
   - Public: `http://localhost/dolice_decoration/public/`
   - Admin login: `http://localhost/dolice_decoration/public/admin/login`

## Identifiants admin (démo)

- Email: `admin@dolice.local`
- Mot de passe: `Admin@1234`

> Tu peux les changer ensuite en base, ou on ajoutera une page “profil” dans le back-office.

## Structure

- `public/` : point d’entrée (`index.php`), assets, uploads
- `app/Controllers/` : contrôleurs
- `app/Models/` : modèles (PDO)
- `app/Views/` : vues (layouts + pages)
- `app/Core/` : Router, DB, View, Auth
- `config/` : bootstrap + `.env` + sessions
- `database/` : scripts SQL
- `storage/` : logs/cache

## Déploiement (hébergement)

- Copier le projet sur l’hébergement
- Mettre `APP_URL` à l’URL du site (ex: `https://domaine.tld/public` ou configurer le document root directement sur `public/`)
- Importer `database/schema.sql` (ou migrer la base)
- Mettre un vrai `DB_HOST/DB_USER/DB_PASS` dans `.env`

