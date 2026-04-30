<?php
declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Core\Upload;
use App\Models\Setting;

final class CompanySettingsController extends BaseController
{
    /**
     * @return list<string>
     */
    private function linesToList(string $raw): array
    {
        $raw = trim($raw);
        if ($raw === '') {
            return [];
        }
        $lines = preg_split('/\r\n|\r|\n/u', $raw) ?: [];
        $out = [];
        foreach ($lines as $line) {
            $line = trim((string)$line);
            if ($line === '') {
                continue;
            }
            $out[] = $line;
        }
        $out = array_values(array_unique($out));
        return $out;
    }

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

        // Multiple phones/emails (stored as JSON lists)
        $phones = $this->linesToList((string)($_POST['phones'] ?? ''));
        $emails = $this->linesToList((string)($_POST['emails'] ?? ''));
        Setting::set('company_phones_json', !empty($phones) ? json_encode($phones, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) : null);
        Setting::set('company_emails_json', !empty($emails) ? json_encode($emails, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) : null);

        // Keep backward-compatible primary values
        $primaryPhone = !empty($phones) ? (string)$phones[0] : trim((string)($_POST['phone'] ?? ''));
        $primaryEmail = !empty($emails) ? (string)$emails[0] : trim((string)($_POST['email'] ?? ''));
        Setting::set('phone', $primaryPhone !== '' ? $primaryPhone : null);
        Setting::set('email', $primaryEmail !== '' ? $primaryEmail : null);

        // Other contacts
        foreach ([
            'whatsapp',
            'address',
            'hours',
            'service_area',
        ] as $k) {
            $setText($k);
        }

        // Map location
        foreach ([
            'company_map_address',
            'company_map_embed_url',
            'company_map_lat',
            'company_map_lng',
        ] as $k) {
            $setText($k);
        }

        // Social links
        foreach ([
            'facebook',
            'instagram',
            'tiktok',
            'youtube',
            'linkedin',
            'twitter',
        ] as $k) {
            $setText($k);
        }

        $_SESSION['flash_success'] = "Entreprise mise à jour.";
        $this->redirect('/admin/company');
    }
}

