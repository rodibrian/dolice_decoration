<?php
declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Core\AdminAudit;
use App\Core\Upload;
use App\Models\Setting;

final class SettingsController extends BaseController
{
    public function index(): void
    {
        $this->requireAdmin(['settings.view']);

        $this->view('admin.settings.index', [
            'title' => 'Paramètres',
            'settings' => Setting::allKeyed(),
            'flash' => $_SESSION['flash_success'] ?? null,
        ], 'layouts/admin');

        unset($_SESSION['flash_success']);
    }

    public function update(): void
    {
        $this->requireAdmin(['settings.update']);

        $keys = [
            'phone',
            'whatsapp',
            'email',
            'address',
            'hours',
            'service_area',
            'facebook',
            'instagram',
        ];

        foreach ($keys as $k) {
            $v = trim((string)($_POST[$k] ?? ''));
            Setting::set($k, $v === '' ? null : $v);
        }

        $heroCoverImage = trim((string)($_POST['hero_cover_image'] ?? ''));
        if (isset($_FILES['hero_cover_file']) && is_array($_FILES['hero_cover_file'])) {
            $uploadedPath = Upload::storeImage($_FILES['hero_cover_file']);
            if ($uploadedPath !== null) {
                $heroCoverImage = $uploadedPath;
            }
        }
        Setting::set('hero_cover_image', $heroCoverImage === '' ? null : $heroCoverImage);

        Setting::set('layout_show_main_nav', isset($_POST['layout_show_main_nav']) ? '1' : '0');
        Setting::set('layout_show_footer', isset($_POST['layout_show_footer']) ? '1' : '0');

        AdminAudit::log('settings.update', 'settings', null, [
            'keys' => array_merge($keys, ['hero_cover_image', 'layout_show_main_nav', 'layout_show_footer']),
            'layout_show_main_nav' => isset($_POST['layout_show_main_nav']) ? 1 : 0,
            'layout_show_footer' => isset($_POST['layout_show_footer']) ? 1 : 0,
        ]);

        $_SESSION['flash_success'] = "Paramètres enregistrés.";
        $this->redirect('/admin/settings');
    }
}

