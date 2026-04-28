<?php
declare(strict_types=1);

namespace App\Core;

use App\Controllers\HomeController;
use App\Controllers\Admin\AuthController;
use App\Controllers\Admin\DashboardController;

final class App
{
    public function run(): void
    {
        $router = new Router();

        // Public
        $router->get('/', [HomeController::class, 'index']);

        // Admin auth
        $router->get('/admin/login', [AuthController::class, 'showLogin']);
        $router->post('/admin/login', [AuthController::class, 'login']);
        $router->post('/admin/logout', [AuthController::class, 'logout']);

        // Admin
        $router->get('/admin', [DashboardController::class, 'index']);

        $router->dispatch();
    }
}
