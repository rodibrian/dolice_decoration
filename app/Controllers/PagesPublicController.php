<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Models\Page;
use App\Models\Setting;
use App\Models\ContactMessage;
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
        if ($page === null) {
            // Public fallback pages for first run when DB content is not seeded yet.
            $fallbackTitles = [
                'about' => 'Notre histoire',
                'faq' => 'FAQ',
                'contact' => 'Contact',
            ];

            if (!isset($fallbackTitles[$key])) {
                http_response_code(404);
                echo '404';
                return;
            }

            $fallbackContents = [
                'about' => "Nous construisons des espaces durables, soignés et fonctionnels. Notre équipe accompagne chaque client avec sérieux, du besoin initial à la livraison.",
                'faq' => "Questions fréquentes:\n\n- Quels travaux réalisez-vous ?\n- Quels sont vos délais moyens ?\n- Comment demander un devis ?\n- Quelles zones couvrez-vous ?\n\nPour une réponse personnalisée, utilisez le formulaire de contact.",
                'contact' => "Vous pouvez nous contacter via le formulaire ci-dessous pour toute demande d'information ou de devis.",
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
            $_SESSION['flash_public'] = "Merci de remplir au moins le nom et le message.";
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

        $_SESSION['flash_public'] = "Message envoyé. Merci !";
        $this->redirect('/contact');
    }
}

