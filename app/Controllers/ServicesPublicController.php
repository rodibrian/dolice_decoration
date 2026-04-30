<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Models\Service;

final class ServicesPublicController extends BaseController
{
    /**
     * @return string
     */
    private function appUrlBase(): string
    {
        $base = (string)(env('APP_URL', '') ?: '');
        return rtrim($base, '/');
    }

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

        $isModal = isset($_GET['modal']) && (string)$_GET['modal'] === '1';
        if ($isModal) {
            $base = $this->appUrlBase();
            $img = trim((string)($service['image_path'] ?? ''));
            $imgUrl = '';
            if ($img !== '') {
                $imgUrl = (preg_match('#^https?://#i', $img) === 1) ? $img : ($base . '/' . ltrim($img, '/'));
            }

            header('Content-Type: application/json; charset=utf-8');
            echo json_encode([
                'service' => [
                    'title' => (string)($service['title'] ?? ''),
                    'slug' => (string)($service['slug'] ?? ''),
                    'category' => (string)($service['category'] ?? ''),
                    'description' => (string)($service['description'] ?? ''),
                    'image' => $imgUrl,
                    'base_price' => $service['base_price'] ?? null,
                    'price_unit' => (string)($service['price_unit'] ?? ''),
                    'price_label' => (string)($service['price_label'] ?? ''),
                    'show_price' => (int)($service['show_price'] ?? 0),
                ],
            ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
            return;
        }

        $this->view('services.show', [
            'title' => (string)$service['title'],
            'service' => $service,
        ]);
    }
}

