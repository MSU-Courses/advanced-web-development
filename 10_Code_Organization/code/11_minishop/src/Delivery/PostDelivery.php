<?php

declare(strict_types=1);

namespace MiniShop\Delivery;

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
