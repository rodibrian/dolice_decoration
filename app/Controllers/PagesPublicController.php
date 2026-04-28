<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Models\Page;
use App\Models\Setting;
use App\Models\ContactMessage;

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
            http_response_code(404);
            echo '404';
            return;
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

        $_SESSION['flash_public'] = "Message envoyé. Merci !";
        $this->redirect('/contact');
    }
}

