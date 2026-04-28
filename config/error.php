<?php
declare(strict_types=1);

$debug = env('APP_DEBUG', '0') === '1';

ini_set('display_errors', $debug ? '1' : '0');
ini_set('display_startup_errors', $debug ? '1' : '0');
error_reporting($debug ? E_ALL : E_ALL & ~E_DEPRECATED & ~E_STRICT);

set_exception_handler(static function (Throwable $e) use ($debug): void {
    http_response_code(500);
    if ($debug) {
        echo "<pre>";
        echo htmlspecialchars($e->__toString(), ENT_QUOTES, 'UTF-8');
        echo "</pre>";
        return;
    }

    echo "Une erreur est survenue.";
});
