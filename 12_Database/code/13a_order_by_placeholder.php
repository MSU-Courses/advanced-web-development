<?php

require __DIR__ . '/db.php';

$stmt = $pdo->prepare('SELECT name, balance FROM users ORDER BY ?');
$stmt->execute(['balance']);

foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $user) {
    echo "{$user['name']}: {$user['balance']}" . PHP_EOL;
}
