<?php

declare(strict_types=1);

class Product implements CartItem
{
    public function __construct(private string $name, private int $amount) {}

    public function title(): string
    {
        return $this->name;
    }

    public function price(): int
    {
        return $this->amount;
    }
}
