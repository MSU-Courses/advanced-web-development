<?php

require __DIR__ . '/db.php';

$newUsers = [
    ['name' => 'Elena Ceban', 'email' => 'elena@gmail.com'],
    ['name' => 'Victor Lungu', 'email' => 'victor@gmail.com'],
];

$stmt = $pdo->prepare('INSERT INTO users (name, email) VALUES (:name, :email)');

foreach ($newUsers as $newUser) {
    $stmt->execute($newUser);
    echo "Добавлен {$newUser['name']}, id = " . $pdo->lastInsertId() . PHP_EOL;
}
