<?php

require __DIR__ . '/db.php';

$pdo->exec(file_get_contents(__DIR__ . '/schema.sql'));

echo 'Таблица users пересоздана' . PHP_EOL;
