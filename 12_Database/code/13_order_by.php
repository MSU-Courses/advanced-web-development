<?php

require __DIR__ . '/db.php';

$_GET['sort'] = $_GET['sort'] ?? 'balance'; // как при адресе ?sort=balance

$sort = match ($_GET['sort'] ?? 'id') {
    'name' => 'name',
    'balance' => 'balance',
    default => 'id',
};

$stmt = $pdo->query("SELECT name, balance FROM users ORDER BY $sort");

foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $user) {
    echo "{$user['name']}: {$user['balance']}" . PHP_EOL;
}
