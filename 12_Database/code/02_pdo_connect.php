<?php

$dsn = 'mysql:host=localhost;dbname=test_app;charset=utf8mb4';

try {
    $pdo = new PDO($dsn, 'root', '');
    echo 'Соединение установлено' . PHP_EOL;
} catch (PDOException $e) {
    die('Ошибка подключения: ' . $e->getMessage());
}
