<?php

require __DIR__ . '/db.php';

try {
    $pdo->query('SELECT * FROM nonexistent_table');
} catch (PDOException $e) {
    error_log($e->getMessage());
    echo 'Произошла ошибка. Попробуйте позже.' . PHP_EOL;
}
