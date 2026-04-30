<?php
declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Core\Auth;
use App\Models\QuoteRequest;

final class QuotesController extends BaseController
{
    public function index(): void
    {
        if (!Auth::check()) {
            $this->redirect('/admin/login');
        }

        $this->view('admin.quotes.index', [
            'title' => 'Demandes de devis',
            'quotes' => QuoteRequest::all(),
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
        $quote = $id > 0 ? QuoteRequest::find($id) : null;
        if ($quote === null) {
            $_SESSION['flash_error'] = "Demande introuvable.";
            $this->redirect('/admin/quotes');
        }

        $this->view('admin.quotes.show', [
            'title' => 'Devis #' . $id,
            'quote' => $quote,
            'items' => QuoteRequest::items($id),
            'flash' => $_SESSION['flash_success'] ?? null,
            'error' => $_SESSION['flash_error'] ?? null,
        ], 'layouts/admin');

        unset($_SESSION['flash_success'], $_SESSION['flash_error']);
    }

    public function update(): void
    {
        if (!Auth::check()) {
            $this->redirect('/admin/login');
        }

        $id = (int)($_POST['id'] ?? 0);
        $quote = $id > 0 ? QuoteRequest::find($id) : null;
        if ($quote === null) {
            $_SESSION['flash_error'] = "Demande introuvable.";
            $this->redirect('/admin/quotes');
        }

        $status = (string)($_POST['status'] ?? 'new');
        $allowed = ['new', 'in_progress', 'replied', 'done', 'archived'];
        if (!in_array($status, $allowed, true)) {
            $status = 'new';
        }
        $notes = trim((string)($_POST['internal_notes'] ?? ''));
        $notes = $notes === '' ? null : $notes;

        QuoteRequest::updateStatus($id, $status);
        QuoteRequest::updateNotes($id, $notes);

        $_SESSION['flash_success'] = "Devis mis à jour.";
        $this->redirect('/admin/quotes/show?id=' . $id);
    }
}

