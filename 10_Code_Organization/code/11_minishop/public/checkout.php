<?php

declare(strict_types=1);

require_once __DIR__ . '/../autoload.php';
require_once __DIR__ . '/../src/functions.php';

use MiniShop\Order;

$deliveryOptions = [
    'courier' => 'Курьер',
    'post' => 'Почта Молдовы',
    'pickup' => 'Самовывоз',
    'express' => 'Экспресс-доставка',
];
$paymentOptions = [
    'cash' => 'Наличными при получении',
    'card' => 'Картой',
];

$deliveryCode = $_GET['delivery'] ?? 'courier';
$paymentCode = $_GET['payment'] ?? 'cash';
$error = '';
$order = null;

try {
    if (!is_string($deliveryCode) || !is_string($paymentCode)) {
        throw new InvalidArgumentException('Некорректные параметры формы');
    }

    $order = new Order(makeCart(), makeDelivery($deliveryCode), makePayment($paymentCode));
} catch (InvalidArgumentException $exception) {
    http_response_code(400);
    $error = $exception->getMessage();
}

require __DIR__ . '/../templates/checkout.php';
