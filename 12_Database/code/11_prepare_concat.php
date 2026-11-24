<?php

require __DIR__ . '/db.php';

$email = $_GET['email'] ?? "' OR '1'='1";

$stmt = $pdo->prepare("SELECT id, name, email FROM users WHERE email = '$email'");
$stmt->execute();

echo 'Найдено пользователей: ' . count($stmt->fetchAll(PDO::FETCH_ASSOC)) . PHP_EOL;
