<?php

declare(strict_types=1);

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
