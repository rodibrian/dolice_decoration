<?php
declare(strict_types=1);

namespace App\Core;

final class Router
{
    /** @var array<string, array<string, array{0: class-string, 1: string}>> */
    private array $routes = [
        'GET' => [],
        'POST' => [],
    ];

    /**
     * @var array<string, list<array{regex:string, handler:array{0:class-string,1:string}, params:list<string>}>>
     */
    private array $dynamicRoutes = [
        'GET' => [],
        'POST' => [],
    ];

    public function get(string $path, array $handler): void
    {
        $this->register('GET', $path, $handler);
    }

    public function post(string $path, array $handler): void
    {
        $this->register('POST', $path, $handler);
    }

    public function dispatch(): void
    {
        $method = strtoupper($_SERVER['REQUEST_METHOD'] ?? 'GET');
        $overridePath = trim((string)($_GET['path'] ?? ''));
        if ($overridePath !== '') {
            // `path` can come from a querystring (index.php?path=/route) and may be URL-encoded.
            $decoded = rawurldecode($overridePath);
            $path = $this->normalize($decoded);
        } else {
            $path = $this->normalize(parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/');
        }

        // Strip the front-controller directory (portable across /public, subfolders, vhosts, etc.)
        $scriptName = (string)($_SERVER['SCRIPT_NAME'] ?? '');
        $base = rtrim(str_replace('\\', '/', dirname($scriptName)), '/');
        if ($base !== '' && $base !== '/' && str_starts_with($path, $base)) {
            $path = substr($path, strlen($base));
        }
        $path = $this->normalize($path);

        $handler = $this->routes[$method][$path] ?? null;
        if ($handler === null) {
            $handler = $this->matchDynamic($method, $path);
            if ($handler === null) {
                http_response_code(404);
                echo "404";
                return;
            }
        }

        [$class, $action] = $handler;
        $controller = new $class();

        if (!method_exists($controller, $action)) {
            http_response_code(500);
            echo "Route handler invalide.";
            return;
        }

        $controller->{$action}();
    }

    /**
     * @param array{0: class-string, 1: string} $handler
     */
    private function register(string $method, string $path, array $handler): void
    {
        $path = $this->normalize($path);

        if (str_contains($path, '{') && str_contains($path, '}')) {
            [$regex, $params] = $this->compileDynamic($path);
            $this->dynamicRoutes[$method][] = [
                'regex' => $regex,
                'handler' => $handler,
                'params' => $params,
            ];
            return;
        }

        $this->routes[$method][$path] = $handler;
    }

    /**
     * @return array{0:string,1:list<string>}
     */
    private function compileDynamic(string $path): array
    {
        $params = [];
        $regex = '';

        if (preg_match_all('/\{([a-zA-Z_][a-zA-Z0-9_]*)\}/', $path, $matches, PREG_OFFSET_CAPTURE) !== 1) {
            // No params: treat as static.
            return ['#^' . preg_quote($path, '#') . '$#', $params];
        }

        $offset = 0;
        foreach ($matches[1] as $i => $m) {
            $name = (string)$m[0];
            $full = $matches[0][$i][0];
            $pos = (int)$matches[0][$i][1];

            $literal = substr($path, $offset, $pos - $offset);
            if ($literal !== '') {
                $regex .= preg_quote($literal, '#');
            }

            $params[] = $name;
            $regex .= '(?P<' . $name . '>[^/]+)';

            $offset = $pos + strlen((string)$full);
        }

        $tail = substr($path, $offset);
        if ($tail !== '') {
            $regex .= preg_quote($tail, '#');
        }

        return ['#^' . $regex . '$#', $params];
    }

    /**
     * @return array{0: class-string, 1: string}|null
     */
    private function matchDynamic(string $method, string $path): ?array
    {
        foreach ($this->dynamicRoutes[$method] as $route) {
            if (!preg_match($route['regex'], $path, $m)) {
                continue;
            }
            foreach ($route['params'] as $p) {
                if (isset($m[$p])) {
                    $_GET[$p] = $m[$p];
                }
            }
            return $route['handler'];
        }
        return null;
    }

    private function normalize(string $path): string
    {
        if ($path === '') {
            return '/';
        }
        if ($path !== '/' && str_ends_with($path, '/')) {
            $path = rtrim($path, '/');
        }
        if (!str_starts_with($path, '/')) {
            $path = '/' . $path;
        }
        return $path;
    }
}
