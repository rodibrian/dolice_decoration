<?php
declare(strict_types=1);

namespace App\Models;

use App\Core\DB;

final class Setting
{
    /**
     * @return array<string, string|null>
     */
    public static function allKeyed(): array
    {
        $pdo = DB::pdo();
        $stmt = $pdo->query('SELECT setting_key, setting_value FROM settings');
        $out = [];
        foreach ($stmt->fetchAll() as $row) {
            $out[(string)$row['setting_key']] = $row['setting_value'] !== null ? (string)$row['setting_value'] : null;
        }
        return $out;
    }

    public static function get(string $key, ?string $default = null): ?string
    {
        $pdo = DB::pdo();
        $stmt = $pdo->prepare('SELECT setting_value FROM settings WHERE setting_key = :k LIMIT 1');
        $stmt->execute(['k' => $key]);
        $row = $stmt->fetch();
        if ($row === false) {
            return $default;
        }
        $val = $row['setting_value'];
        return $val !== null && $val !== '' ? (string)$val : $default;
    }

    public static function set(string $key, ?string $value): void
    {
        $pdo = DB::pdo();
        $stmt = $pdo->prepare(
            'INSERT INTO settings (setting_key, setting_value)
             VALUES (:k, :v)
             ON DUPLICATE KEY UPDATE setting_value = VALUES(setting_value)'
        );
        $stmt->execute(['k' => $key, 'v' => $value]);
    }
}

