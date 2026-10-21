<?php

declare(strict_types=1);

// Пока у классов нет ни общего родителя, ни интерфейса.
class Courier
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

class Pickup
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

$deliveries = [new Courier(), new Pickup()];

foreach ($deliveries as $delivery) {
    echo $delivery->title() . ': ' . $delivery->cost(600) . " лей\n";
}

// Курьер: 50 лей
// Самовывоз: 0 лей
