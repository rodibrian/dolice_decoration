<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Models\Service;
use App\Models\Translation;

final class ServicesPublicController extends BaseController
{
    /**
     * @param array<string, mixed> $row
     * @return array<string, mixed>
     */
    private function mergeServiceTranslations(array $row): array
    {
        $id = (int)($row['id'] ?? 0);

        return $id > 0 ? Translation::mergeRow('service', $id, $row, ['title', 'description', 'category', 'price_label', 'price_unit']) : $row;
    }
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
        $services = array_map(fn (array $s): array => $this->mergeServiceTranslations($s), Service::published());
        $this->view('services.index', [
            'title' => t('nav.services'),
            'services' => $services,
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

        $service = $this->mergeServiceTranslations($service);

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

