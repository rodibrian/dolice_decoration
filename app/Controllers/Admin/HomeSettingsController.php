<?php
declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Core\Upload;
use App\Models\Setting;

final class HomeSettingsController extends BaseController
{
    public function index(): void
    {
        $this->requireAdmin(['settings.view']);

        $this->view('admin.home_settings.index', [
            'title' => 'Gérer Accueil',
            'settings' => Setting::allKeyed(),
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
        $setBool = static function (string $k): void {
            $on = isset($_POST[$k]) && (string)$_POST[$k] === '1';
            Setting::set($k, $on ? '1' : null);
        };

        // Theme
        $theme = (string)($_POST['site_theme'] ?? 'default');
        $allowedThemes = ['default', 'ocean', 'sunset'];
        if (!in_array($theme, $allowedThemes, true)) {
            $theme = 'default';
        }
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

        // Navbar / Footer visibility toggles
        foreach ([
            'nav_show_services',
            'nav_show_projects',
            'nav_show_blog',
            'nav_show_faq',
            'nav_show_contact',
            'nav_show_quote',
            'nav_show_history',
        ] as $k) {
            $setBool($k);
        }
        foreach ([
            'footer_show_services',
            'footer_show_projects',
            'footer_show_blog',
            'footer_show_faq',
            'footer_show_contact',
            'footer_show_quote',
            'footer_show_history',
            'footer_show_admin',
        ] as $k) {
            $setBool($k);
        }

        $_SESSION['flash_success'] = "Accueil mis à jour.";
        $this->redirect('/admin/home');
    }
}

