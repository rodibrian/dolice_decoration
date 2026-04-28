<?php
declare(strict_types=1);

/**
 * Minimal .env loader (no dependencies).
 * Put secrets in `.env` (ignored by git). Use `.env.example` as template.
 */
function env(string $key, ?string $default = null): ?string
{
    $val = $_ENV[$key] ?? $_SERVER[$key] ?? getenv($key);
    if ($val === false || $val === null || $val === '') {
        return $default;
    }
    return (string)$val;
}

function loadEnv(string $path): void
{
    if (!is_file($path)) {
        return;
    }

    $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    if ($lines === false) {
        return;
    }

    foreach ($lines as $line) {
        $line = trim($line);
        if ($line === '' || str_starts_with($line, '#')) {
            continue;
        }

        $pos = strpos($line, '=');
        if ($pos === false) {
            continue;
        }

        $k = trim(substr($line, 0, $pos));
        $v = trim(substr($line, $pos + 1));

        if ($k === '') {
            continue;
        }

        if ((str_starts_with($v, '"') && str_ends_with($v, '"')) || (str_starts_with($v, "'") && str_ends_with($v, "'"))) {
            $v = substr($v, 1, -1);
        }

        $_ENV[$k] = $v;
        $_SERVER[$k] = $v;
        putenv($k . '=' . $v);
    }
}

loadEnv(BASE_PATH . '/.env');
