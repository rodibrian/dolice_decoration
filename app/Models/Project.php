<?php
declare(strict_types=1);

namespace App\Models;

use App\Core\DB;

final class Project
{
    /**
     * @return list<array<string, mixed>>
     */
    public static function all(): array
    {
        $pdo = DB::pdo();
        $stmt = $pdo->query('SELECT * FROM projects ORDER BY is_featured DESC, id DESC');
        /** @var list<array<string, mixed>> $rows */
        $rows = $stmt->fetchAll();
        return $rows;
    }

    /**
     * @return list<array<string, mixed>>
     */
    public static function published(int $limit = 0, ?string $category = null): array
    {
        $pdo = DB::pdo();
        $params = [];
        $sql = "SELECT * FROM projects WHERE status = 'published'";
        if ($category !== null && $category !== '') {
            $sql .= ' AND category = :category';
            $params['category'] = $category;
        }
        $sql .= ' ORDER BY is_featured DESC, id DESC';
        if ($limit > 0) {
            $sql .= ' LIMIT ' . (int)$limit;
        }

        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
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
        $stmt = $pdo->prepare('SELECT * FROM projects WHERE slug = :slug LIMIT 1');
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
        $stmt = $pdo->prepare('SELECT * FROM projects WHERE id = :id LIMIT 1');
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch();
        return $row !== false ? $row : null;
    }

    public static function create(array $data): int
    {
        $pdo = DB::pdo();
        $stmt = $pdo->prepare(
            'INSERT INTO projects (title, slug, category, work_type, location, project_date, description, is_featured, status)
             VALUES (:title, :slug, :category, :work_type, :location, :project_date, :description, :is_featured, :status)'
        );
        $stmt->execute([
            'title' => $data['title'],
            'slug' => $data['slug'],
            'category' => $data['category'],
            'work_type' => $data['work_type'],
            'location' => $data['location'],
            'project_date' => $data['project_date'],
            'description' => $data['description'],
            'is_featured' => $data['is_featured'],
            'status' => $data['status'],
        ]);
        return (int)$pdo->lastInsertId();
    }

    public static function update(int $id, array $data): void
    {
        $pdo = DB::pdo();
        $stmt = $pdo->prepare(
            'UPDATE projects
             SET title = :title,
                 slug = :slug,
                 category = :category,
                 work_type = :work_type,
                 location = :location,
                 project_date = :project_date,
                 description = :description,
                 is_featured = :is_featured,
                 status = :status
             WHERE id = :id'
        );
        $stmt->execute([
            'id' => $id,
            'title' => $data['title'],
            'slug' => $data['slug'],
            'category' => $data['category'],
            'work_type' => $data['work_type'],
            'location' => $data['location'],
            'project_date' => $data['project_date'],
            'description' => $data['description'],
            'is_featured' => $data['is_featured'],
            'status' => $data['status'],
        ]);
    }

    public static function delete(int $id): void
    {
        $pdo = DB::pdo();
        $stmt = $pdo->prepare('DELETE FROM projects WHERE id = :id');
        $stmt->execute(['id' => $id]);
    }

    /**
     * @return list<array<string, mixed>>
     */
    public static function images(int $projectId): array
    {
        $pdo = DB::pdo();
        $stmt = $pdo->prepare('SELECT * FROM project_images WHERE project_id = :id ORDER BY sort_order ASC, id ASC');
        $stmt->execute(['id' => $projectId]);
        /** @var list<array<string, mixed>> $rows */
        $rows = $stmt->fetchAll();
        return $rows;
    }

    public static function addImage(int $projectId, string $path, int $sortOrder = 0): int
    {
        $pdo = DB::pdo();
        $stmt = $pdo->prepare(
            'INSERT INTO project_images (project_id, image_path, sort_order) VALUES (:project_id, :image_path, :sort_order)'
        );
        $stmt->execute([
            'project_id' => $projectId,
            'image_path' => $path,
            'sort_order' => $sortOrder,
        ]);
        return (int)$pdo->lastInsertId();
    }

    public static function deleteImage(int $imageId): void
    {
        $pdo = DB::pdo();
        $stmt = $pdo->prepare('DELETE FROM project_images WHERE id = :id');
        $stmt->execute(['id' => $imageId]);
    }
}

