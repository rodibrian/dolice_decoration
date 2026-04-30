<?php
declare(strict_types=1);

namespace App\Models;

use App\Core\DB;

final class User
{
    /**
     * @return array{id:int, email:string, name:string, role:string, password_hash:string}|null
     */
    public static function findByEmail(string $email): ?array
    {
        $pdo = DB::pdo();
        $stmt = $pdo->prepare('SELECT id, email, name, role, password_hash FROM users WHERE email = :email LIMIT 1');
        $stmt->execute(['email' => $email]);
        $row = $stmt->fetch();
        return $row !== false ? $row : null;
    }

    /**
     * @return list<array<string, mixed>>
     */
    public static function all(): array
    {
        $pdo = DB::pdo();
        $stmt = $pdo->query('SELECT id, name, email, role, created_at FROM users ORDER BY id DESC');
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
        $stmt = $pdo->prepare('SELECT id, name, email, role, password_hash, created_at FROM users WHERE id = :id LIMIT 1');
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch();
        return $row !== false ? $row : null;
    }

    public static function create(array $data): int
    {
        $pdo = DB::pdo();
        $stmt = $pdo->prepare(
            'INSERT INTO users (name, email, role, password_hash)
             VALUES (:name, :email, :role, :password_hash)'
        );
        $stmt->execute([
            'name' => $data['name'],
            'email' => $data['email'],
            'role' => $data['role'],
            'password_hash' => $data['password_hash'],
        ]);
        return (int)$pdo->lastInsertId();
    }

    public static function update(int $id, array $data): void
    {
        $pdo = DB::pdo();
        $stmt = $pdo->prepare(
            'UPDATE users
             SET name = :name,
                 email = :email,
                 role = :role
             WHERE id = :id'
        );
        $stmt->execute([
            'id' => $id,
            'name' => $data['name'],
            'email' => $data['email'],
            'role' => $data['role'],
        ]);
    }

    public static function updatePassword(int $id, string $passwordHash): void
    {
        $pdo = DB::pdo();
        $stmt = $pdo->prepare('UPDATE users SET password_hash = :h WHERE id = :id');
        $stmt->execute(['id' => $id, 'h' => $passwordHash]);
    }

    public static function delete(int $id): void
    {
        $pdo = DB::pdo();
        $stmt = $pdo->prepare('DELETE FROM users WHERE id = :id');
        $stmt->execute(['id' => $id]);
    }
}
