<?php
declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\AuditLog;

final class LogsController extends BaseController
{
    public function index(): void
    {
        $this->requireAdmin(['admin.super']);

        $this->view('admin.logs.index', [
            'title' => 'Logs',
            'logs' => AuditLog::latest(400),
        ], 'layouts/admin');
    }
}

