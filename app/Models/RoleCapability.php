<?php
declare(strict_types=1);

namespace App\Models;

use App\Core\DB;
use PDOException;

final class RoleCapability
{
    /**
     * @return list<string>
     */
    public static function forRole(string $role): array
    {
        if ($role === '') {
            return [];
        }
        try {
            $pdo = DB::pdo();
            $stmt = $pdo->prepare('SELECT capability FROM role_capabilities WHERE role = :r');
            $stmt->execute(['r' => $role]);
            return array_map(static fn (array $row): string => (string)$row['capability'], $stmt->fetchAll());
        } catch (PDOException $e) {
            return [];
        }
    }

    /**
     * @param list<string> $capabilities
     */
    public static function setForRole(string $role, array $capabilities): void
    {
        if ($role === '') {
            return;
        }
        $capabilities = array_values(array_unique(array_filter(array_map('strval', $capabilities))));

        $pdo = DB::pdo();
        $pdo->beginTransaction();
        try {
            $del = $pdo->prepare('DELETE FROM role_capabilities WHERE role = :r');
            $del->execute(['r' => $role]);

            if (!empty($capabilities)) {
                $ins = $pdo->prepare('INSERT INTO role_capabilities (role, capability) VALUES (:r, :c)');
                foreach ($capabilities as $c) {
                    $ins->execute(['r' => $role, 'c' => $c]);
                }
            }

            $pdo->commit();
        } catch (\Throwable $e) {
            $pdo->rollBack();
            throw $e;
        }
    }
}

