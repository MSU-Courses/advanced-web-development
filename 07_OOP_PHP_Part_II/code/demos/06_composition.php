<?php

declare(strict_types=1);

// Каждый файл примеров запускается самостоятельно.
interface DeliveryMethod
{
    public function title(): string;
    public function cost(int $goodsTotal): int;
}

final class CourierDelivery implements DeliveryMethod
{
    public function title(): string
    {
        return 'Курьер';
    }

    public function cost(int $goodsTotal): int
    {
        return $goodsTotal >= 1000 ? 0 : 50;
    }
}

final class ExpressDelivery implements DeliveryMethod
{
    public function title(): string
    {
        return 'Экспресс-доставка';
    }

    public function cost(int $goodsTotal): int
    {
        return 120;
    }
}

interface PaymentMethod
{
    public function title(): string;
    public function fee(int $amount): int;
}

final class CardPayment implements PaymentMethod
{
    public function title(): string
    {
        return 'Картой';
    }

    public function fee(int $amount): int
    {
        return 0;
    }
}

final class CashPayment implements PaymentMethod
{
    public function title(): string
    {
        return 'Наличными при получении';
    }

    public function fee(int $amount): int
    {
        return 20;
    }
}

// Заказ использует объекты, а не наследует способы доставки или оплаты.
final class Order
{
    public function __construct(
        private int $goodsTotal,
        private DeliveryMethod $delivery,
        private PaymentMethod $payment,
    ) {}

    public function goodsTotal(): int
    {
        return $this->goodsTotal;
    }

    public function deliveryCost(): int
    {
        return $this->delivery->cost($this->goodsTotal);
    }

    public function paymentFee(): int
    {
        return $this->payment->fee($this->goodsTotal + $this->deliveryCost());
    }

    public function total(): int
    {
        return $this->goodsTotal + $this->deliveryCost() + $this->paymentFee();
    }
}

$deliveries = [new CourierDelivery(), new ExpressDelivery()];
$payments = [new CardPayment(), new CashPayment()];

foreach ($deliveries as $delivery) {
    foreach ($payments as $payment) {
        $order = new Order(736, $delivery, $payment);
        echo $delivery->title() . ' / ' . $payment->title()
            . ': ' . $order->total() . " лей\n";
    }
}
