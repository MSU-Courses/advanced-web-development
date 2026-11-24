<?php

require __DIR__ . '/db.php';

$email = $_GET['email'] ?? "' OR '1'='1";

$sql = "SELECT id, name, email FROM users WHERE email = '$email'";
echo $sql . PHP_EOL;

$users = $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
echo 'Найдено пользователей: ' . count($users) . PHP_EOL;
