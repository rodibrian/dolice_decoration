<?php
declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Core\Auth;
use App\Models\Page;

final class PagesController extends BaseController
{
    public function index(): void
    {
        $this->requireAdmin(['pages.view']);

        $this->view('admin.pages.index', [
            'title' => 'Pages',
            'pages' => Page::all(),
            'flash' => $_SESSION['flash_success'] ?? null,
        ], 'layouts/admin');

        unset($_SESSION['flash_success']);
    }

    public function edit(): void
    {
        $this->requireAdmin(['pages.update']);

        $key = trim((string)($_GET['page_key'] ?? ''));
        if ($key === '') {
            $this->redirect('/admin/pages');
        }

        $page = Page::findByKey($key);
        if ($page === null) {
            $page = [
                'page_key' => $key,
                'title' => ucfirst(str_replace('_', ' ', $key)),
                'content' => '',
            ];
        }

        $this->view('admin.pages.form', [
            'title' => 'Éditer page',
            'page' => $page,
            'error' => $_SESSION['flash_error'] ?? null,
        ], 'layouts/admin');

        unset($_SESSION['flash_error']);
    }

    public function update(): void
    {
        $this->requireAdmin(['pages.update']);

        $key = trim((string)($_POST['page_key'] ?? ''));
        $title = trim((string)($_POST['title'] ?? ''));
        $content = (string)($_POST['content'] ?? '');

        if ($key === '' || $title === '') {
            $_SESSION['flash_error'] = "Clé et titre requis.";
            $this->redirect('/admin/pages/edit?page_key=' . urlencode($key));
        }

        Page::upsert($key, $title, $content === '' ? null : $content);
        $_SESSION['flash_success'] = "Page mise à jour.";
        $this->redirect('/admin/pages');
    }
}

