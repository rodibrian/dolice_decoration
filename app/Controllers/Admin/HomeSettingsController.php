<?php
declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Core\AdminAudit;
use App\Core\Auth;
use App\Core\SiteTheme;
use App\Core\Upload;
use App\Models\Setting;
use App\Models\Translation;

final class HomeSettingsController extends BaseController
{
    /** @return list<string> */
    private static function navFooterSettingKeys(): array
    {
        return [
            'nav_show_services',
            'nav_show_projects',
            'nav_show_blog',
            'nav_show_faq',
            'nav_show_contact',
            'nav_show_quote',
            'nav_show_history',
            'footer_show_services',
            'footer_show_projects',
            'footer_show_blog',
            'footer_show_faq',
            'footer_show_contact',
            'footer_show_quote',
            'footer_show_history',
            'footer_show_admin',
        ];
    }

    /**
     * Capability required to change visibility of this menu/footer link (null = any settings.update).
     */
    private static function navFooterCapabilityForKey(string $key): ?string
    {
        return match ($key) {
            'nav_show_services', 'footer_show_services' => 'services.view',
            'nav_show_projects', 'footer_show_projects' => 'projects.view',
            'nav_show_blog', 'footer_show_blog' => 'posts.view',
            'nav_show_faq', 'footer_show_faq' => 'pages.view',
            'nav_show_contact', 'footer_show_contact' => 'messages.view',
            'nav_show_quote', 'footer_show_quote' => 'quotes.view',
            'nav_show_history', 'footer_show_history' => 'pages.view',
            'footer_show_admin' => 'admin.super',
            default => null,
        };
    }

    /**
     * @return array<string, array{can: bool, cap: string}>
     */
    private static function navFooterRights(): array
    {
        $out = [];
        foreach (self::navFooterSettingKeys() as $key) {
            $cap = self::navFooterCapabilityForKey($key);
            if ($cap === null) {
                $out[$key] = ['can' => true, 'cap' => ''];
                continue;
            }
            $out[$key] = ['can' => Auth::can($cap), 'cap' => $cap];
        }

        return $out;
    }

    public function index(): void
    {
        $this->requireAdmin(['settings.view']);

        $this->view('admin.home_settings.index', [
            'title' => 'Gérer Accueil',
            'settings' => Setting::allKeyed(),
            'navFooterRights' => self::navFooterRights(),
            'trans_en' => Translation::tableReady() ? Translation::mapFor('site', 0, 'en') : [],
            'trans_mg' => Translation::tableReady() ? Translation::mapFor('site', 0, 'mg') : [],
            'flash' => $_SESSION['flash_success'] ?? null,
            'error' => $_SESSION['flash_error'] ?? null,
        ], 'layouts/admin');

        unset($_SESSION['flash_success'], $_SESSION['flash_error']);
    }

    public function update(): void
    {
        $this->requireAdmin(['settings.update']);

        $setText = static function (string $k): void {
            $v = trim((string)($_POST[$k] ?? ''));
            Setting::set($k, $v === '' ? null : $v);
        };
        // Important : stocker '0' si décoché — null/'' est interprété comme « défaut = affiché » par le site public.
        $setBool = static function (string $k): void {
            $on = isset($_POST[$k]) && (string)$_POST[$k] === '1';
            Setting::set($k, $on ? '1' : '0');
        };

        // Theme
        $theme = SiteTheme::normalize((string)($_POST['site_theme'] ?? SiteTheme::DEFAULT));
        Setting::set('site_theme', $theme);

        // Hero texts
        $setText('home_badge_text');
        $setText('home_hero_title');
        $setText('home_hero_subtitle');
        $setText('home_primary_cta_label');
        $setText('home_primary_cta_url');
        $setText('home_secondary_cta_label');
        $setText('home_secondary_cta_url');

        // Cover image (same key already used elsewhere)
        $heroCoverImage = trim((string)($_POST['hero_cover_image'] ?? ''));
        if (isset($_FILES['hero_cover_file']) && is_array($_FILES['hero_cover_file'])) {
            $uploadedPath = Upload::storeImage($_FILES['hero_cover_file']);
            if ($uploadedPath !== null) {
                $heroCoverImage = $uploadedPath;
            }
        }
        Setting::set('hero_cover_image', $heroCoverImage === '' ? null : $heroCoverImage);

        // Toggle slides block on homepage
        $setBool('home_slides_enabled');

        // Navbar / Footer visibility toggles (only keys the user is allowed to change)
        foreach (self::navFooterSettingKeys() as $k) {
            $cap = self::navFooterCapabilityForKey($k);
            if ($cap !== null && !Auth::can($cap)) {
                continue;
            }
            $setBool($k);
        }

        AdminAudit::log('home_settings.update', 'home_settings', null, [
            'site_theme' => $theme,
            'home_slides_enabled' => isset($_POST['home_slides_enabled']) && (string)$_POST['home_slides_enabled'] === '1',
            'hero_cover_updated' => isset($_FILES['hero_cover_file']) && is_array($_FILES['hero_cover_file']),
        ]);

        if (Translation::tableReady()) {
            Translation::saveLocale('site', 0, 'en', [
                'home_badge_text' => trim((string)($_POST['en_home_badge_text'] ?? '')),
                'home_hero_title' => trim((string)($_POST['en_home_hero_title'] ?? '')),
                'home_hero_subtitle' => trim((string)($_POST['en_home_hero_subtitle'] ?? '')),
                'home_primary_cta_label' => trim((string)($_POST['en_home_primary_cta_label'] ?? '')),
                'home_secondary_cta_label' => trim((string)($_POST['en_home_secondary_cta_label'] ?? '')),
            ]);
            Translation::saveLocale('site', 0, 'mg', [
                'home_badge_text' => trim((string)($_POST['mg_home_badge_text'] ?? '')),
                'home_hero_title' => trim((string)($_POST['mg_home_hero_title'] ?? '')),
                'home_hero_subtitle' => trim((string)($_POST['mg_home_hero_subtitle'] ?? '')),
                'home_primary_cta_label' => trim((string)($_POST['mg_home_primary_cta_label'] ?? '')),
                'home_secondary_cta_label' => trim((string)($_POST['mg_home_secondary_cta_label'] ?? '')),
            ]);
        }

        $_SESSION['flash_success'] = "Accueil mis à jour.";
        $this->redirect('/admin/home');
    }
}

