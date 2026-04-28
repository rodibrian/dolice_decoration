<?php
declare(strict_types=1);

namespace App\Models;

use App\Core\DB;

final class Testimonial
{
    /**
     * @return list<array<string, mixed>>
     */
    public static function all(): array
    {
        $pdo = DB::pdo();
        $stmt = $pdo->query("SELECT * FROM testimonials ORDER BY FIELD(status,'pending','approved') ASC, id DESC");
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
        $stmt = $pdo->prepare('SELECT * FROM testimonials WHERE id = :id LIMIT 1');
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch();
        return $row !== false ? $row : null;
    }

    public static function create(array $data): int
    {
        $pdo = DB::pdo();
        $stmt = $pdo->prepare(
            'INSERT INTO testimonials (client_name, client_company, content, rating, logo_path, status)
             VALUES (:client_name, :client_company, :content, :rating, :logo_path, :status)'
        );
        $stmt->execute([
            'client_name' => $data['client_name'],
            'client_company' => $data['client_company'],
            'content' => $data['content'],
            'rating' => $data['rating'],
            'logo_path' => $data['logo_path'],
            'status' => $data['status'],
        ]);
        return (int)$pdo->lastInsertId();
    }

    public static function update(int $id, array $data): void
    {
        $pdo = DB::pdo();
        $stmt = $pdo->prepare(
            'UPDATE testimonials
             SET client_name = :client_name,
                 client_company = :client_company,
                 content = :content,
                 rating = :rating,
                 logo_path = :logo_path,
                 status = :status
             WHERE id = :id'
        );
        $stmt->execute([
            'id' => $id,
            'client_name' => $data['client_name'],
            'client_company' => $data['client_company'],
            'content' => $data['content'],
            'rating' => $data['rating'],
            'logo_path' => $data['logo_path'],
            'status' => $data['status'],
        ]);
    }

    public static function setStatus(int $id, string $status): void
    {
        $pdo = DB::pdo();
        $stmt = $pdo->prepare('UPDATE testimonials SET status = :status WHERE id = :id');
        $stmt->execute(['id' => $id, 'status' => $status]);
    }

    public static function delete(int $id): void
    {
        $pdo = DB::pdo();
        $stmt = $pdo->prepare('DELETE FROM testimonials WHERE id = :id');
        $stmt->execute(['id' => $id]);
    }
}

