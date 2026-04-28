<?php
declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Core\Auth;
use App\Core\Upload;
use App\Models\Project;

final class ProjectsController extends BaseController
{
    public function index(): void
    {
        if (!Auth::check()) {
            $this->redirect('/admin/login');
        }

        $this->view('admin.projects.index', [
            'title' => 'Réalisations',
            'projects' => Project::all(),
            'flash' => $_SESSION['flash_success'] ?? null,
            'error' => $_SESSION['flash_error'] ?? null,
        ], 'layouts/admin');

        unset($_SESSION['flash_success'], $_SESSION['flash_error']);
    }

    public function create(): void
    {
        if (!Auth::check()) {
            $this->redirect('/admin/login');
        }

        $this->view('admin.projects.form', [
            'title' => 'Nouvelle réalisation',
            'project' => null,
            'images' => [],
            'error' => $_SESSION['flash_error'] ?? null,
        ], 'layouts/admin');

        unset($_SESSION['flash_error']);
    }

    public function store(): void
    {
        if (!Auth::check()) {
            $this->redirect('/admin/login');
        }

        $title = trim((string)($_POST['title'] ?? ''));
        $slug = trim((string)($_POST['slug'] ?? ''));
        $category = trim((string)($_POST['category'] ?? '')) ?: null;
        $workType = trim((string)($_POST['work_type'] ?? '')) ?: null;
        $location = trim((string)($_POST['location'] ?? '')) ?: null;
        $projectDate = trim((string)($_POST['project_date'] ?? '')) ?: null;
        $description = trim((string)($_POST['description'] ?? '')) ?: null;
        $isFeatured = isset($_POST['is_featured']) ? 1 : 0;
        $status = (string)($_POST['status'] ?? 'draft');
        if (!in_array($status, ['draft', 'published'], true)) {
            $status = 'draft';
        }

        if ($title === '' || $slug === '') {
            $_SESSION['flash_error'] = "Titre et slug requis.";
            $this->redirect('/admin/projects/create');
        }

        $id = Project::create([
            'title' => $title,
            'slug' => $slug,
            'category' => $category,
            'work_type' => $workType,
            'location' => $location,
            'project_date' => $projectDate,
            'description' => $description,
            'is_featured' => $isFeatured,
            'status' => $status,
        ]);

        // Multi-images
        if (isset($_FILES['images']) && is_array($_FILES['images'])) {
            $files = self::normalizeMultiFiles($_FILES['images']);
            foreach ($files as $f) {
                $path = Upload::storeImage($f);
                if ($path !== null) {
                    Project::addImage($id, $path, 0);
                }
            }
        }

        $_SESSION['flash_success'] = "Réalisation créée.";
        $this->redirect('/admin/projects');
    }

    public function edit(): void
    {
        if (!Auth::check()) {
            $this->redirect('/admin/login');
        }

        $id = (int)($_GET['id'] ?? 0);
        $project = $id > 0 ? Project::find($id) : null;
        if ($project === null) {
            $_SESSION['flash_error'] = "Réalisation introuvable.";
            $this->redirect('/admin/projects');
        }

        $this->view('admin.projects.form', [
            'title' => 'Modifier réalisation',
            'project' => $project,
            'images' => Project::images($id),
            'error' => $_SESSION['flash_error'] ?? null,
        ], 'layouts/admin');

        unset($_SESSION['flash_error']);
    }

    public function update(): void
    {
        if (!Auth::check()) {
            $this->redirect('/admin/login');
        }

        $id = (int)($_POST['id'] ?? 0);
        $project = $id > 0 ? Project::find($id) : null;
        if ($project === null) {
            $_SESSION['flash_error'] = "Réalisation introuvable.";
            $this->redirect('/admin/projects');
        }

        $title = trim((string)($_POST['title'] ?? ''));
        $slug = trim((string)($_POST['slug'] ?? ''));
        $category = trim((string)($_POST['category'] ?? '')) ?: null;
        $workType = trim((string)($_POST['work_type'] ?? '')) ?: null;
        $location = trim((string)($_POST['location'] ?? '')) ?: null;
        $projectDate = trim((string)($_POST['project_date'] ?? '')) ?: null;
        $description = trim((string)($_POST['description'] ?? '')) ?: null;
        $isFeatured = isset($_POST['is_featured']) ? 1 : 0;
        $status = (string)($_POST['status'] ?? 'draft');
        if (!in_array($status, ['draft', 'published'], true)) {
            $status = 'draft';
        }

        if ($title === '' || $slug === '') {
            $_SESSION['flash_error'] = "Titre et slug requis.";
            $this->redirect('/admin/projects/edit?id=' . $id);
        }

        Project::update($id, [
            'title' => $title,
            'slug' => $slug,
            'category' => $category,
            'work_type' => $workType,
            'location' => $location,
            'project_date' => $projectDate,
            'description' => $description,
            'is_featured' => $isFeatured,
            'status' => $status,
        ]);

        // Add more images
        if (isset($_FILES['images']) && is_array($_FILES['images'])) {
            $files = self::normalizeMultiFiles($_FILES['images']);
            foreach ($files as $f) {
                $path = Upload::storeImage($f);
                if ($path !== null) {
                    Project::addImage($id, $path, 0);
                }
            }
        }

        $_SESSION['flash_success'] = "Réalisation mise à jour.";
        $this->redirect('/admin/projects');
    }

    public function delete(): void
    {
        if (!Auth::check()) {
            $this->redirect('/admin/login');
        }

        $id = (int)($_POST['id'] ?? 0);
        if ($id > 0) {
            Project::delete($id);
            $_SESSION['flash_success'] = "Réalisation supprimée.";
        }
        $this->redirect('/admin/projects');
    }

    public function deleteImage(): void
    {
        if (!Auth::check()) {
            $this->redirect('/admin/login');
        }

        $projectId = (int)($_POST['project_id'] ?? 0);
        $imageId = (int)($_POST['image_id'] ?? 0);
        if ($imageId > 0) {
            Project::deleteImage($imageId);
            $_SESSION['flash_success'] = "Image supprimée.";
        }
        $this->redirect('/admin/projects/edit?id=' . $projectId);
    }

    /**
     * @param array<string, mixed> $multi
     * @return list<array<string, mixed>>
     */
    private static function normalizeMultiFiles(array $multi): array
    {
        $names = $multi['name'] ?? [];
        if (!is_array($names)) {
            return [];
        }

        $out = [];
        $count = count($names);
        for ($i = 0; $i < $count; $i++) {
            $out[] = [
                'name' => $multi['name'][$i] ?? '',
                'type' => $multi['type'][$i] ?? '',
                'tmp_name' => $multi['tmp_name'][$i] ?? '',
                'error' => $multi['error'][$i] ?? UPLOAD_ERR_NO_FILE,
                'size' => $multi['size'][$i] ?? 0,
            ];
        }
        return $out;
    }
}

