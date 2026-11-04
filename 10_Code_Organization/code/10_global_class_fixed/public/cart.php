<?php

declare(strict_types=1);

require_once __DIR__ . '/../src/Cart/CartItem.php';
require_once __DIR__ . '/../src/Cart/Product.php';
require_once __DIR__ . '/../src/Cart/Cart.php';

use MiniShop\Cart\Cart;
use MiniShop\Cart\Product;

$cart = new Cart();

try {
    $cart->add(new Product('Коврик для мыши', 89), 0);
} catch (InvalidArgumentException $exception) {
    echo 'Ошибка: ' . $exception->getMessage() . "\n";
}
