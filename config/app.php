<?php
declare(strict_types=1);

return [
    'env' => env('APP_ENV', 'local'),
    'debug' => env('APP_DEBUG', '1') === '1',
    'url' => rtrim((string)env('APP_URL', ''), '/'),
    'session_name' => env('SESSION_NAME', 'dolice_sess'),
];

