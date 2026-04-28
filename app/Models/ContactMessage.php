<?php
declare(strict_types=1);

namespace App\Models;

use App\Core\DB;

final class ContactMessage
{
    /**
     * @return list<array<string, mixed>>
     */
    public static function all(): array
    {
        $pdo = DB::pdo();
        $stmt = $pdo->query('SELECT * FROM contact_messages ORDER BY created_at DESC, id DESC');
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
        $stmt = $pdo->prepare('SELECT * FROM contact_messages WHERE id = :id LIMIT 1');
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch();
        return $row !== false ? $row : null;
    }

    public static function create(array $data): int
    {
        $pdo = DB::pdo();
        $stmt = $pdo->prepare(
            'INSERT INTO contact_messages (name, email, phone, subject, message, status)
             VALUES (:name, :email, :phone, :subject, :message, :status)'
        );
        $stmt->execute([
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'],
            'subject' => $data['subject'],
            'message' => $data['message'],
            'status' => $data['status'],
        ]);
        return (int)$pdo->lastInsertId();
    }

    public static function updateStatus(int $id, string $status): void
    {
        $pdo = DB::pdo();
        $stmt = $pdo->prepare('UPDATE contact_messages SET status = :status WHERE id = :id');
        $stmt->execute(['id' => $id, 'status' => $status]);
    }
}

