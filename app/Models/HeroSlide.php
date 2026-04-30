<?php
declare(strict_types=1);

namespace App\Models;

use App\Core\DB;
use PDOException;

final class HeroSlide
{
    /**
     * @return list<array<string, mixed>>
     */
    public static function all(): array
    {
        try {
            $pdo = DB::pdo();
            $stmt = $pdo->query('SELECT * FROM hero_slides ORDER BY display_order ASC, id DESC');
            /** @var list<array<string, mixed>> $rows */
            $rows = $stmt->fetchAll();
            return $rows;
        } catch (PDOException $e) {
            // Table might not be installed yet in some environments.
            return [];
        }
    }

    /**
     * @return list<array<string, mixed>>
     */
    public static function published(int $limit = 0): array
    {
        try {
            $pdo = DB::pdo();
            $sql = 'SELECT * FROM hero_slides WHERE is_published = 1 ORDER BY display_order ASC, id DESC';
            if ($limit > 0) {
                $sql .= ' LIMIT ' . (int)$limit;
            }
            $stmt = $pdo->query($sql);
            /** @var list<array<string, mixed>> $rows */
            $rows = $stmt->fetchAll();
            return $rows;
        } catch (PDOException $e) {
            return [];
        }
    }

    /**
     * @return array<string, mixed>|null
     */
    public static function find(int $id): ?array
    {
        try {
            $pdo = DB::pdo();
            $stmt = $pdo->prepare('SELECT * FROM hero_slides WHERE id = :id LIMIT 1');
            $stmt->execute(['id' => $id]);
            $row = $stmt->fetch();
            return $row !== false ? $row : null;
        } catch (PDOException $e) {
            return null;
        }
    }

    public static function create(array $data): int
    {
        $pdo = DB::pdo();
        $stmt = $pdo->prepare(
            'INSERT INTO hero_slides (title, subtitle, media_type, media_path, cta_label, cta_url, display_order, is_published)
             VALUES (:title, :subtitle, :media_type, :media_path, :cta_label, :cta_url, :display_order, :is_published)'
        );
        $stmt->execute([
            'title' => $data['title'],
            'subtitle' => $data['subtitle'],
            'media_type' => $data['media_type'],
            'media_path' => $data['media_path'],
            'cta_label' => $data['cta_label'],
            'cta_url' => $data['cta_url'],
            'display_order' => $data['display_order'],
            'is_published' => $data['is_published'],
        ]);
        return (int)$pdo->lastInsertId();
    }

    public static function update(int $id, array $data): void
    {
        $pdo = DB::pdo();
        $stmt = $pdo->prepare(
            'UPDATE hero_slides
             SET title = :title,
                 subtitle = :subtitle,
                 media_type = :media_type,
                 media_path = :media_path,
                 cta_label = :cta_label,
                 cta_url = :cta_url,
                 display_order = :display_order,
                 is_published = :is_published
             WHERE id = :id'
        );
        $stmt->execute([
            'id' => $id,
            'title' => $data['title'],
            'subtitle' => $data['subtitle'],
            'media_type' => $data['media_type'],
            'media_path' => $data['media_path'],
            'cta_label' => $data['cta_label'],
            'cta_url' => $data['cta_url'],
            'display_order' => $data['display_order'],
            'is_published' => $data['is_published'],
        ]);
    }

    public static function delete(int $id): void
    {
        $pdo = DB::pdo();
        $stmt = $pdo->prepare('DELETE FROM hero_slides WHERE id = :id');
        $stmt->execute(['id' => $id]);
    }
}

