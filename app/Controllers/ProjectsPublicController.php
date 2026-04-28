<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Models\Project;

final class ProjectsPublicController extends BaseController
{
    public function index(): void
    {
        $category = trim((string)($_GET['category'] ?? '')) ?: null;
        $this->view('projects.index', [
            'title' => 'Réalisations',
            'projects' => Project::published(0, $category),
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
        $this->view('projects.show', [
            'title' => (string)$project['title'],
            'project' => $project,
            'images' => $images,
        ]);
    }
}

