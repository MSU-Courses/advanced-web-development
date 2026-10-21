<?php

declare(strict_types=1);

class DeliveryLabel
{
    public function __construct(private string $title, private int $price) {}

    public function __toString(): string
    {
        return $this->title . ' (' . $this->price . ' лей)';
    }
}

class Config
{
    public function __construct(private array $values) {}

    public function __get(string $name): mixed
    {
        if (!array_key_exists($name, $this->values)) {
            throw new InvalidArgumentException('Нет настройки: ' . $name);
        }

        return $this->values[$name];
    }

    public function __isset(string $name): bool
    {
        return isset($this->values[$name]);
    }
}

class PriceRange
{
    public function __construct(private int $min, private int $max) {}

    public function __invoke(int $price): bool
    {
        return $price >= $this->min && $price <= $this->max;
    }
}

class Address
{
    public function __construct(public string $city) {}
}

class DraftOrder
{
    public function __construct(public Address $address) {}

    public function __clone(): void
    {
        $this->address = clone $this->address;
    }
}

$delivery = new DeliveryLabel('Курьер', 50);
echo $delivery . "\n";

$config = new Config(['currency' => 'лей']);
echo $config->currency . "\n";
echo ($config->phone ?? 'Телефон не указан') . "\n";

$range = new PriceRange(50, 150);
echo implode(', ', array_filter([35, 50, 120, 250], $range)) . "\n";

$first = new DraftOrder(new Address('Кишинёв'));
$second = clone $first;
$second->address->city = 'Бельцы';
echo $first->address->city . ' / ' . $second->address->city . "\n";
