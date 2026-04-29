<?php
declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Core\Auth;
use App\Core\Upload;
use App\Models\Setting;

final class SettingsController extends BaseController
{
    public function index(): void
    {
        if (!Auth::check()) {
            $this->redirect('/admin/login');
        }

        $this->view('admin.settings.index', [
            'title' => 'Paramètres',
            'settings' => Setting::allKeyed(),
            'flash' => $_SESSION['flash_success'] ?? null,
        ], 'layouts/admin');

        unset($_SESSION['flash_success']);
    }

    public function update(): void
    {
        if (!Auth::check()) {
            $this->redirect('/admin/login');
        }

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

        $_SESSION['flash_success'] = "Paramètres enregistrés.";
        $this->redirect('/admin/settings');
    }
}

