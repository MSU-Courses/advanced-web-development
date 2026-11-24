<?php

require __DIR__ . '/db.php';

$stmt = $pdo->query('SELECT name, balance FROM users ORDER BY balance DESC');

$first = $stmt->fetch(PDO::FETCH_ASSOC);
echo "Больше всего баллов: {$first['name']}" . PHP_EOL;

while ($user = $stmt->fetch(PDO::FETCH_ASSOC)) {
    echo "{$user['name']}: {$user['balance']}" . PHP_EOL;
}
