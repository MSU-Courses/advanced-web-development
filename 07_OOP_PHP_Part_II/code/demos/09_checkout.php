<?php

declare(strict_types=1);

// Итог MiniShop: все классы находятся в этом же файле.
// Поменяйте $deliveryCode и $paymentCode в конце файла.
interface DeliveryMethod
{
    public function title(): string;
    public function cost(int $goodsTotal): int;
}

class CourierDelivery implements DeliveryMethod
{
    public function title(): string
    {
        return 'Курьер';
    }

    public function cost(int $goodsTotal): int
    {
        return $goodsTotal >= 1000 ? 0 : 50;
    }
}

class ExpressDelivery implements DeliveryMethod
{
    public function title(): string
    {
        return 'Экспресс-доставка';
    }

    public function cost(int $goodsTotal): int
    {
        return 120;
    }
}

class PostDelivery implements DeliveryMethod
{
    public function title(): string
    {
        return 'Почта Молдовы';
    }

    public function cost(int $goodsTotal): int
    {
        return $goodsTotal >= 1000 ? 0 : 35;
    }
}

class PickupDelivery implements DeliveryMethod
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

interface PaymentMethod
{
    public function title(): string;
    public function fee(int $amount): int;
}

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

interface CartItem
{
    public function title(): string;
    public function price(): int;
}

class Product implements CartItem
{
    public function __construct(private string $name, private int $amount) {}

    public function title(): string
    {
        return $this->name;
    }

    public function price(): int
    {
        return $this->amount;
    }
}

class GiftWrap implements CartItem
{
    public function title(): string
    {
        return 'Подарочная упаковка';
    }

    public function price(): int
    {
        return 49;
    }
}

class Cart
{
    private array $items = [];

    public function add(CartItem $item, int $quantity = 1): void
    {
        $this->items[] = ['item' => $item, 'quantity' => $quantity];
    }

    public function total(): int
    {
        $total = 0;

        foreach ($this->items as $line) {
            $total += $line['item']->price() * $line['quantity'];
        }

        return $total;
    }
}

class Order
{
    public function __construct(
        public readonly Cart $cart,
        public readonly DeliveryMethod $delivery,
        public readonly PaymentMethod $payment,
    ) {}

    public function goodsTotal(): int
    {
        return $this->cart->total();
    }

    public function deliveryCost(): int
    {
        return $this->delivery->cost($this->goodsTotal());
    }

    public function paymentFee(): int
    {
        return $this->payment->fee($this->goodsTotal() + $this->deliveryCost());
    }

    public function total(): int
    {
        return $this->goodsTotal() + $this->deliveryCost() + $this->paymentFee();
    }
}

function makeCart(): Cart
{
    $cart = new Cart();
    $cart->add(new Product('Мышь Logitech M185', 299), 2);
    $cart->add(new Product('Коврик для мыши', 89));
    $cart->add(new GiftWrap());

    return $cart;
}

function makeDelivery(string $code): DeliveryMethod
{
    return match ($code) {
        'courier' => new CourierDelivery(),
        'post' => new PostDelivery(),
        'pickup' => new PickupDelivery(),
        'express' => new ExpressDelivery(),
        default => throw new InvalidArgumentException('Неизвестный способ доставки'),
    };
}

function makePayment(string $code): PaymentMethod
{
    return match ($code) {
        'card' => new CardPayment(),
        'cash' => new CashPayment(),
        default => throw new InvalidArgumentException('Неизвестный способ оплаты'),
    };
}

class Price
{
    public static function format(int $amount): string
    {
        return number_format($amount, 0, ',', ' ') . ' лей';
    }
}

$deliveryCode = 'courier';
$paymentCode = 'cash';

$order = new Order(makeCart(), makeDelivery($deliveryCode), makePayment($paymentCode));

echo 'Товары: ' . Price::format($order->goodsTotal()) . "\n";
echo 'Доставка (' . $order->delivery->title() . '): ' . Price::format($order->deliveryCost()) . "\n";
echo 'Оплата (' . $order->payment->title() . '): ' . Price::format($order->paymentFee()) . "\n";
echo 'Итого: ' . Price::format($order->total()) . "\n";
