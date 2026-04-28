<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Models\QuoteRequest;

final class QuotesPublicController extends BaseController
{
    public function showForm(): void
    {
        $this->view('quotes.form', [
            'title' => 'Demander un devis',
            'flash' => $_SESSION['flash_public'] ?? null,
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
        $message = trim((string)($_POST['message'] ?? '')) ?: null;

        if ($name === '') {
            $_SESSION['flash_public'] = "Merci d’indiquer votre nom.";
            $this->redirect('/devis');
        }

        QuoteRequest::create([
            'name' => $name,
            'phone' => $phone,
            'email' => $email,
            'project_type' => $projectType,
            'message' => $message,
            'status' => 'new',
            'internal_notes' => null,
        ]);

        $_SESSION['flash_public'] = "Demande envoyée. Nous vous recontactons rapidement.";
        $this->redirect('/devis');
    }
}

