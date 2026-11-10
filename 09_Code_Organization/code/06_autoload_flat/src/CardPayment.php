<?php

declare(strict_types=1);

class CardPayment implements PaymentMethod
{
    public function title(): string
    {
        return 'Картой';
    }

    public function fee(int $amount): int
    {
        return 0;
    }
}
