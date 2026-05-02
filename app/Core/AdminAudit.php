<?php
declare(strict_types=1);

namespace App\Core;

use App\Models\AuditLog;

/**
 * Journalisation homogène des actions admin (CRUD et réglages).
 *
 * @param array<string, mixed>|null $details null ou [] = aucun détail en base
 */
final class AdminAudit
{
    public static function log(string $action, ?string $entity = null, ?int $entityId = null, ?array $details = null): void
    {
        $payload = $details ?? [];
        AuditLog::add($action, $entity, $entityId, $payload === [] ? null : $payload);
    }
}
