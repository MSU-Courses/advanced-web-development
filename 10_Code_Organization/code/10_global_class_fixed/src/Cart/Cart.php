<?php

declare(strict_types=1);

namespace MiniShop\Cart;

class Cart
{
    private array $items = [];

    public function add(CartItem $item, int $quantity = 1): void
    {
        if ($quantity <= 0) {
            throw new \InvalidArgumentException('Количество должно быть положительным');
        }

        $this->items[] = ['item' => $item, 'quantity' => $quantity];
    }

    public function items(): array
    {
        return $this->items;
    }

    public function total(): int
    {
        $total = 0;

        foreach ($this->items as $line) {
            $total += $line['item']->price() * $line['quantity'];
        }

        return $total;
    }
}
