<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Core\View;

abstract class BaseController
{
    /**
     * @param array<string, mixed> $data
     */
    protected function view(string $view, array $data = [], string $layout = 'layouts/main'): void
    {
        View::render($view, $data, $layout);
    }

    protected function redirect(string $path): void
    {
        header('Location: ' . $this->url($path));
        exit;
    }

    /**
     * Require authenticated admin session and optionally capabilities.
     *
     * @param list<string> $capabilities
     */
    protected function requireAdmin(array $capabilities = []): void
    {
        if (!\App\Core\Auth::check()) {
            $this->redirect('/admin/login');
        }
        foreach ($capabilities as $cap) {
            if (!\App\Core\Auth::can($cap)) {
                http_response_code(403);
                echo '403';
                exit;
            }
        }
    }

    protected function url(string $path): string
    {
        $base = rtrim(env('APP_URL', ''), '/');
        $path = '/' . ltrim($path, '/');
        return $base !== '' ? ($base . $path) : $path;
    }
}
