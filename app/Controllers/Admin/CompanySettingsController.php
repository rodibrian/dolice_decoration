<?php
declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Core\Upload;
use App\Models\Setting;

final class CompanySettingsController extends BaseController
{
    public function index(): void
    {
        $this->requireAdmin(['settings.view']);

        $this->view('admin.company_settings.index', [
            'title' => 'Gérer Entreprise',
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

        // Company identity
        $setText('company_name');
        $setText('company_slogan');

        $logoPath = trim((string)($_POST['company_logo'] ?? ''));
        if (isset($_FILES['company_logo_file']) && is_array($_FILES['company_logo_file'])) {
            $uploadedPath = Upload::storeImage($_FILES['company_logo_file']);
            if ($uploadedPath !== null) {
                $logoPath = $uploadedPath;
            }
        }
        Setting::set('company_logo', $logoPath === '' ? null : $logoPath);

        // Contacts & socials
        foreach ([
            'phone',
            'whatsapp',
            'email',
            'address',
            'hours',
            'service_area',
            'facebook',
            'instagram',
            'tiktok',
            'youtube',
            'linkedin',
        ] as $k) {
            $setText($k);
        }

        $_SESSION['flash_success'] = "Entreprise mise à jour.";
        $this->redirect('/admin/company');
    }
}

