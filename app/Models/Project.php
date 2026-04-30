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

    /**
     * @param list<int> $projectIds
     * @return array<int, string>
     */
    public static function firstImagesByProjectIds(array $projectIds): array
    {
        if ($projectIds === []) {
            return [];
        }

        $ids = array_values(array_unique(array_map(static fn ($id): int => (int)$id, $projectIds)));
        if ($ids === []) {
            return [];
        }

        $placeholders = implode(',', array_fill(0, count($ids), '?'));
        $pdo = DB::pdo();
        $stmt = $pdo->prepare(
            "SELECT pi.project_id, pi.image_path
             FROM project_images pi
             INNER JOIN (
                 SELECT project_id, MIN(id) AS min_id
                 FROM project_images
                 WHERE project_id IN ($placeholders)
                 GROUP BY project_id
             ) first_img ON first_img.min_id = pi.id"
        );
        $stmt->execute($ids);
        /** @var list<array{project_id:mixed,image_path:mixed}> $rows */
        $rows = $stmt->fetchAll();

        $map = [];
        foreach ($rows as $row) {
            $map[(int)$row['project_id']] = (string)$row['image_path'];
        }
        return $map;
    }

    /**
     * @param list<int> $projectIds
     * @return array<int, list<string>> project_id => list of image paths (relative or absolute)
     */
    public static function imagesByProjectIds(array $projectIds, int $limitPerProject = 5): array
    {
        $limitPerProject = max(1, min(12, $limitPerProject));
        if ($projectIds === []) {
            return [];
        }

        $ids = array_values(array_unique(array_map(static fn ($id): int => (int)$id, $projectIds)));
        if ($ids === []) {
            return [];
        }

        $placeholders = implode(',', array_fill(0, count($ids), '?'));
        $pdo = DB::pdo();
        $stmt = $pdo->prepare(
            "SELECT project_id, image_path
             FROM project_images
             WHERE project_id IN ($placeholders)
             ORDER BY project_id ASC, sort_order ASC, id ASC"
        );
        $stmt->execute($ids);
        /** @var list<array{project_id:mixed,image_path:mixed}> $rows */
        $rows = $stmt->fetchAll();

        $out = [];
        foreach ($rows as $row) {
            $pid = (int)$row['project_id'];
            $path = trim((string)$row['image_path']);
            if ($pid <= 0 || $path === '') {
                continue;
            }
            if (!isset($out[$pid])) {
                $out[$pid] = [];
            }
            if (count($out[$pid]) >= $limitPerProject) {
                continue;
            }
            $out[$pid][] = $path;
        }
        return $out;
    }
}

