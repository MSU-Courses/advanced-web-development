<?php

declare(strict_types=1);

use MiniShop\Cart\Cart;
use MiniShop\Cart\Product;
use MiniShop\Delivery\CourierDelivery;
use MiniShop\Delivery\DeliveryMethod;
use MiniShop\Delivery\ExpressDelivery;
use MiniShop\Delivery\PickupDelivery;
use MiniShop\Delivery\PostDelivery;
use MiniShop\Payment\CardPayment;
use MiniShop\Payment\CashPayment;
use MiniShop\Payment\PaymentMethod;

function makeCart(): Cart
{
    $cart = new Cart();
    $cart->add(new Product('Мышь Logitech M185', 299), 2);
    $cart->add(new Product('Коврик для мыши', 89));
    $cart->add(new Product('Подарочная упаковка', 49));
    return $cart;
}

function makeDelivery(string $code): DeliveryMethod
{
    return match ($code) {
        'courier' => new CourierDelivery(),
        'post' => new PostDelivery(),
        'pickup' => new PickupDelivery(),
        'express' => new ExpressDelivery(),
        default => throw new InvalidArgumentException('Неизвестный способ доставки'),
    };
}

function makePayment(string $code): PaymentMethod
{
    return match ($code) {
        'card' => new CardPayment(),
        'cash' => new CashPayment(),
        default => throw new InvalidArgumentException('Неизвестный способ оплаты'),
    };
}

function h(string $text): string
{
    return htmlspecialchars($text, ENT_QUOTES, 'UTF-8');
}
