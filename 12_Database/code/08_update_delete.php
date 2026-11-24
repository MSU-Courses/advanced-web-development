<?php

require __DIR__ . '/db.php';

$stmt = $pdo->prepare('UPDATE users SET email = ? WHERE id = ?');
$stmt->execute(['ivan.ivanov@gmail.com', 1]);
echo 'Обновлено строк: ' . $stmt->rowCount() . PHP_EOL;

$stmt = $pdo->prepare('DELETE FROM users WHERE id = ?');
$stmt->execute([99]);
echo 'Удалено строк: ' . $stmt->rowCount() . PHP_EOL;
