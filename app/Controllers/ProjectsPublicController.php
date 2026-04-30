<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Models\Project;

final class ProjectsPublicController extends BaseController
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
        $category = trim((string)($_GET['category'] ?? '')) ?: null;
        $projects = Project::published(0, $category);
        $projectIds = array_map(
            static fn (array $p): int => (int)($p['id'] ?? 0),
            $projects
        );
        $imageMap = Project::imagesByProjectIds($projectIds, 5);

        $projects = array_map(static function (array $project) use ($imageMap): array {
            $id = (int)($project['id'] ?? 0);
            $imgs = $imageMap[$id] ?? [];
            $project['images'] = $imgs;
            $project['cover_image'] = $imgs[0] ?? null;
            return $project;
        }, $projects);

        $this->view('projects.index', [
            'title' => 'Réalisations',
            'projects' => $projects,
            'category' => $category,
        ]);
    }

    public function show(): void
    {
        $slug = trim((string)($_GET['slug'] ?? ''));
        $project = $slug !== '' ? Project::findBySlug($slug) : null;
        if ($project === null || (string)($project['status'] ?? 'draft') !== 'published') {
            http_response_code(404);
            echo '404';
            return;
        }

        $images = Project::images((int)$project['id']);

        $isModal = isset($_GET['modal']) && (string)$_GET['modal'] === '1';
        if ($isModal) {
            $base = $this->appUrlBase();
            $imageUrls = array_values(array_filter(array_map(static function (array $img) use ($base): ?string {
                $p = trim((string)($img['image_path'] ?? ''));
                if ($p === '') {
                    return null;
                }
                if (preg_match('#^https?://#i', $p) === 1) {
                    return $p;
                }
                return $base . '/' . ltrim($p, '/');
            }, $images)));

            header('Content-Type: application/json; charset=utf-8');
            echo json_encode([
                'project' => [
                    'title' => (string)($project['title'] ?? ''),
                    'location' => (string)($project['location'] ?? ''),
                    'category' => (string)($project['category'] ?? ''),
                    'work_type' => (string)($project['work_type'] ?? ''),
                    'project_date' => (string)($project['project_date'] ?? ''),
                    'description' => (string)($project['description'] ?? ''),
                ],
                'images' => $imageUrls,
            ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
            return;
        }

        $this->view('projects.show', [
            'title' => (string)$project['title'],
            'project' => $project,
            'images' => $images,
        ]);
    }
}

