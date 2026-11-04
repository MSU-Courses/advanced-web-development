<?php

declare(strict_types=1);

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
