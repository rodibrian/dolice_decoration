<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Models\Service;

final class ServicesPublicController extends BaseController
{
    public function index(): void
    {
        $this->view('services.index', [
            'title' => 'Services',
            'services' => Service::published(),
        ]);
    }

    public function show(): void
    {
        $slug = trim((string)($_GET['slug'] ?? ''));
        $service = $slug !== '' ? Service::findBySlug($slug) : null;
        if ($service === null || (int)($service['is_published'] ?? 0) !== 1) {
            http_response_code(404);
            echo '404';
            return;
        }

        $this->view('services.show', [
            'title' => (string)$service['title'],
            'service' => $service,
        ]);
    }
}

