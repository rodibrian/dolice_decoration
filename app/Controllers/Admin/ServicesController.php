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
        $this->requireAdmin(['services.view']);

        $this->view('admin.services.index', [
            'title' => 'Services',
            'services' => Service::all(),
            'flash' => $_SESSION['flash_success'] ?? null,
        ], 'layouts/admin');

        unset($_SESSION['flash_success']);
    }

    public function create(): void
    {
        $this->requireAdmin(['services.create']);

        $this->view('admin.services.form', [
            'title' => 'Nouveau service',
            'service' => null,
            'error' => $_SESSION['flash_error'] ?? null,
        ], 'layouts/admin');

        unset($_SESSION['flash_error']);
    }

    public function store(): void
    {
        $this->requireAdmin(['services.create']);

        $title = trim((string)($_POST['title'] ?? ''));
        $slug = trim((string)($_POST['slug'] ?? ''));
        $category = trim((string)($_POST['category'] ?? '')) ?: null;
        $description = trim((string)($_POST['description'] ?? '')) ?: null;
        $basePriceRaw = trim((string)($_POST['base_price'] ?? ''));
        $basePrice = $basePriceRaw === '' ? null : (float)$basePriceRaw;
        if ($basePrice !== null && $basePrice < 0) {
            $basePrice = null;
        }
        $priceUnit = trim((string)($_POST['price_unit'] ?? '')) ?: null;
        $priceLabel = trim((string)($_POST['price_label'] ?? '')) ?: null;
        $showPrice = isset($_POST['show_price']) ? 1 : 0;
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
            'base_price' => $basePrice,
            'price_unit' => $priceUnit,
            'price_label' => $priceLabel,
            'show_price' => $showPrice,
            'display_order' => $displayOrder,
            'is_published' => $isPublished,
        ]);

        if (($basePrice !== null || $showPrice === 1 || $priceUnit !== null || $priceLabel !== null) && !Service::supportsPricing()) {
            $_SESSION['flash_error'] = "Les champs prix ne peuvent pas être enregistrés tant que la base n'est pas migrée (colonnes services: base_price, show_price...). Applique l'ALTER TABLE indiqué dans `database/schema.sql`.";
        }

        $_SESSION['flash_success'] = "Service créé.";
        $this->redirect('/admin/services');
    }

    public function edit(): void
    {
        $this->requireAdmin(['services.update']);

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
        $this->requireAdmin(['services.update']);

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
        $basePriceRaw = trim((string)($_POST['base_price'] ?? ''));
        $basePrice = $basePriceRaw === '' ? null : (float)$basePriceRaw;
        if ($basePrice !== null && $basePrice < 0) {
            $basePrice = null;
        }
        $priceUnit = trim((string)($_POST['price_unit'] ?? '')) ?: null;
        $priceLabel = trim((string)($_POST['price_label'] ?? '')) ?: null;
        $showPrice = isset($_POST['show_price']) ? 1 : 0;
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
            'base_price' => $basePrice,
            'price_unit' => $priceUnit,
            'price_label' => $priceLabel,
            'show_price' => $showPrice,
            'display_order' => $displayOrder,
            'is_published' => $isPublished,
        ]);

        if (($basePrice !== null || $showPrice === 1 || $priceUnit !== null || $priceLabel !== null) && !Service::supportsPricing()) {
            $_SESSION['flash_error'] = "Les champs prix ne peuvent pas être enregistrés tant que la base n'est pas migrée (colonnes services: base_price, show_price...). Applique l'ALTER TABLE indiqué dans `database/schema.sql`.";
        }

        $_SESSION['flash_success'] = "Service mis à jour.";
        $this->redirect('/admin/services');
    }

    public function delete(): void
    {
        $this->requireAdmin(['services.delete']);

        $id = (int)($_POST['id'] ?? 0);
        if ($id > 0) {
            Service::delete($id);
            $_SESSION['flash_success'] = "Service supprimé.";
        }

        $this->redirect('/admin/services');
    }
}

