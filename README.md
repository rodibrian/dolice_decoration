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

### InfinityFree

1. **Fichiers**  
   Uploader tout le dépôt (dossiers `app/`, `config/`, `public/`, `database/`, `storage/`, etc.). Le **document root** du domaine doit pointer vers `public/` (recommandé). Dans ce cas, `APP_URL` = `https://votre-sous-domaine.infinityfreeapp.com` **sans** `/public` à la fin.

2. **`.env` à la racine du projet** (même niveau que le dossier `public/`, pas dans `public/`) avec par exemple :

   ```
   APP_ENV=production
   APP_DEBUG=0
   APP_URL=https://votre-site.infinityfreeapp.com

   DB_HOST=sql208.infinityfree.com
   DB_PORT=3306
   DB_NAME=if0_XXXXX_votre_base
   DB_USER=if0_XXXXX
   DB_PASS=votre_mot_de_passe_mysql
   DB_CHARSET=utf8mb4
   ```

   Remplace les valeurs par celles du panneau **MySQL Databases** (hôte, nom de base, utilisateur, mot de passe). Ne commite jamais ce fichier (il est dans `.gitignore`).

3. **Base de données**  
   Dans phpMyAdmin (InfinityFree), sélectionner la base créée puis importer `database/schema.sql`. Si tu utilises les traductions, importer aussi `database/schema-translations.sql` après coup si besoin.

4. **Droits d’écriture**  
   Le dossier `public/uploads/` doit être accessible en écriture par PHP (images, logos, etc.).

5. **HTTPS**  
   Utiliser `https://` dans `APP_URL` si le site est servi en HTTPS.

> **Sécurité :** si un mot de passe MySQL a été partagé en clair (chat, ticket, capture), régénère-le dans le panneau InfinityFree après déploiement.

