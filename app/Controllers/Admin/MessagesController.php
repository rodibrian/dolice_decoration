<?php
declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Core\Auth;
use App\Models\ContactMessage;

final class MessagesController extends BaseController
{
    public function index(): void
    {
        if (!Auth::check()) {
            $this->redirect('/admin/login');
        }

        $this->view('admin.messages.index', [
            'title' => 'Messages',
            'messages' => ContactMessage::all(),
            'flash' => $_SESSION['flash_success'] ?? null,
            'error' => $_SESSION['flash_error'] ?? null,
        ], 'layouts/admin');

        unset($_SESSION['flash_success'], $_SESSION['flash_error']);
    }

    public function show(): void
    {
        if (!Auth::check()) {
            $this->redirect('/admin/login');
        }

        $id = (int)($_GET['id'] ?? 0);
        $message = $id > 0 ? ContactMessage::find($id) : null;
        if ($message === null) {
            $_SESSION['flash_error'] = "Message introuvable.";
            $this->redirect('/admin/messages');
        }

        $this->view('admin.messages.show', [
            'title' => 'Message #' . $id,
            'message' => $message,
            'flash' => $_SESSION['flash_success'] ?? null,
            'error' => $_SESSION['flash_error'] ?? null,
        ], 'layouts/admin');

        unset($_SESSION['flash_success'], $_SESSION['flash_error']);
    }

    public function updateStatus(): void
    {
        if (!Auth::check()) {
            $this->redirect('/admin/login');
        }

        $id = (int)($_POST['id'] ?? 0);
        $message = $id > 0 ? ContactMessage::find($id) : null;
        if ($message === null) {
            $_SESSION['flash_error'] = "Message introuvable.";
            $this->redirect('/admin/messages');
        }

        $status = (string)($_POST['status'] ?? 'new');
        $allowed = ['new', 'read', 'archived'];
        if (!in_array($status, $allowed, true)) {
            $status = 'new';
        }

        ContactMessage::updateStatus($id, $status);
        $_SESSION['flash_success'] = "Statut mis à jour.";
        $this->redirect('/admin/messages/show?id=' . $id);
    }
}

