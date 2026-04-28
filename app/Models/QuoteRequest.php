<?php
declare(strict_types=1);

namespace App\Models;

use App\Core\DB;

final class QuoteRequest
{
    /**
     * @return list<array<string, mixed>>
     */
    public static function all(): array
    {
        $pdo = DB::pdo();
        $stmt = $pdo->query('SELECT * FROM quote_requests ORDER BY created_at DESC, id DESC');
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
        $stmt = $pdo->prepare('SELECT * FROM quote_requests WHERE id = :id LIMIT 1');
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch();
        return $row !== false ? $row : null;
    }

    public static function create(array $data): int
    {
        $pdo = DB::pdo();
        $stmt = $pdo->prepare(
            'INSERT INTO quote_requests (name, phone, email, project_type, message, status, internal_notes)
             VALUES (:name, :phone, :email, :project_type, :message, :status, :internal_notes)'
        );
        $stmt->execute([
            'name' => $data['name'],
            'phone' => $data['phone'],
            'email' => $data['email'],
            'project_type' => $data['project_type'],
            'message' => $data['message'],
            'status' => $data['status'],
            'internal_notes' => $data['internal_notes'],
        ]);
        return (int)$pdo->lastInsertId();
    }

    public static function updateStatus(int $id, string $status): void
    {
        $pdo = DB::pdo();
        $stmt = $pdo->prepare('UPDATE quote_requests SET status = :status WHERE id = :id');
        $stmt->execute(['id' => $id, 'status' => $status]);
    }

    public static function updateNotes(int $id, ?string $notes): void
    {
        $pdo = DB::pdo();
        $stmt = $pdo->prepare('UPDATE quote_requests SET internal_notes = :notes WHERE id = :id');
        $stmt->execute(['id' => $id, 'notes' => $notes]);
    }
}

