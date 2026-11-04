<?php

declare(strict_types=1);

function formatPrice(int $amount, string $currency): string
{
    return $amount . ' ' . $currency;
}
