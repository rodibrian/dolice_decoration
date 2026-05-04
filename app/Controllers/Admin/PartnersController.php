<?php
declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Core\AdminAudit;
use App\Core\Auth;
use App\Core\Upload;
use App\Models\Partner;
use App\Models\Translation;

final class PartnersController extends BaseController
{
    public function index(): void
    {
        $this->requireAdmin(['partners.view']);

        $this->view('admin.partners.index', [
            'title' => 'Partenaires',
            'partners' => Partner::all(),
            'flash' => $_SESSION['flash_success'] ?? null,
            'error' => $_SESSION['flash_error'] ?? null,
        ], 'layouts/admin');

        unset($_SESSION['flash_success'], $_SESSION['flash_error']);
    }

    public function create(): void
    {
        $this->requireAdmin(['partners.create']);

        $this->view('admin.partners.form', [
            'title' => 'Nouveau partenaire',
            'partner' => null,
            'error' => $_SESSION['flash_error'] ?? null,
        ], 'layouts/admin');

        unset($_SESSION['flash_error']);
    }

    public function store(): void
    {
        $this->requireAdmin(['partners.create']);

        $name = trim((string)($_POST['name'] ?? ''));
        $url = trim((string)($_POST['url'] ?? '')) ?: null;
        $category = trim((string)($_POST['category'] ?? '')) ?: null;
        $displayOrder = (int)($_POST['display_order'] ?? 0);
        $isPublished = isset($_POST['is_published']) ? 1 : 0;

        if ($name === '') {
            $_SESSION['flash_error'] = "Nom requis.";
            $this->redirect('/admin/partners/create');
        }

        $logoPath = null;
        if (isset($_FILES['logo']) && is_array($_FILES['logo'])) {
            $logoPath = Upload::storeImage($_FILES['logo']);
        }

        $newId = Partner::create([
            'name' => $name,
            'logo_path' => $logoPath,
            'url' => $url,
            'category' => $category,
            'display_order' => $displayOrder,
            'is_published' => $isPublished,
        ]);

        AdminAudit::log('partner.create', 'partner', $newId, [
            'name' => $name,
            'is_published' => $isPublished,
        ]);

        $_SESSION['flash_success'] = "Partenaire créé.";
        $this->redirect('/admin/partners');
    }

    public function edit(): void
    {
        $this->requireAdmin(['partners.update']);

        $id = (int)($_GET['id'] ?? 0);
        $partner = $id > 0 ? Partner::find($id) : null;
        if ($partner === null) {
            $_SESSION['flash_error'] = "Partenaire introuvable.";
            $this->redirect('/admin/partners');
        }

        $this->view('admin.partners.form', [
            'title' => 'Modifier partenaire',
            'partner' => $partner,
            'error' => $_SESSION['flash_error'] ?? null,
        ], 'layouts/admin');

        unset($_SESSION['flash_error']);
    }

    public function update(): void
    {
        $this->requireAdmin(['partners.update']);

        $id = (int)($_POST['id'] ?? 0);
        $partner = $id > 0 ? Partner::find($id) : null;
        if ($partner === null) {
            $_SESSION['flash_error'] = "Partenaire introuvable.";
            $this->redirect('/admin/partners');
        }

        $name = trim((string)($_POST['name'] ?? ''));
        $url = trim((string)($_POST['url'] ?? '')) ?: null;
        $category = trim((string)($_POST['category'] ?? '')) ?: null;
        $displayOrder = (int)($_POST['display_order'] ?? 0);
        $isPublished = isset($_POST['is_published']) ? 1 : 0;

        if ($name === '') {
            $_SESSION['flash_error'] = "Nom requis.";
            $this->redirect('/admin/partners/edit?id=' . $id);
        }

        $logoPath = (string)($partner['logo_path'] ?? '');
        if (isset($_FILES['logo']) && is_array($_FILES['logo'])) {
            $newPath = Upload::storeImage($_FILES['logo']);
            if ($newPath !== null) {
                $logoPath = $newPath;
            }
        }

        Partner::update($id, [
            'name' => $name,
            'logo_path' => $logoPath !== '' ? $logoPath : null,
            'url' => $url,
            'category' => $category,
            'display_order' => $displayOrder,
            'is_published' => $isPublished,
        ]);

        AdminAudit::log('partner.update', 'partner', $id, [
            'name' => $name,
            'is_published' => $isPublished,
        ]);

        $_SESSION['flash_success'] = "Partenaire mis à jour.";
        $this->redirect('/admin/partners');
    }

    public function delete(): void
    {
        $this->requireAdmin(['partners.delete']);

        $id = (int)($_POST['id'] ?? 0);
        if ($id > 0) {
            $row = Partner::find($id);
            Translation::deleteForEntity('partner', $id);
            Partner::delete($id);
            AdminAudit::log('partner.delete', 'partner', $id, [
                'name' => (string)($row['name'] ?? ''),
            ]);
            $_SESSION['flash_success'] = "Partenaire supprimé.";
        }
        $this->redirect('/admin/partners');
    }
}

