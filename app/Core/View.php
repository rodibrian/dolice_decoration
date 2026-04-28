<?php
declare(strict_types=1);

namespace App\Core;

final class View
{
    /**
     * @param array<string, mixed> $data
     */
    public static function render(string $view, array $data = [], string $layout = 'layouts/main'): void
    {
        $viewPath = APP_PATH . '/Views/' . str_replace('.', '/', $view) . '.php';
        $layoutPath = APP_PATH . '/Views/' . str_replace('.', '/', $layout) . '.php';

        if (!is_file($viewPath)) {
            throw new \RuntimeException("Vue introuvable: {$viewPath}");
        }
        if (!is_file($layoutPath)) {
            throw new \RuntimeException("Layout introuvable: {$layoutPath}");
        }

        extract($data, EXTR_SKIP);

        ob_start();
        require $viewPath;
        $content = ob_get_clean();

        require $layoutPath;
    }
}
