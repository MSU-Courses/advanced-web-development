<?php

declare(strict_types=1);

interface DeliveryMethod
{
    public function title(): string;
    public function cost(int $goodsTotal): int;
}

abstract class RegularDelivery implements DeliveryMethod
{
    public const FREE_FROM = 1000;

    abstract public function title(): string;
    abstract protected function baseCost(): int;

    final public function cost(int $goodsTotal): int
    {
        if ($goodsTotal >= self::FREE_FROM) {
            return 0;
        }

        return $this->baseCost();
    }
}

class CourierDelivery extends RegularDelivery
{
    public function title(): string
    {
        return 'Курьер';
    }

    protected function baseCost(): int
    {
        return 50;
    }
}

class PostDelivery extends RegularDelivery
{
    public function title(): string
    {
        return 'Почта Молдовы';
    }

    protected function baseCost(): int
    {
        return 35;
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

class Cart
{
    private array $items = [];

    public function add(CartItem $item, int $quantity = 1): void
    {
        if ($quantity <= 0) {
            throw new InvalidArgumentException('Количество должно быть положительным');
        }

        $this->items[] = ['item' => $item, 'quantity' => $quantity];
    }

    public function items(): array
    {
        return $this->items;
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
    $cart->add(new Product('Подарочная упаковка', 49));
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

function h(string $text): string
{
    return htmlspecialchars($text, ENT_QUOTES, 'UTF-8');
}

// Два значения можно выбрать через HTML-форму.
$deliveryOptions = [
    'courier' => 'Курьер',
    'post' => 'Почта Молдовы',
    'pickup' => 'Самовывоз',
    'express' => 'Экспресс-доставка',
];
$paymentOptions = [
    'cash' => 'Наличными при получении',
    'card' => 'Картой',
];

$deliveryCode = $_GET['delivery'] ?? 'courier';
$paymentCode = $_GET['payment'] ?? 'cash';
$error = '';
$order = null;

try {
    if (!is_string($deliveryCode) || !is_string($paymentCode)) {
        throw new InvalidArgumentException('Некорректные параметры формы');
    }

    $order = new Order(makeCart(), makeDelivery($deliveryCode), makePayment($paymentCode));
} catch (InvalidArgumentException $exception) {
    http_response_code(400);
    $error = $exception->getMessage();
}
?>
<!doctype html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>MiniShop - оформление заказа</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 720px; margin: 30px auto; line-height: 1.6; padding: 0 16px; }
        label { display: inline-block; margin-right: 20px; }
        select, button { padding: 6px; }
        table { border-collapse: collapse; width: 100%; }
        th, td { border-bottom: 1px solid #ddd; padding: 8px; text-align: left; }
        .error { color: darkred; }
    </style>
</head>
<body>
    <h1>MiniShop</h1>
    <p>Измените способ доставки и оплаты, чтобы увидеть новый итог заказа.</p>

    <form method="get">
        <label for="delivery">Доставка</label>
        <select id="delivery" name="delivery">
            <?php foreach ($deliveryOptions as $code => $title): ?>
                <option value="<?= h($code) ?>" <?= $deliveryCode === $code ? 'selected' : '' ?>>
                    <?= h($title) ?>
                </option>
            <?php endforeach; ?>
        </select>

        <label for="payment">Оплата</label>
        <select id="payment" name="payment">
            <?php foreach ($paymentOptions as $code => $title): ?>
                <option value="<?= h($code) ?>" <?= $paymentCode === $code ? 'selected' : '' ?>>
                    <?= h($title) ?>
                </option>
            <?php endforeach; ?>
        </select>
        <button type="submit">Пересчитать</button>
    </form>

    <?php if ($error !== ''): ?>
        <p class="error"><?= h($error) ?></p>
    <?php elseif ($order !== null): ?>
        <h2>Корзина</h2>
        <table>
            <tr><th>Товар</th><th>Количество</th><th>Сумма</th></tr>
            <?php foreach ($order->cart->items() as $line): ?>
                <tr>
                    <td><?= h($line['item']->title()) ?></td>
                    <td><?= $line['quantity'] ?></td>
                    <td><?= $line['item']->price() * $line['quantity'] ?> лей</td>
                </tr>
            <?php endforeach; ?>
        </table>
        <p>Товары: <?= $order->goodsTotal() ?> лей</p>
        <p>Доставка (<?= h($order->delivery->title()) ?>): <?= $order->deliveryCost() ?> лей</p>
        <p>Оплата (<?= h($order->payment->title()) ?>): <?= $order->paymentFee() ?> лей</p>
        <h2>Итого: <?= $order->total() ?> лей</h2>
    <?php endif; ?>
</body>
</html>
