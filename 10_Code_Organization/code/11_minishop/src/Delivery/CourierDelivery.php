<?php

declare(strict_types=1);

namespace MiniShop\Delivery;

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
