<?php

declare(strict_types=1);

class CashPayment implements PaymentMethod
{
    public function title(): string
    {
        return 'Наличными при получении';
    }

    public function fee(int $amount): int
    {
        return 20;
    }
}
