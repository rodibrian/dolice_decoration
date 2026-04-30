<?php
declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Core\Auth;
use App\Core\Upload;
use App\Models\HeroSlide;

final class HeroSlidesController extends BaseController
{
    public function index(): void
    {
        $this->requireAdmin(['hero_slides.view']);

        $slides = [];
        $error = $_SESSION['flash_error'] ?? null;
        try {
            $slides = HeroSlide::all();
        } catch (\Throwable $e) {
            $slides = [];
            $error = $error ?: "Table hero_slides manquante. Exécute la requête SQL de création dans `database/schema.sql`.";
        }

        $this->view('admin.hero_slides.index', [
            'title' => 'Slides accueil',
            'slides' => $slides,
            'flash' => $_SESSION['flash_success'] ?? null,
            'error' => $error,
        ], 'layouts/admin');

        unset($_SESSION['flash_success'], $_SESSION['flash_error']);
    }

    public function create(): void
    {
        $this->requireAdmin(['hero_slides.create']);

        $this->view('admin.hero_slides.form', [
            'title' => 'Nouveau slide',
            'slide' => null,
            'error' => $_SESSION['flash_error'] ?? null,
        ], 'layouts/admin');

        unset($_SESSION['flash_error']);
    }

    public function store(): void
    {
        $this->requireAdmin(['hero_slides.create']);

        $title = trim((string)($_POST['title'] ?? '')) ?: null;
        $subtitle = trim((string)($_POST['subtitle'] ?? '')) ?: null;
        $ctaLabel = trim((string)($_POST['cta_label'] ?? '')) ?: null;
        $ctaUrl = trim((string)($_POST['cta_url'] ?? '')) ?: null;
        $displayOrder = (int)($_POST['display_order'] ?? 0);
        $isPublished = isset($_POST['is_published']) ? 1 : 0;

        $mediaPath = null;
        $mediaType = null;
        if (isset($_FILES['media']) && is_array($_FILES['media'])) {
            $stored = Upload::storeHeroMedia($_FILES['media']);
            if ($stored !== null) {
                $mediaPath = $stored['path'];
                $mediaType = $stored['type'];
            }
        }

        if ($mediaPath === null || $mediaType === null) {
            $_SESSION['flash_error'] = "Fichier requis (image: jpg/png/webp, vidéo: mp4/webm).";
            $this->redirect('/admin/hero-slides/create');
        }

        HeroSlide::create([
            'title' => $title,
            'subtitle' => $subtitle,
            'media_type' => $mediaType,
            'media_path' => $mediaPath,
            'cta_label' => $ctaLabel,
            'cta_url' => $ctaUrl,
            'display_order' => $displayOrder,
            'is_published' => $isPublished,
        ]);

        $_SESSION['flash_success'] = "Slide créé.";
        $this->redirect('/admin/hero-slides');
    }

    public function edit(): void
    {
        $this->requireAdmin(['hero_slides.update']);

        $id = (int)($_GET['id'] ?? 0);
        $slide = $id > 0 ? HeroSlide::find($id) : null;
        if ($slide === null) {
            $_SESSION['flash_error'] = "Slide introuvable.";
            $this->redirect('/admin/hero-slides');
        }

        $this->view('admin.hero_slides.form', [
            'title' => 'Modifier slide',
            'slide' => $slide,
            'error' => $_SESSION['flash_error'] ?? null,
        ], 'layouts/admin');

        unset($_SESSION['flash_error']);
    }

    public function update(): void
    {
        $this->requireAdmin(['hero_slides.update']);

        $id = (int)($_POST['id'] ?? 0);
        $slide = $id > 0 ? HeroSlide::find($id) : null;
        if ($slide === null) {
            $_SESSION['flash_error'] = "Slide introuvable.";
            $this->redirect('/admin/hero-slides');
        }

        $title = trim((string)($_POST['title'] ?? '')) ?: null;
        $subtitle = trim((string)($_POST['subtitle'] ?? '')) ?: null;
        $ctaLabel = trim((string)($_POST['cta_label'] ?? '')) ?: null;
        $ctaUrl = trim((string)($_POST['cta_url'] ?? '')) ?: null;
        $displayOrder = (int)($_POST['display_order'] ?? 0);
        $isPublished = isset($_POST['is_published']) ? 1 : 0;

        $mediaPath = trim((string)($slide['media_path'] ?? ''));
        $mediaType = (string)($slide['media_type'] ?? 'image');

        if (isset($_FILES['media']) && is_array($_FILES['media'])) {
            $stored = Upload::storeHeroMedia($_FILES['media']);
            if ($stored !== null) {
                $mediaPath = $stored['path'];
                $mediaType = $stored['type'];
            }
        }

        if ($mediaPath === '') {
            $_SESSION['flash_error'] = "Fichier requis (image: jpg/png/webp, vidéo: mp4/webm).";
            $this->redirect('/admin/hero-slides/edit?id=' . $id);
        }

        if (!in_array($mediaType, ['image', 'video'], true)) {
            $mediaType = 'image';
        }

        HeroSlide::update($id, [
            'title' => $title,
            'subtitle' => $subtitle,
            'media_type' => $mediaType,
            'media_path' => $mediaPath,
            'cta_label' => $ctaLabel,
            'cta_url' => $ctaUrl,
            'display_order' => $displayOrder,
            'is_published' => $isPublished,
        ]);

        $_SESSION['flash_success'] = "Slide mis à jour.";
        $this->redirect('/admin/hero-slides');
    }

    public function delete(): void
    {
        $this->requireAdmin(['hero_slides.delete']);

        $id = (int)($_POST['id'] ?? 0);
        if ($id > 0) {
            HeroSlide::delete($id);
            $_SESSION['flash_success'] = "Slide supprimé.";
        }
        $this->redirect('/admin/hero-slides');
    }
}

