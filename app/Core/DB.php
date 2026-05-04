<?php
declare(strict_types=1);

namespace App\Core;

use PDO;

final class DB
{
    private static ?PDO $pdo = null;

    public static function pdo(): PDO
    {
        if (self::$pdo instanceof PDO) {
            return self::$pdo;
        }

        $host = env('DB_HOST', '127.0.0.1');
        $name = env('DB_NAME', 'dolice_decoration');
        $user = env('DB_USER', 'root');
        $pass = env('DB_PASS', '');
        $charset = env('DB_CHARSET', 'utf8mb4');
        $port = env('DB_PORT', '');
        $portSuffix = '';
        if ($port !== null && $port !== '' && ctype_digit((string)$port)) {
            $portSuffix = ';port=' . (int)$port;
        }

        $dsn = "mysql:host={$host};dbname={$name};charset={$charset}{$portSuffix}";
        $pdo = new PDO($dsn, $user, $pass, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ]);

        self::$pdo = $pdo;
        return $pdo;
    }
}
