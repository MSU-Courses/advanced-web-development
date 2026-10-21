<?php

declare(strict_types=1);

class Price
{
    public const CURRENCY = 'лей';

    public static function format(int $amount): string
    {
        return number_format($amount, 0, ',', ' ') . ' ' . self::CURRENCY;
    }
}

class OrderCounter
{
    private static int $lastNumber = 0;

    public static function next(): int
    {
        return ++self::$lastNumber;
    }
}

echo Price::format(14999) . "\n"; // 14 999 лей
echo OrderCounter::next() . "\n"; // 1
echo OrderCounter::next() . "\n"; // 2

// Если запустить файл заново, счётчик снова начнётся с 1.
