<?php
declare(strict_types=1);

namespace App\Models;

use App\Core\DB;

final class Service
{
    /**
     * @return list<array<string, mixed>>
     */
    public static function all(): array
    {
        $pdo = DB::pdo();
        $stmt = $pdo->query('SELECT * FROM services ORDER BY display_order ASC, id DESC');
        /** @var list<array<string, mixed>> $rows */
        $rows = $stmt->fetchAll();
        return $rows;
    }

    /**
     * @return list<array<string, mixed>>
     */
    public static function published(int $limit = 0): array
    {
        $pdo = DB::pdo();
        $sql = 'SELECT * FROM services WHERE is_published = 1 ORDER BY display_order ASC, id DESC';
        if ($limit > 0) {
            $sql .= ' LIMIT ' . (int)$limit;
        }
        $stmt = $pdo->query($sql);
        /** @var list<array<string, mixed>> $rows */
        $rows = $stmt->fetchAll();
        return $rows;
    }

    /**
     * @return array<string, mixed>|null
     */
    public static function findBySlug(string $slug): ?array
    {
        $pdo = DB::pdo();
        $stmt = $pdo->prepare('SELECT * FROM services WHERE slug = :slug LIMIT 1');
        $stmt->execute(['slug' => $slug]);
        $row = $stmt->fetch();
        return $row !== false ? $row : null;
    }

    /**
     * @return array<string, mixed>|null
     */
    public static function find(int $id): ?array
    {
        $pdo = DB::pdo();
        $stmt = $pdo->prepare('SELECT * FROM services WHERE id = :id LIMIT 1');
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch();
        return $row !== false ? $row : null;
    }

    public static function create(array $data): int
    {
        $pdo = DB::pdo();
        $stmt = $pdo->prepare(
            'INSERT INTO services (title, slug, category, description, image_path, display_order, is_published)
             VALUES (:title, :slug, :category, :description, :image_path, :display_order, :is_published)'
        );
        $stmt->execute([
            'title' => $data['title'],
            'slug' => $data['slug'],
            'category' => $data['category'],
            'description' => $data['description'],
            'image_path' => $data['image_path'],
            'display_order' => $data['display_order'],
            'is_published' => $data['is_published'],
        ]);
        return (int)$pdo->lastInsertId();
    }

    public static function update(int $id, array $data): void
    {
        $pdo = DB::pdo();
        $stmt = $pdo->prepare(
            'UPDATE services
             SET title = :title,
                 slug = :slug,
                 category = :category,
                 description = :description,
                 image_path = :image_path,
                 display_order = :display_order,
                 is_published = :is_published
             WHERE id = :id'
        );
        $stmt->execute([
            'id' => $id,
            'title' => $data['title'],
            'slug' => $data['slug'],
            'category' => $data['category'],
            'description' => $data['description'],
            'image_path' => $data['image_path'],
            'display_order' => $data['display_order'],
            'is_published' => $data['is_published'],
        ]);
    }

    public static function delete(int $id): void
    {
        $pdo = DB::pdo();
        $stmt = $pdo->prepare('DELETE FROM services WHERE id = :id');
        $stmt->execute(['id' => $id]);
    }
}

