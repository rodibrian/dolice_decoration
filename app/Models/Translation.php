<?php
declare(strict_types=1);

namespace App\Models;

use App\Core\DB;
use App\Core\Locale;
use PDO;
use PDOException;

final class Translation
{
    private static ?bool $tableOk = null;

    public static function tableReady(): bool
    {
        if (self::$tableOk !== null) {
            return self::$tableOk;
        }
        try {
            DB::pdo()->query('SELECT 1 FROM translations LIMIT 1');
            self::$tableOk = true;
        } catch (PDOException $e) {
            self::$tableOk = false;
        }

        return self::$tableOk;
    }

    /**
     * @return array<string, string>
     */
    public static function mapFor(string $entityType, int $entityId, string $locale): array
    {
        if (!self::tableReady() || $locale === Locale::DEFAULT) {
            return [];
        }
        $stmt = DB::pdo()->prepare(
            'SELECT field, value FROM translations WHERE entity_type = :t AND entity_id = :id AND locale = :l'
        );
        $stmt->execute(['t' => $entityType, 'id' => $entityId, 'l' => $locale]);
        $out = [];
        foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
            $f = (string)($row['field'] ?? '');
            if ($f === '') {
                continue;
            }
            $out[$f] = (string)($row['value'] ?? '');
        }

        return $out;
    }

    /**
     * @param list<string> $fields champs à fusionner (ex: title, description)
     * @param array<string, mixed> $row
     * @return array<string, mixed>
     */
    public static function mergeRow(string $entityType, int $entityId, array $row, array $fields): array
    {
        $loc = Locale::current();
        if ($loc === Locale::DEFAULT || !self::tableReady()) {
            return $row;
        }
        $map = self::mapFor($entityType, $entityId, $loc);
        foreach ($fields as $f) {
            if (isset($map[$f]) && trim($map[$f]) !== '') {
                $row[$f] = $map[$f];
            }
        }

        return $row;
    }

    /**
     * @param array<string, string|null> $values field => value (null ou '' = suppression)
     */
    public static function saveLocale(string $entityType, int $entityId, string $locale, array $values): void
    {
        if (!self::tableReady() || $locale === Locale::DEFAULT) {
            return;
        }
        $pdo = DB::pdo();
        $del = $pdo->prepare(
            'DELETE FROM translations WHERE entity_type = :t AND entity_id = :id AND locale = :l AND field = :f'
        );
        $ins = $pdo->prepare(
            'INSERT INTO translations (entity_type, entity_id, locale, field, value)
             VALUES (:t, :id, :l, :f, :v)
             ON DUPLICATE KEY UPDATE value = VALUES(value)'
        );
        foreach ($values as $field => $value) {
            $field = trim((string)$field);
            if ($field === '') {
                continue;
            }
            $v = $value === null ? '' : trim((string)$value);
            if ($v === '') {
                $del->execute(['t' => $entityType, 'id' => $entityId, 'l' => $locale, 'f' => $field]);
            } else {
                $ins->execute(['t' => $entityType, 'id' => $entityId, 'l' => $locale, 'f' => $field, 'v' => $v]);
            }
        }
    }

    public static function deleteForEntity(string $entityType, int $entityId): void
    {
        if (!self::tableReady()) {
            return;
        }
        $stmt = DB::pdo()->prepare('DELETE FROM translations WHERE entity_type = :t AND entity_id = :id');
        $stmt->execute(['t' => $entityType, 'id' => $entityId]);
    }
}
