<?php
declare(strict_types=1);

$sessionName = env('SESSION_NAME', 'dolice_sess');

session_name($sessionName);
session_set_cookie_params([
    'lifetime' => 0,
    'path' => '/',
    'httponly' => true,
    'samesite' => 'Lax',
    'secure' => (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off'),
]);

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}
