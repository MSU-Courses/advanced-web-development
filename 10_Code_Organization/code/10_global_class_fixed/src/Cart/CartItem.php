<?php

declare(strict_types=1);

namespace MiniShop\Cart;

interface CartItem
{
    public function title(): string;
    public function price(): int;
}
