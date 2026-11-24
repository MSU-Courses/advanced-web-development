<?php

require __DIR__ . '/vendor/autoload.php';

use MiniShop\OrderRepository;
use MiniShop\OrderService;
use MiniShop\ProductRepository;

$config = require __DIR__ . '/config/config.php';

$dsn = sprintf('mysql:host=%s;dbname=%s;charset=%s', $config['host'], $config['database'], $config['charset']);
$pdo = new PDO($dsn, $config['user'], $config['password']);

$products = new ProductRepository($pdo);
$orderService = new OrderService($pdo, $products, new OrderRepository($pdo));

$requests = [[1, 2], [3, 1]]; // пары: id товара и количество

foreach ($requests as $request) {
    try {
        $orderId = $orderService->place($request[0], $request[1]);
        echo "Заказ №$orderId оформлен" . PHP_EOL;
    } catch (RuntimeException $e) {
        echo 'Заказ не оформлен: ' . $e->getMessage() . PHP_EOL;
    }
}

foreach ($products->all() as $product) {
    echo "{$product['name']}: {$product['price']} лей, на складе {$product['stock']}" . PHP_EOL;
}
