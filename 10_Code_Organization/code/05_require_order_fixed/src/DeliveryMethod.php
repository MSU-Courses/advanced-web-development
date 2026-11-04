<?php

declare(strict_types=1);

interface DeliveryMethod
{
    public function title(): string;
    public function cost(int $goodsTotal): int;
}
