<?php
declare(strict_types=1);

namespace App\Models;

use App\Core\DB;
use PDOException;

final class AuditLog
{
    /**
     * @param array<string, mixed>|null $meta
     */
    public static function add(string $action, ?string $entity = null, ?int $entityId = null, ?array $meta = null): void
    {
        try {
            $pdo = DB::pdo();
            $user = \App\Core\Auth::user();
            $userId = is_array($user) ? (int)($user['id'] ?? 0) : null;
            $userEmail = is_array($user) ? (string)($user['email'] ?? '') : null;
            $ip = (string)($_SERVER['REMOTE_ADDR'] ?? '');
            $ua = (string)($_SERVER['HTTP_USER_AGENT'] ?? '');

            $stmt = $pdo->prepare(
                'INSERT INTO audit_logs (user_id, user_email, action, entity, entity_id, meta_json, ip, user_agent)
                 VALUES (:user_id, :user_email, :action, :entity, :entity_id, :meta_json, :ip, :user_agent)'
            );
            $stmt->execute([
                'user_id' => $userId && $userId > 0 ? $userId : null,
                'user_email' => $userEmail !== '' ? $userEmail : null,
                'action' => $action,
                'entity' => $entity,
                'entity_id' => $entityId,
                'meta_json' => $meta !== null ? json_encode($meta, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) : null,
                'ip' => $ip !== '' ? $ip : null,
                'user_agent' => $ua !== '' ? substr($ua, 0, 255) : null,
            ]);
        } catch (PDOException $e) {
            // Table might not be installed yet.
            return;
        }
    }

    /**
     * @return list<array<string, mixed>>
     */
    public static function latest(int $limit = 200): array
    {
        $limit = max(1, min(1000, $limit));
        try {
            $pdo = DB::pdo();
            $stmt = $pdo->query('SELECT * FROM audit_logs ORDER BY id DESC LIMIT ' . (int)$limit);
            /** @var list<array<string, mixed>> $rows */
            $rows = $stmt->fetchAll();
            return $rows;
        } catch (PDOException $e) {
            return [];
        }
    }
}

