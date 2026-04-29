<?php
declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Core\Auth;
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
            'hero_cover_image',
        ];

        foreach ($keys as $k) {
            $v = trim((string)($_POST[$k] ?? ''));
            Setting::set($k, $v === '' ? null : $v);
        }

        $_SESSION['flash_success'] = "Paramètres enregistrés.";
        $this->redirect('/admin/settings');
    }
}

