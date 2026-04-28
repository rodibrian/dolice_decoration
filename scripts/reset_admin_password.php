<?php
declare(strict_types=1);

require __DIR__ . '/../config/bootstrap.php';

use App\Core\DB;

$email = 'admin@dolice.local';
$newPassword = 'Admin@1234';
$hash = password_hash($newPassword, PASSWORD_DEFAULT);

$pdo = DB::pdo();
$stmt = $pdo->prepare('UPDATE users SET password_hash = :h WHERE email = :e');
$stmt->execute(['h' => $hash, 'e' => $email]);

echo "OK: password reset for {$email}" . PHP_EOL;

