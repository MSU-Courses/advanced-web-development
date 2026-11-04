<?php

declare(strict_types=1);

interface PaymentMethod
{
    public function title(): string;
    public function fee(int $amount): int;
}
