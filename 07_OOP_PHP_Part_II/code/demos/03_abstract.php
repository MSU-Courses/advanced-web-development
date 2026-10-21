<?php

declare(strict_types=1);

interface DeliveryMethod
{
    public function title(): string;
    public function cost(int $goodsTotal): int;
}

// У курьера и почты одно правило бесплатной доставки.
abstract class RegularDelivery implements DeliveryMethod
{
    public const FREE_FROM = 1000;

    abstract public function title(): string;
    abstract protected function baseCost(): int;

    final public function cost(int $goodsTotal): int
    {
        if ($goodsTotal >= self::FREE_FROM) {
            return 0;
        }

        return $this->baseCost();
    }
}

class CourierDelivery extends RegularDelivery
{
    public function title(): string
    {
        return 'Курьер';
    }

    protected function baseCost(): int
    {
        return 50;
    }
}

class PostDelivery extends RegularDelivery
{
    public function title(): string
    {
        return 'Почта Молдовы';
    }

    protected function baseCost(): int
    {
        return 35;
    }
}

// Экспресс не наследует RegularDelivery:
// его стоимость не зависит от суммы товаров.
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

$deliveries = [new CourierDelivery(), new PostDelivery(), new ExpressDelivery()];

foreach ([600, 1200] as $goodsTotal) {
    echo "Сумма товаров: $goodsTotal лей\n";

    foreach ($deliveries as $delivery) {
        echo '  ' . $delivery->title() . ': ' . $delivery->cost($goodsTotal) . " лей\n";
    }
}
