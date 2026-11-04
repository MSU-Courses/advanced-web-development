<?php

declare(strict_types=1);

spl_autoload_register(function (string $class): void {
    $file = __DIR__ . '/../src/' . $class . '.php';

    if (file_exists($file)) {
        require $file;
    }
});

$cart = new Cart();
$cart->add(new Product('Мышь Logitech M185', 299), 2);
$cart->add(new Product('Коврик для мыши', 89));
$cart->add(new Product('Подарочная упаковка', 49));

$order = new Order($cart, new CourierDelivery(), new CashPayment());

echo 'Товары: ' . $order->goodsTotal() . " лей\n";
echo 'Доставка: ' . $order->deliveryCost() . " лей\n";
echo 'Оплата: ' . $order->paymentFee() . " лей\n";
echo 'Итого: ' . $order->total() . " лей\n";
