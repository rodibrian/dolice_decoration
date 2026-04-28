<?php
declare(strict_types=1);

namespace App\Models;

use App\Core\DB;

final class Page
{
    /**
     * @return list<array<string, mixed>>
     */
    public static function all(): array
    {
        $pdo = DB::pdo();
        $stmt = $pdo->query('SELECT * FROM pages ORDER BY page_key ASC');
        /** @var list<array<string, mixed>> $rows */
        $rows = $stmt->fetchAll();
        return $rows;
    }

    /**
     * @return array<string, mixed>|null
     */
    public static function findByKey(string $key): ?array
    {
        $pdo = DB::pdo();
        $stmt = $pdo->prepare('SELECT * FROM pages WHERE page_key = :k LIMIT 1');
        $stmt->execute(['k' => $key]);
        $row = $stmt->fetch();
        return $row !== false ? $row : null;
    }

    public static function upsert(string $key, string $title, ?string $content): void
    {
        $pdo = DB::pdo();
        $stmt = $pdo->prepare(
            'INSERT INTO pages (page_key, title, content)
             VALUES (:k, :t, :c)
             ON DUPLICATE KEY UPDATE title = VALUES(title), content = VALUES(content)'
        );
        $stmt->execute(['k' => $key, 't' => $title, 'c' => $content]);
    }
}

