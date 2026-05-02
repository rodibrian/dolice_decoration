<?php
declare(strict_types=1);

namespace App\Models;

use App\Core\DB;

final class Post
{
    /**
     * @return list<array<string, mixed>>
     */
    public static function all(): array
    {
        $pdo = DB::pdo();
        $stmt = $pdo->query('SELECT * FROM posts ORDER BY COALESCE(published_at, created_at) DESC, id DESC');
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
        $sql = "SELECT * FROM posts WHERE status = 'published' ORDER BY published_at DESC, id DESC";
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
        $stmt = $pdo->prepare('SELECT * FROM posts WHERE slug = :slug LIMIT 1');
        $stmt->execute(['slug' => $slug]);
        $row = $stmt->fetch();
        return $row !== false ? $row : null;
    }

    /** Slug URL (minuscules, tirets), adapté au français. */
    public static function slugify(string $text): string
    {
        $text = trim($text);
        if ($text === '') {
            return 'article';
        }
        $lower = mb_strtolower($text, 'UTF-8');
        $ascii = @iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $lower);
        if ($ascii !== false && $ascii !== '') {
            $lower = $ascii;
        }
        $lower = (string)(preg_replace('/[^a-z0-9]+/i', '-', $lower) ?? '');
        $lower = strtolower(trim($lower, '-'));
        if ($lower === '') {
            return 'article';
        }
        if (strlen($lower) > 180) {
            $lower = rtrim(substr($lower, 0, 180), '-');
        }

        return $lower !== '' ? $lower : 'article';
    }

    public static function slugTaken(string $slug, ?int $exceptPostId = null): bool
    {
        $pdo = DB::pdo();
        if ($exceptPostId !== null) {
            $stmt = $pdo->prepare('SELECT id FROM posts WHERE slug = :slug AND id <> :id LIMIT 1');
            $stmt->execute(['slug' => $slug, 'id' => $exceptPostId]);
        } else {
            $stmt = $pdo->prepare('SELECT id FROM posts WHERE slug = :slug LIMIT 1');
            $stmt->execute(['slug' => $slug]);
        }

        return $stmt->fetch() !== false;
    }

    /** Retourne un slug libre, en ajoutant -2, -3, … si besoin. */
    public static function ensureUniqueSlug(string $baseSlug, ?int $exceptPostId = null): string
    {
        $base = self::slugify($baseSlug);
        $candidate = $base;
        $n = 2;
        $guard = 0;
        while (self::slugTaken($candidate, $exceptPostId)) {
            $guard++;
            if ($guard > 1000) {
                return self::slugify($base . '-' . bin2hex(random_bytes(4)));
            }
            $suffix = '-' . $n;
            $maxLen = 190 - strlen($suffix);
            $trunc = $maxLen > 1 ? substr($base, 0, $maxLen) : 'a';
            $candidate = rtrim($trunc, '-') . $suffix;
            $n++;
        }

        return $candidate;
    }

    /**
     * @return array<string, mixed>|null
     */
    public static function find(int $id): ?array
    {
        $pdo = DB::pdo();
        $stmt = $pdo->prepare('SELECT * FROM posts WHERE id = :id LIMIT 1');
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch();
        return $row !== false ? $row : null;
    }

    public static function create(array $data): int
    {
        $pdo = DB::pdo();
        $stmt = $pdo->prepare(
            'INSERT INTO posts (title, slug, excerpt, content, featured_image, author, keywords, status, published_at)
             VALUES (:title, :slug, :excerpt, :content, :featured_image, :author, :keywords, :status, :published_at)'
        );
        $stmt->execute([
            'title' => $data['title'],
            'slug' => $data['slug'],
            'excerpt' => $data['excerpt'],
            'content' => $data['content'],
            'featured_image' => $data['featured_image'],
            'author' => $data['author'],
            'keywords' => $data['keywords'],
            'status' => $data['status'],
            'published_at' => $data['published_at'],
        ]);
        return (int)$pdo->lastInsertId();
    }

    public static function update(int $id, array $data): void
    {
        $pdo = DB::pdo();
        $stmt = $pdo->prepare(
            'UPDATE posts
             SET title = :title,
                 slug = :slug,
                 excerpt = :excerpt,
                 content = :content,
                 featured_image = :featured_image,
                 author = :author,
                 keywords = :keywords,
                 status = :status,
                 published_at = :published_at
             WHERE id = :id'
        );
        $stmt->execute([
            'id' => $id,
            'title' => $data['title'],
            'slug' => $data['slug'],
            'excerpt' => $data['excerpt'],
            'content' => $data['content'],
            'featured_image' => $data['featured_image'],
            'author' => $data['author'],
            'keywords' => $data['keywords'],
            'status' => $data['status'],
            'published_at' => $data['published_at'],
        ]);
    }

    public static function delete(int $id): void
    {
        $pdo = DB::pdo();
        $stmt = $pdo->prepare('DELETE FROM posts WHERE id = :id');
        $stmt->execute(['id' => $id]);
    }
}

