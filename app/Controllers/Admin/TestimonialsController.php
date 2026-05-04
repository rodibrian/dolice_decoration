<?php
declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Core\AdminAudit;
use App\Core\Auth;
use App\Core\Upload;
use App\Models\Testimonial;
use App\Models\Translation;

final class TestimonialsController extends BaseController
{
    public function index(): void
    {
        $this->requireAdmin(['testimonials.view']);

        $this->view('admin.testimonials.index', [
            'title' => 'Témoignages',
            'testimonials' => Testimonial::all(),
            'flash' => $_SESSION['flash_success'] ?? null,
            'error' => $_SESSION['flash_error'] ?? null,
        ], 'layouts/admin');

        unset($_SESSION['flash_success'], $_SESSION['flash_error']);
    }

    public function create(): void
    {
        $this->requireAdmin(['testimonials.create']);

        $this->view('admin.testimonials.form', [
            'title' => 'Nouveau témoignage',
            'testimonial' => null,
            'error' => $_SESSION['flash_error'] ?? null,
        ], 'layouts/admin');

        unset($_SESSION['flash_error']);
    }

    public function store(): void
    {
        $this->requireAdmin(['testimonials.create']);

        $clientName = trim((string)($_POST['client_name'] ?? ''));
        $clientCompany = trim((string)($_POST['client_company'] ?? '')) ?: null;
        $content = trim((string)($_POST['content'] ?? ''));
        $ratingRaw = trim((string)($_POST['rating'] ?? ''));
        $rating = $ratingRaw === '' ? null : (int)$ratingRaw;
        if ($rating !== null && ($rating < 1 || $rating > 5)) {
            $rating = null;
        }

        $status = (string)($_POST['status'] ?? 'pending');
        if (!in_array($status, ['pending', 'approved'], true)) {
            $status = 'pending';
        }

        if ($clientName === '' || $content === '') {
            $_SESSION['flash_error'] = "Nom client et contenu requis.";
            $this->redirect('/admin/testimonials/create');
        }

        $logoPath = null;
        if (isset($_FILES['logo']) && is_array($_FILES['logo'])) {
            $logoPath = Upload::storeImage($_FILES['logo']);
        }

        $newId = Testimonial::create([
            'client_name' => $clientName,
            'client_company' => $clientCompany,
            'content' => $content,
            'rating' => $rating,
            'logo_path' => $logoPath,
            'status' => $status,
        ]);

        AdminAudit::log('testimonial.create', 'testimonial', $newId, [
            'client_name' => $clientName,
            'status' => $status,
            'content_preview' => function_exists('mb_substr') ? mb_substr($content, 0, 120) : substr($content, 0, 120),
        ]);

        $_SESSION['flash_success'] = "Témoignage créé.";
        $this->redirect('/admin/testimonials');
    }

    public function edit(): void
    {
        $this->requireAdmin(['testimonials.update']);

        $id = (int)($_GET['id'] ?? 0);
        $testimonial = $id > 0 ? Testimonial::find($id) : null;
        if ($testimonial === null) {
            $_SESSION['flash_error'] = "Témoignage introuvable.";
            $this->redirect('/admin/testimonials');
        }

        $this->view('admin.testimonials.form', [
            'title' => 'Modifier témoignage',
            'testimonial' => $testimonial,
            'error' => $_SESSION['flash_error'] ?? null,
        ], 'layouts/admin');

        unset($_SESSION['flash_error']);
    }

    public function update(): void
    {
        $this->requireAdmin(['testimonials.update']);

        $id = (int)($_POST['id'] ?? 0);
        $testimonial = $id > 0 ? Testimonial::find($id) : null;
        if ($testimonial === null) {
            $_SESSION['flash_error'] = "Témoignage introuvable.";
            $this->redirect('/admin/testimonials');
        }

        $clientName = trim((string)($_POST['client_name'] ?? ''));
        $clientCompany = trim((string)($_POST['client_company'] ?? '')) ?: null;
        $content = trim((string)($_POST['content'] ?? ''));
        $ratingRaw = trim((string)($_POST['rating'] ?? ''));
        $rating = $ratingRaw === '' ? null : (int)$ratingRaw;
        if ($rating !== null && ($rating < 1 || $rating > 5)) {
            $rating = null;
        }

        $status = (string)($_POST['status'] ?? 'pending');
        if (!in_array($status, ['pending', 'approved'], true)) {
            $status = 'pending';
        }

        if ($clientName === '' || $content === '') {
            $_SESSION['flash_error'] = "Nom client et contenu requis.";
            $this->redirect('/admin/testimonials/edit?id=' . $id);
        }

        $logoPath = (string)($testimonial['logo_path'] ?? '');
        if (isset($_FILES['logo']) && is_array($_FILES['logo'])) {
            $newPath = Upload::storeImage($_FILES['logo']);
            if ($newPath !== null) {
                $logoPath = $newPath;
            }
        }

        Testimonial::update($id, [
            'client_name' => $clientName,
            'client_company' => $clientCompany,
            'content' => $content,
            'rating' => $rating,
            'logo_path' => $logoPath !== '' ? $logoPath : null,
            'status' => $status,
        ]);

        AdminAudit::log('testimonial.update', 'testimonial', $id, [
            'client_name' => $clientName,
            'status' => $status,
        ]);

        $_SESSION['flash_success'] = "Témoignage mis à jour.";
        $this->redirect('/admin/testimonials');
    }

    public function approve(): void
    {
        $this->requireAdmin(['testimonials.update']);

        $id = (int)($_POST['id'] ?? 0);
        if ($id > 0) {
            Testimonial::setStatus($id, 'approved');
            AdminAudit::log('testimonial.approve', 'testimonial', $id, ['status' => 'approved']);
            $_SESSION['flash_success'] = "Témoignage approuvé.";
        }
        $this->redirect('/admin/testimonials');
    }

    public function delete(): void
    {
        $this->requireAdmin(['testimonials.delete']);

        $id = (int)($_POST['id'] ?? 0);
        if ($id > 0) {
            $row = Testimonial::find($id);
            Translation::deleteForEntity('testimonial', $id);
            Testimonial::delete($id);
            AdminAudit::log('testimonial.delete', 'testimonial', $id, [
                'client_name' => (string)($row['client_name'] ?? ''),
            ]);
            $_SESSION['flash_success'] = "Témoignage supprimé.";
        }
        $this->redirect('/admin/testimonials');
    }
}

