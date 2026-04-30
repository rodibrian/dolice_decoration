<?php
declare(strict_types=1);

namespace App\Core;

use App\Models\User;
use App\Core\DB;
use PDOException;

final class Auth
{
    /**
     * Capability catalog used by the Super Admin UI.
     *
     * @return array<string, array<string, string>> group => [capability => label]
     */
    public static function capabilityCatalog(): array
    {
        return [
            'Dashboard' => [
                'dashboard.view' => 'Voir le dashboard',
            ],
            'Inbox' => [
                'quotes.view' => 'Voir les devis',
                'quotes.update' => 'Mettre à jour les devis',
                'messages.view' => 'Voir les messages',
                'messages.update' => 'Mettre à jour les messages',
            ],
            'Contenu' => [
                'services.view' => 'Voir services',
                'services.create' => 'Créer services',
                'services.update' => 'Modifier services',
                'services.delete' => 'Supprimer services',

                'projects.view' => 'Voir réalisations',
                'projects.create' => 'Créer réalisations',
                'projects.update' => 'Modifier réalisations',
                'projects.delete' => 'Supprimer réalisations',

                'posts.view' => 'Voir articles',
                'posts.create' => 'Créer articles',
                'posts.update' => 'Modifier articles',
                'posts.delete' => 'Supprimer articles',

                'testimonials.view' => 'Voir témoignages',
                'testimonials.create' => 'Créer témoignages',
                'testimonials.update' => 'Modifier / approuver témoignages',
                'testimonials.delete' => 'Supprimer témoignages',

                'partners.view' => 'Voir partenaires',
                'partners.create' => 'Créer partenaires',
                'partners.update' => 'Modifier partenaires',
                'partners.delete' => 'Supprimer partenaires',

                'hero_slides.view' => 'Voir slides accueil',
                'hero_slides.create' => 'Créer slides accueil',
                'hero_slides.update' => 'Modifier slides accueil',
                'hero_slides.delete' => 'Supprimer slides accueil',

                'pages.view' => 'Voir pages',
                'pages.update' => 'Modifier pages',

                'settings.view' => 'Voir paramètres',
                'settings.update' => 'Modifier paramètres',
            ],
            'Notifications' => [
                'notifications.view' => 'Voir la config notifications (EmailJS)',
                'notifications.update' => 'Modifier la config notifications (EmailJS)',
            ],
            'Super admin' => [
                'admin.super' => 'Accès super admin (utilisateurs, rôles, logs)',
            ],
        ];
    }

    /**
     * Default permissions if DB tables are not installed yet.
     *
     * @return array<string, list<string>> role => capabilities
     */
    private static function defaultCapabilitiesByRole(): array
    {
        return [
            'admin' => [
                'dashboard.view',
                'quotes.view', 'quotes.update',
                'messages.view', 'messages.update',
                'services.view', 'services.create', 'services.update', 'services.delete',
                'projects.view', 'projects.create', 'projects.update', 'projects.delete',
                'posts.view', 'posts.create', 'posts.update', 'posts.delete',
                'testimonials.view', 'testimonials.create', 'testimonials.update', 'testimonials.delete',
                'partners.view', 'partners.create', 'partners.update', 'partners.delete',
                'hero_slides.view', 'hero_slides.create', 'hero_slides.update', 'hero_slides.delete',
                'pages.view', 'pages.update',
                'settings.view', 'settings.update',
                'notifications.view', 'notifications.update',
            ],
        ];
    }

    private static ?bool $capTableExists = null;
    /** @var array<string, list<string>> */
    private static array $capCache = [];

    private static function capabilityTableExists(): bool
    {
        if (self::$capTableExists !== null) {
            return self::$capTableExists;
        }
        try {
            DB::pdo()->query('SELECT 1 FROM role_capabilities LIMIT 0');
            self::$capTableExists = true;
        } catch (PDOException $e) {
            self::$capTableExists = false;
        }
        return self::$capTableExists;
    }

    /**
     * @return list<string>
     */
    private static function capabilitiesForRole(string $role): array
    {
        if ($role === '') {
            return [];
        }
        if (isset(self::$capCache[$role])) {
            return self::$capCache[$role];
        }

        if (self::capabilityTableExists()) {
            try {
                $pdo = DB::pdo();
                $stmt = $pdo->prepare('SELECT capability FROM role_capabilities WHERE role = :r');
                $stmt->execute(['r' => $role]);
                $caps = array_map(static fn (array $row): string => (string)$row['capability'], $stmt->fetchAll());
                self::$capCache[$role] = $caps;
                return $caps;
            } catch (PDOException $e) {
                // fallback below
            }
        }

        $defaults = self::defaultCapabilitiesByRole();
        self::$capCache[$role] = $defaults[$role] ?? [];
        return self::$capCache[$role];
    }

    public static function role(): string
    {
        $u = self::user();
        return $u !== null ? (string)($u['role'] ?? '') : '';
    }

    public static function isSuperAdmin(): bool
    {
        return self::role() === 'super_admin';
    }

    public static function hasRole(string ...$roles): bool
    {
        if (!self::check()) {
            return false;
        }
        $r = self::role();
        foreach ($roles as $role) {
            if ($r === $role) {
                return true;
            }
        }
        return false;
    }

    /**
     * Simple permissions map (extend as needed).
     */
    public static function can(string $capability): bool
    {
        if (self::isSuperAdmin()) {
            return true;
        }

        $role = self::role();
        $caps = self::capabilitiesForRole($role);
        return in_array($capability, $caps, true);
    }

    public static function user(): ?array
    {
        return $_SESSION['auth_user'] ?? null;
    }

    public static function check(): bool
    {
        return self::user() !== null;
    }

    public static function attempt(string $email, string $password): bool
    {
        $user = User::findByEmail($email);
        if ($user === null) {
            return false;
        }

        if (!password_verify($password, $user['password_hash'])) {
            return false;
        }

        $_SESSION['auth_user'] = [
            'id' => (int)$user['id'],
            'email' => (string)$user['email'],
            'name' => (string)$user['name'],
            'role' => (string)$user['role'],
        ];

        // Reset per-request capability cache (role might differ between sessions)
        self::$capCache = [];

        return true;
    }

    public static function logout(): void
    {
        unset($_SESSION['auth_user']);
    }
}
