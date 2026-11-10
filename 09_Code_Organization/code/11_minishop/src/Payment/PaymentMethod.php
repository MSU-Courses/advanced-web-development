<?php

declare(strict_types=1);

namespace MiniShop\Payment;

interface PaymentMethod
{
    public function title(): string;
    public function fee(int $amount): int;
}
