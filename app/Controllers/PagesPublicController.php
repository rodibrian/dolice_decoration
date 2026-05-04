<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Models\ContactMessage;
use App\Models\Page;
use App\Models\Setting;
use App\Models\Translation;
use App\Services\EmailJs;

final class PagesPublicController extends BaseController
{
    public function show(): void
    {
        // Map from current path to page_key
        $path = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';
        $scriptName = (string)($_SERVER['SCRIPT_NAME'] ?? '');
        $base = rtrim(str_replace('\\', '/', dirname($scriptName)), '/');
        if ($base !== '' && $base !== '/' && str_starts_with($path, $base)) {
            $path = substr($path, strlen($base));
        }
        $path = rtrim($path, '/') ?: '/';

        $key = match ($path) {
            '/notre-histoire' => 'about',
            '/faq' => 'faq',
            '/contact' => 'contact',
            default => trim((string)($_GET['page_key'] ?? '')),
        };
        if ($key === '') {
            http_response_code(404);
            echo '404';
            return;
        }

        $page = Page::findByKey($key);
        if (is_array($page) && isset($page['id'])) {
            $page = Translation::mergeRow('page', (int)$page['id'], $page, ['title', 'content']);
        }
        if ($page === null) {
            // Public fallback pages for first run when DB content is not seeded yet.
            $fallbackTitles = [
                'about' => t('nav.history'),
                'faq' => t('nav.faq'),
                'contact' => t('nav.contact'),
            ];

            if (!isset($fallbackTitles[$key])) {
                http_response_code(404);
                echo '404';
                return;
            }

            $fallbackContents = [
                'about' => t('public.fallback_pages.about_content'),
                'faq' => t('public.fallback_pages.faq_content'),
                'contact' => t('public.fallback_pages.contact_content'),
            ];

            $page = [
                'page_key' => $key,
                'title' => $fallbackTitles[$key],
                'content' => $fallbackContents[$key],
            ];
        }

        $this->view('pages.show', [
            'title' => (string)$page['title'],
            'page' => $page,
            'settings' => Setting::allKeyed(),
            'flash' => $_SESSION['flash_public'] ?? null,
        ]);

        unset($_SESSION['flash_public']);
    }

    public function contactSubmit(): void
    {
        $name = trim((string)($_POST['name'] ?? ''));
        $email = trim((string)($_POST['email'] ?? '')) ?: null;
        $phone = trim((string)($_POST['phone'] ?? '')) ?: null;
        $subject = trim((string)($_POST['subject'] ?? '')) ?: null;
        $message = trim((string)($_POST['message'] ?? ''));

        // Honeypot
        $hp = trim((string)($_POST['company'] ?? ''));
        if ($hp !== '') {
            $this->redirect('/contact');
        }

        if ($name === '' || $message === '') {
            $_SESSION['flash_public'] = t('public.forms.name_and_message_required');
            $this->redirect('/contact');
        }

        ContactMessage::create([
            'name' => $name,
            'email' => $email,
            'phone' => $phone,
            'subject' => $subject,
            'message' => $message,
            'status' => 'new',
        ]);

        // EmailJS notification (optional)
        try {
            $toEmail = trim((string)(Setting::get('emailjs_to_email', '') ?? ''));
            $toName = trim((string)(Setting::get('emailjs_to_name', '') ?? ''));
            $createdAt = date('Y-m-d H:i');
            $subj = ($subject ?? '') !== '' ? (string)$subject : 'Contact';
            EmailJs::notifyContact([
                'to_email' => $toEmail,
                'to_name' => $toName,
                'subject' => 'Nouveau message — ' . $name,
                'type' => 'Contact',
                'created_at' => $createdAt,
                'quote_id' => '—',
                'name' => $name,
                'phone' => ($phone ?? '') !== '' ? (string)$phone : '—',
                'email' => ($email ?? '') !== '' ? (string)$email : '—',
                'project_type' => '—',
                'services' => '—',
                'contact_subject' => $subj,
                'message' => $message !== '' ? $message : '—',
                'source' => 'public_contact',
            ]);
        } catch (\Throwable $e) {
            // ignore
        }

        $_SESSION['flash_public'] = t('public.forms.message_sent_ok');
        $this->redirect('/contact');
    }
}

