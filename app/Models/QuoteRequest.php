<?php
declare(strict_types=1);

namespace App\Models;

use App\Core\DB;
use PDO;
use PDOException;

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

    /**
     * Store selected services as quote_request_items (price snapshot).
     *
     * @param list<int> $serviceIds
     */
    public static function addServiceItems(int $quoteId, array $serviceIds): void
    {
        if ($quoteId <= 0 || empty($serviceIds)) {
            return;
        }

        $pdo = DB::pdo();

        $placeholders = implode(',', array_fill(0, count($serviceIds), '?'));
        /** @var list<array<string,mixed>> $services */
        $services = [];
        try {
            $stmt = $pdo->prepare(
                "SELECT id, title, base_price, price_unit
                 FROM services
                 WHERE id IN ({$placeholders})"
            );
            $stmt->execute($serviceIds);
            $services = $stmt->fetchAll();
        } catch (PDOException $e) {
            // Backward compatible when DB is not migrated yet (no price columns).
            $stmt = $pdo->prepare(
                "SELECT id, title
                 FROM services
                 WHERE id IN ({$placeholders})"
            );
            $stmt->execute($serviceIds);
            $services = $stmt->fetchAll();
        }
        if (empty($services)) {
            return;
        }

        $insert = $pdo->prepare(
            'INSERT INTO quote_request_items (quote_request_id, service_id, service_title, unit_price, price_unit, qty)
             VALUES (:quote_request_id, :service_id, :service_title, :unit_price, :price_unit, :qty)'
        );

        foreach ($services as $s) {
            $insert->execute([
                'quote_request_id' => $quoteId,
                'service_id' => (int)($s['id'] ?? 0),
                'service_title' => (string)($s['title'] ?? ''),
                'unit_price' => array_key_exists('base_price', $s) && $s['base_price'] !== null ? (float)$s['base_price'] : null,
                'price_unit' => array_key_exists('price_unit', $s) ? ((string)($s['price_unit'] ?? '') ?: null) : null,
                'qty' => 1,
            ]);
        }
    }

    /**
     * @return list<array<string, mixed>>
     */
    public static function items(int $quoteId): array
    {
        $pdo = DB::pdo();
        $stmt = $pdo->prepare('SELECT * FROM quote_request_items WHERE quote_request_id = :id ORDER BY id ASC');
        $stmt->execute(['id' => $quoteId]);
        /** @var list<array<string, mixed>> $rows */
        $rows = $stmt->fetchAll();
        return $rows;
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

