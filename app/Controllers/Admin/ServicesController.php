<?php
declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Core\Auth;
use App\Core\Upload;
use App\Models\Service;

final class ServicesController extends BaseController
{
    public function index(): void
    {
        if (!Auth::check()) {
            $this->redirect('/admin/login');
        }

        $this->view('admin.services.index', [
            'title' => 'Services',
            'services' => Service::all(),
            'flash' => $_SESSION['flash_success'] ?? null,
        ], 'layouts/admin');

        unset($_SESSION['flash_success']);
    }

    public function create(): void
    {
        if (!Auth::check()) {
            $this->redirect('/admin/login');
        }

        $this->view('admin.services.form', [
            'title' => 'Nouveau service',
            'service' => null,
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
        $description = trim((string)($_POST['description'] ?? '')) ?: null;
        $displayOrder = (int)($_POST['display_order'] ?? 0);
        $isPublished = isset($_POST['is_published']) ? 1 : 0;

        if ($title === '' || $slug === '') {
            $_SESSION['flash_error'] = "Titre et slug requis.";
            $this->redirect('/admin/services/create');
        }

        $imagePath = null;
        if (isset($_FILES['image']) && is_array($_FILES['image'])) {
            $imagePath = Upload::storeImage($_FILES['image']);
        }

        Service::create([
            'title' => $title,
            'slug' => $slug,
            'category' => $category,
            'description' => $description,
            'image_path' => $imagePath,
            'display_order' => $displayOrder,
            'is_published' => $isPublished,
        ]);

        $_SESSION['flash_success'] = "Service créé.";
        $this->redirect('/admin/services');
    }

    public function edit(): void
    {
        if (!Auth::check()) {
            $this->redirect('/admin/login');
        }

        $id = (int)($_GET['id'] ?? 0);
        $service = $id > 0 ? Service::find($id) : null;
        if ($service === null) {
            $_SESSION['flash_error'] = "Service introuvable.";
            $this->redirect('/admin/services');
        }

        $this->view('admin.services.form', [
            'title' => 'Modifier service',
            'service' => $service,
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
        $service = $id > 0 ? Service::find($id) : null;
        if ($service === null) {
            $_SESSION['flash_error'] = "Service introuvable.";
            $this->redirect('/admin/services');
        }

        $title = trim((string)($_POST['title'] ?? ''));
        $slug = trim((string)($_POST['slug'] ?? ''));
        $category = trim((string)($_POST['category'] ?? '')) ?: null;
        $description = trim((string)($_POST['description'] ?? '')) ?: null;
        $displayOrder = (int)($_POST['display_order'] ?? 0);
        $isPublished = isset($_POST['is_published']) ? 1 : 0;

        if ($title === '' || $slug === '') {
            $_SESSION['flash_error'] = "Titre et slug requis.";
            $this->redirect('/admin/services/edit?id=' . $id);
        }

        $imagePath = (string)($service['image_path'] ?? '');
        if (isset($_FILES['image']) && is_array($_FILES['image'])) {
            $newPath = Upload::storeImage($_FILES['image']);
            if ($newPath !== null) {
                $imagePath = $newPath;
            }
        }

        Service::update($id, [
            'title' => $title,
            'slug' => $slug,
            'category' => $category,
            'description' => $description,
            'image_path' => $imagePath !== '' ? $imagePath : null,
            'display_order' => $displayOrder,
            'is_published' => $isPublished,
        ]);

        $_SESSION['flash_success'] = "Service mis à jour.";
        $this->redirect('/admin/services');
    }

    public function delete(): void
    {
        if (!Auth::check()) {
            $this->redirect('/admin/login');
        }

        $id = (int)($_POST['id'] ?? 0);
        if ($id > 0) {
            Service::delete($id);
            $_SESSION['flash_success'] = "Service supprimé.";
        }

        $this->redirect('/admin/services');
    }
}

