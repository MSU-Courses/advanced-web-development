<?php

declare(strict_types=1);

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
