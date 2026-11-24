<?php

require __DIR__ . '/db.php';

$count = $pdo->exec('UPDATE users SET balance = balance + 10');

echo "Изменено строк: $count" . PHP_EOL;
