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
