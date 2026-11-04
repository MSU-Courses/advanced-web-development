<?php

declare(strict_types=1);

class Validator
{
    public static function isQuantity(int $quantity): bool
    {
        return $quantity >= 1 && $quantity <= 10;
    }
}
