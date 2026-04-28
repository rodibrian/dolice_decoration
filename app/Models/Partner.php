<?php
declare(strict_types=1);

namespace App\Models;

use App\Core\DB;

final class Partner
{
    /**
     * @return list<array<string, mixed>>
     */
    public static function all(): array
    {
        $pdo = DB::pdo();
        $stmt = $pdo->query('SELECT * FROM partners ORDER BY display_order ASC, id DESC');
        /** @var list<array<string, mixed>> $rows */
        $rows = $stmt->fetchAll();
        return $rows;
    }

    /**
     * @return array<string, mixed>|null
     */
    public static function find(int $id): ?array
    {
        $pdo = DB::pdo();
        $stmt = $pdo->prepare('SELECT * FROM partners WHERE id = :id LIMIT 1');
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch();
        return $row !== false ? $row : null;
    }

    public static function create(array $data): int
    {
        $pdo = DB::pdo();
        $stmt = $pdo->prepare(
            'INSERT INTO partners (name, logo_path, url, category, display_order, is_published)
             VALUES (:name, :logo_path, :url, :category, :display_order, :is_published)'
        );
        $stmt->execute([
            'name' => $data['name'],
            'logo_path' => $data['logo_path'],
            'url' => $data['url'],
            'category' => $data['category'],
            'display_order' => $data['display_order'],
            'is_published' => $data['is_published'],
        ]);
        return (int)$pdo->lastInsertId();
    }

    public static function update(int $id, array $data): void
    {
        $pdo = DB::pdo();
        $stmt = $pdo->prepare(
            'UPDATE partners
             SET name = :name,
                 logo_path = :logo_path,
                 url = :url,
                 category = :category,
                 display_order = :display_order,
                 is_published = :is_published
             WHERE id = :id'
        );
        $stmt->execute([
            'id' => $id,
            'name' => $data['name'],
            'logo_path' => $data['logo_path'],
            'url' => $data['url'],
            'category' => $data['category'],
            'display_order' => $data['display_order'],
            'is_published' => $data['is_published'],
        ]);
    }

    public static function delete(int $id): void
    {
        $pdo = DB::pdo();
        $stmt = $pdo->prepare('DELETE FROM partners WHERE id = :id');
        $stmt->execute(['id' => $id]);
    }
}

