<?php
declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Core\AdminAudit;
use App\Core\Auth;
use App\Core\Upload;
use App\Models\Project;
use App\Models\Translation;

final class ProjectsController extends BaseController
{
    public function index(): void
    {
        $this->requireAdmin(['projects.view']);

        $projects = Project::all();
        $ids = array_map(static fn (array $p): int => (int)$p['id'], $projects);

        $this->view('admin.projects.index', [
            'title' => 'Réalisations',
            'projects' => $projects,
            'projectFirstImages' => $ids !== [] ? Project::firstImagesByProjectIds($ids) : [],
            'flash' => $_SESSION['flash_success'] ?? null,
            'error' => $_SESSION['flash_error'] ?? null,
        ], 'layouts/admin');

        unset($_SESSION['flash_success'], $_SESSION['flash_error']);
    }

    public function create(): void
    {
        $this->requireAdmin(['projects.create']);

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
        $this->requireAdmin(['projects.create']);

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

        AdminAudit::log('project.create', 'project', $id, [
            'title' => $title,
            'slug' => $slug,
            'status' => $status,
            'images_added' => isset($_FILES['images']) ? count(self::normalizeMultiFiles($_FILES['images'])) : 0,
        ]);

        $_SESSION['flash_success'] = "Réalisation créée.";
        $this->redirect('/admin/projects');
    }

    public function edit(): void
    {
        $this->requireAdmin(['projects.update']);

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
        $this->requireAdmin(['projects.update']);

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

        AdminAudit::log('project.update', 'project', $id, [
            'title' => $title,
            'slug' => $slug,
            'status' => $status,
        ]);

        $_SESSION['flash_success'] = "Réalisation mise à jour.";
        $this->redirect('/admin/projects');
    }

    public function delete(): void
    {
        $this->requireAdmin(['projects.delete']);

        $id = (int)($_POST['id'] ?? 0);
        if ($id > 0) {
            $row = Project::find($id);
            Translation::deleteForEntity('project', $id);
            Project::delete($id);
            AdminAudit::log('project.delete', 'project', $id, [
                'title' => (string)($row['title'] ?? ''),
                'slug' => (string)($row['slug'] ?? ''),
            ]);
            $_SESSION['flash_success'] = "Réalisation supprimée.";
        }
        $this->redirect('/admin/projects');
    }

    public function deleteImage(): void
    {
        $this->requireAdmin(['projects.update']);

        $projectId = (int)($_POST['project_id'] ?? 0);
        $imageId = (int)($_POST['image_id'] ?? 0);
        if ($imageId > 0) {
            Project::deleteImage($imageId);
            AdminAudit::log('project.image.delete', 'project_image', $imageId, [
                'project_id' => $projectId,
            ]);
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

