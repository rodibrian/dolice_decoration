<?php
declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Core\Auth;

final class DashboardController extends BaseController
{
    public function index(): void
    {
        if (!Auth::check()) {
            $this->redirect('/admin/login');
        }

        $this->view('admin.dashboard', [
            'title' => 'Dashboard',
            'user' => Auth::user(),
        ], 'layouts/admin');
    }
}
