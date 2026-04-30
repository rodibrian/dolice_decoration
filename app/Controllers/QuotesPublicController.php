<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Models\QuoteRequest;
use App\Models\Service;

final class QuotesPublicController extends BaseController
{
    public function showForm(): void
    {
        $this->view('quotes.form', [
            'title' => 'Demander un devis',
            'flash' => $_SESSION['flash_public'] ?? null,
            'services' => Service::published(),
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
        $serviceIds = $_POST['services'] ?? [];
        if (!is_array($serviceIds)) {
            $serviceIds = [];
        }
        $serviceIds = array_values(array_filter(array_map(static fn ($v): int => (int)$v, $serviceIds), static fn (int $v): bool => $v > 0));
        $serviceIds = array_values(array_unique($serviceIds));

        if ($name === '') {
            $_SESSION['flash_public'] = "Merci d’indiquer votre nom.";
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

        $_SESSION['flash_public'] = "Demande envoyée. Nous vous recontactons rapidement.";
        $this->redirect('/devis');
    }
}

