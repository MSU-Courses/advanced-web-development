<?php

declare(strict_types=1);

// Способ доставки умеет назвать себя и посчитать стоимость.
interface DeliveryMethod
{
    public function title(): string;
    public function cost(int $goodsTotal): int;
}

// Объект с номером отслеживания можно показать пользователю.
interface Trackable
{
    public function trackingNumber(): string;
}

// Курьерская доставка выполняет сразу две роли.
final class CourierDelivery implements DeliveryMethod, Trackable
{
    public function __construct(private string $number) {}

    public function title(): string
    {
        return 'Курьер';
    }

    public function cost(int $goodsTotal): int
    {
        return $goodsTotal >= 1000 ? 0 : 50;
    }

    public function trackingNumber(): string
    {
        return $this->number;
    }
}

// Самовывоз не предоставляет отслеживание.
final class PickupDelivery implements DeliveryMethod
{
    public function title(): string
    {
        return 'Самовывоз';
    }

    public function cost(int $goodsTotal): int
    {
        return 0;
    }
}

// Посылка предоставляет отслеживание, но не рассчитывает стоимость доставки.
final class Parcel implements Trackable
{
    public function __construct(private string $number) {}

    public function trackingNumber(): string
    {
        return $this->number;
    }
}

function trackingLine(Trackable $item): string
{
    return 'Отслеживание: ' . $item->trackingNumber();
}

function deliveryLine(DeliveryMethod $delivery, int $goodsTotal): string
{
    return $delivery->title() . ': ' . $delivery->cost($goodsTotal) . ' лей';
}

$courier = new CourierDelivery('MD12345');
$pickup = new PickupDelivery();
$parcel = new Parcel('MD67890');

echo trackingLine($courier) . "\n"; // Отслеживание: MD12345
echo trackingLine($parcel) . "\n";  // Отслеживание: MD67890
echo deliveryLine($courier, 600) . "\n"; // Курьер: 50 лей
echo deliveryLine($pickup, 600) . "\n";  // Самовывоз: 0 лей
