<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Models\QuoteRequest;
use App\Models\Service;
use App\Models\Setting;
use App\Models\Translation;
use App\Services\EmailJs;

final class QuotesPublicController extends BaseController
{
    public function showForm(): void
    {
        $services = array_map(static function (array $row): array {
            $id = (int)($row['id'] ?? 0);

            return $id > 0 ? Translation::mergeRow('service', $id, $row, ['title', 'description', 'category', 'price_label', 'price_unit']) : $row;
        }, Service::published());

        $this->view('quotes.form', [
            'title' => t('nav.quote'),
            'flash' => $_SESSION['flash_public'] ?? null,
            'services' => $services,
        ]);
        unset($_SESSION['flash_public']);
    }

    public function submit(): void
    {
        // Honeypot
        $hp = trim((string)($_POST['company'] ?? ''));
        if ($hp !== '') {
            $this->redirect('/devis');
        }

        $name = trim((string)($_POST['name'] ?? ''));
        $phone = trim((string)($_POST['phone'] ?? '')) ?: null;
        $email = trim((string)($_POST['email'] ?? '')) ?: null;
        $projectType = trim((string)($_POST['project_type'] ?? '')) ?: null;
        $messageRaw = trim((string)($_POST['message'] ?? ''));

        $city = trim((string)($_POST['city'] ?? '')) ?: null;
        $address = trim((string)($_POST['address'] ?? '')) ?: null;
        $surface = trim((string)($_POST['surface'] ?? '')) ?: null;
        $timeline = trim((string)($_POST['timeline'] ?? '')) ?: null;
        $budget = trim((string)($_POST['budget'] ?? '')) ?: null;
        $contactPref = trim((string)($_POST['contact_preference'] ?? '')) ?: null;

        $prefRaw = strtolower(trim((string)($contactPref ?? '')));
        $prefLabel = match ($prefRaw) {
            'phone', 'téléphone', 'telephone', 'tel' => t('public.forms.contact_phone'),
            'email', 'mail' => t('public.forms.contact_email'),
            'whatsapp' => t('public.social.whatsapp'),
            default => $contactPref !== null && trim((string)$contactPref) !== '' ? (string)$contactPref : '',
        };

        $extra = [];
        if ($city !== null) {
            $extra[] = t('public.quote.msg_city', ['value' => $city]);
        }
        if ($address !== null) {
            $extra[] = t('public.quote.msg_address', ['value' => $address]);
        }
        if ($surface !== null) {
            $extra[] = t('public.quote.msg_surface', ['value' => $surface]);
        }
        if ($timeline !== null) {
            $extra[] = t('public.quote.msg_timeline', ['value' => $timeline]);
        }
        if ($budget !== null) {
            $extra[] = t('public.quote.msg_budget', ['value' => $budget]);
        }
        if ($prefLabel !== '') {
            $extra[] = t('public.quote.msg_contact_pref', ['value' => $prefLabel]);
        }

        $messageParts = [];
        if (!empty($extra)) {
            $messageParts[] = implode("\n", $extra);
        }
        if ($messageRaw !== '') {
            $messageParts[] = $messageRaw;
        }
        $message = !empty($messageParts) ? implode("\n\n", $messageParts) : null;
        $serviceIds = $_POST['services'] ?? [];
        if (!is_array($serviceIds)) {
            $serviceIds = [];
        }
        $serviceIds = array_values(array_filter(array_map(static fn ($v): int => (int)$v, $serviceIds), static fn (int $v): bool => $v > 0));
        $serviceIds = array_values(array_unique($serviceIds));

        if ($name === '') {
            $_SESSION['flash_public'] = t('public.forms.name_required');
            $this->redirect('/devis');
        }

        $quoteId = QuoteRequest::create([
            'name' => $name,
            'phone' => $phone,
            'email' => $email,
            'project_type' => $projectType,
            'message' => $message,
            'status' => 'new',
            'internal_notes' => null,
        ]);

        if (!empty($serviceIds)) {
            QuoteRequest::addServiceItems($quoteId, $serviceIds);
        }

        // EmailJS notification (optional)
        try {
            $toEmail = trim((string)(Setting::get('emailjs_to_email', '') ?? ''));
            $toName = trim((string)(Setting::get('emailjs_to_name', '') ?? ''));
            $svcNames = [];
            if (!empty($serviceIds)) {
                $all = Service::published();
                $map = [];
                foreach ($all as $s) {
                    $sid = (int)($s['id'] ?? 0);
                    if ($sid > 0) {
                        $s = Translation::mergeRow('service', $sid, $s, ['title']);
                    }
                    $map[$sid] = (string)($s['title'] ?? '');
                }
                foreach ($serviceIds as $sid) {
                    $t = trim((string)($map[(int)$sid] ?? ''));
                    if ($t !== '') $svcNames[] = $t;
                }
            }
            $servicesText = implode(', ', $svcNames);
            if ($servicesText === '') $servicesText = '—';
            $createdAt = date('Y-m-d H:i');

            EmailJs::notifyQuote([
                'to_email' => $toEmail,
                'to_name' => $toName,
                'subject' => 'Nouvelle demande de devis #' . (string)$quoteId . ' — ' . $name,
                'type' => 'Devis',
                'created_at' => $createdAt,
                'quote_id' => (string)$quoteId,
                'name' => $name,
                'phone' => ($phone ?? '') !== '' ? (string)$phone : '—',
                'email' => ($email ?? '') !== '' ? (string)$email : '—',
                'project_type' => ($projectType ?? '') !== '' ? (string)$projectType : '—',
                'services' => $servicesText,
                'contact_subject' => '—',
                'message' => ($message ?? '') !== '' ? (string)$message : '—',
                'source' => 'public_quote',
            ]);
        } catch (\Throwable $e) {
            // ignore
        }

        $_SESSION['flash_public'] = t('public.forms.sent_ok');
        $this->redirect('/devis');
    }
}

