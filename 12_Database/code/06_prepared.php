<?php

require __DIR__ . '/db.php';

$email = $_GET['email'] ?? 'maria@gmail.com';

$stmt = $pdo->prepare('SELECT id, name FROM users WHERE email = ?');
$stmt->execute([$email]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if ($user) {
    echo "Найден пользователь: {$user['name']}" . PHP_EOL;
} else {
    echo 'Пользователь не найден' . PHP_EOL;
}

$stmt = $pdo->prepare('SELECT id, name FROM users WHERE email = :email');
$stmt->execute(['email' => $email]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);
echo "Именованный плейсхолдер: {$user['name']}" . PHP_EOL;
