<?php
declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Core\Auth;
use App\Core\DB;

final class DashboardController extends BaseController
{
    public function index(): void
    {
        $this->requireAdmin(['dashboard.view']);

        $pdo = DB::pdo();
        $kpi = [
            'quotes_new' => (int)$pdo->query("SELECT COUNT(*) FROM quote_requests WHERE status = 'new'")->fetchColumn(),
            'messages_new' => (int)$pdo->query("SELECT COUNT(*) FROM contact_messages WHERE status = 'new'")->fetchColumn(),
            'projects_published' => (int)$pdo->query("SELECT COUNT(*) FROM projects WHERE status = 'published'")->fetchColumn(),
            'services_published' => (int)$pdo->query("SELECT COUNT(*) FROM services WHERE is_published = 1")->fetchColumn(),
            'posts_published' => (int)$pdo->query("SELECT COUNT(*) FROM posts WHERE status = 'published'")->fetchColumn(),
            'testimonials_pending' => (int)$pdo->query("SELECT COUNT(*) FROM testimonials WHERE status = 'pending'")->fetchColumn(),
        ];

        $this->view('admin.dashboard', [
            'title' => 'Dashboard',
            'user' => Auth::user(),
            'kpi' => $kpi,
        ], 'layouts/admin');
    }
}
