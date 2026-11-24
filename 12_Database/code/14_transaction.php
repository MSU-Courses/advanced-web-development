<?php

require __DIR__ . '/db.php';

function transferBonus(PDO $pdo, int $fromId, int $toId, int $amount): void
{
    $pdo->beginTransaction();

    try {
        $stmt = $pdo->prepare('UPDATE users SET balance = balance - ? WHERE id = ? AND balance >= ?');
        $stmt->execute([$amount, $fromId, $amount]);
        if ($stmt->rowCount() === 0) {
            throw new RuntimeException('Недостаточно баллов');
        }

        $stmt = $pdo->prepare('UPDATE users SET balance = balance + ? WHERE id = ?');
        $stmt->execute([$amount, $toId]);
        if ($stmt->rowCount() === 0) {
            throw new RuntimeException('Получатель не найден');
        }

        $pdo->commit();
    } catch (Throwable $e) {
        $pdo->rollBack();
        throw $e;
    }
}

transferBonus($pdo, 1, 2, 30);   // выполнен

try {
    transferBonus($pdo, 1, 99, 20);
} catch (RuntimeException $e) {
    echo 'Перевод отменён: ' . $e->getMessage() . PHP_EOL;
}

$stmt = $pdo->query('SELECT id, balance FROM users WHERE id IN (1, 2)');
foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $user) {
    echo "id {$user['id']}: {$user['balance']}" . PHP_EOL;
}
