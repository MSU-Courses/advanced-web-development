<?php

declare(strict_types=1);

namespace MiniShop\Delivery;

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
