<?php

declare(strict_types=1);

// Объявляем всё необходимое в одном файле.
interface DeliveryMethod
{
    public function title(): string;
    public function cost(int $goodsTotal): int;
}

class PickupDelivery implements DeliveryMethod
{
    public function title(): string
    {
        return 'Самовывоз';
    }

    public function cost(int $goodsTotal): int
    {
        return 0;
    }
}

class ExpressDelivery implements DeliveryMethod
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

class CourierDelivery implements DeliveryMethod
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

function deliveryLine(DeliveryMethod $delivery, int $goodsTotal): string
{
    return $delivery->title() . ': ' . $delivery->cost($goodsTotal) . ' лей';
}

$deliveries = [
    new PickupDelivery(),
    new ExpressDelivery(),
    new CourierDelivery(),
];

foreach ($deliveries as $delivery) {
    echo deliveryLine($delivery, 1200) . "\n";
}

$courier = new CourierDelivery();
var_dump($courier instanceof DeliveryMethod); // bool(true)
