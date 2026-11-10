<?php

declare(strict_types=1);

interface CartItem
{
    public function title(): string;
    public function price(): int;
}
