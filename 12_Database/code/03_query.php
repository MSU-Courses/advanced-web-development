<?php

require __DIR__ . '/db.php';

$stmt = $pdo->query('SELECT id, name, email FROM users');
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);

foreach ($users as $user) {
    echo "{$user['id']}. {$user['name']} ({$user['email']})" . PHP_EOL;
}
