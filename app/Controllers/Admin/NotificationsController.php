<?php
declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\Setting;
use App\Services\EmailJs;

final class NotificationsController extends BaseController
{
    public function index(): void
    {
        $this->requireAdmin(['notifications.view']);

        $this->view('admin.notifications.index', [
            'title' => 'Notifications (EmailJS)',
            'settings' => Setting::allKeyed(),
            'flash' => $_SESSION['flash_success'] ?? null,
            'error' => $_SESSION['flash_error'] ?? null,
        ], 'layouts/admin');

        unset($_SESSION['flash_success'], $_SESSION['flash_error']);
    }

    public function update(): void
    {
        $this->requireAdmin(['notifications.update']);

        $setText = static function (string $k): void {
            $v = trim((string)($_POST[$k] ?? ''));
            Setting::set($k, $v === '' ? null : $v);
        };
        $enabled = isset($_POST['emailjs_enabled']) && (string)($_POST['emailjs_enabled']) === '1';
        Setting::set('emailjs_enabled', $enabled ? '1' : null);

        foreach ([
            'emailjs_service_id',
            'emailjs_template_quote',
            'emailjs_template_contact',
            'emailjs_public_key',
            'emailjs_private_key',
            'emailjs_to_email',
            'emailjs_to_name',
        ] as $k) {
            $setText($k);
        }

        $_SESSION['flash_success'] = "Configuration EmailJS enregistrée.";
        $this->redirect('/admin/notifications');
    }

    public function test(): void
    {
        $this->requireAdmin(['notifications.update']);

        $type = (string)($_POST['test_type'] ?? 'quote');
        if (!in_array($type, ['quote', 'contact'], true)) {
            $type = 'quote';
        }

        $toEmail = trim((string)(Setting::get('emailjs_to_email', '') ?? ''));
        $toName = trim((string)(Setting::get('emailjs_to_name', '') ?? ''));

        $ok = false;
        if ($type === 'quote') {
            $ok = EmailJs::notifyQuote([
                'to_email' => $toEmail,
                'to_name' => $toName,
                'subject' => 'Nouvelle demande de devis #TEST — Client Test',
                'type' => 'Devis',
                'created_at' => date('Y-m-d H:i'),
                'quote_id' => 'TEST',
                'name' => 'Client Test',
                'phone' => '0340000000',
                'email' => 'client.test@example.com',
                'project_type' => 'Test projet',
                'services' => 'Service A, Service B',
                'contact_subject' => '—',
                'message' => "Ceci est un test depuis l'admin.",
                'source' => 'admin_test',
            ]);
        } else {
            $ok = EmailJs::notifyContact([
                'to_email' => $toEmail,
                'to_name' => $toName,
                'subject' => 'Nouveau message — Client Test',
                'type' => 'Contact',
                'created_at' => date('Y-m-d H:i'),
                'quote_id' => '—',
                'name' => 'Client Test',
                'phone' => '0340000000',
                'email' => 'client.test@example.com',
                'project_type' => '—',
                'services' => '—',
                'contact_subject' => 'Test sujet',
                'message' => "Ceci est un test depuis l'admin.",
                'source' => 'admin_test',
            ]);
        }

        $_SESSION['flash_success'] = $ok ? "Email de test envoyé." : "Échec de l'envoi (vérifie clés/service/template).";
        $this->redirect('/admin/notifications');
    }
}

