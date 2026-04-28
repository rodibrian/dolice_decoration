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
}
