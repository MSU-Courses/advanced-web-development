<?php

declare(strict_types=1);

function h(string $text): string
{
    return htmlspecialchars($text, ENT_QUOTES, 'UTF-8');
}

function formatPrice(int $amount, string $currency): string
{
    return $amount . ' ' . $currency;
}
