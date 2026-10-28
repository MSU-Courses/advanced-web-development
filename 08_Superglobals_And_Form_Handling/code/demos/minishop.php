<?php
function h(string $value): string
{
    return htmlspecialchars($value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

$price = 120;
$email = '';
$quantityInput = '1';
$delivery = 'pickup';
$agree = false;
$errors = [];
$total = null;

if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST') {
    $rawEmail = $_POST['email'] ?? null;
    $rawQuantity = $_POST['quantity'] ?? null;
    $rawDelivery = $_POST['delivery'] ?? null;
    $rawAgree = $_POST['agree'] ?? null;

    if (!is_string($rawEmail)) {
        $errors['email'] = 'Передайте адрес почты одной строкой.';
    } else {
        $email = trim($rawEmail);

        if ($email === '') {
            $errors['email'] = 'Укажите адрес почты.';
        } elseif (filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
            $errors['email'] = 'Проверьте формат адреса почты.';
        }
    }

    if (!is_string($rawQuantity)) {
        $quantityInput = '';
        $errors['quantity'] = 'Передайте количество одной строкой.';
    } else {
        $quantityInput = trim($rawQuantity);
        $quantity = filter_var($quantityInput, FILTER_VALIDATE_INT, [
            'options' => ['min_range' => 1, 'max_range' => 10],
        ]);

        if ($quantity === false) {
            $errors['quantity'] = 'Введите целое число от 1 до 10.';
        }
    }

    $delivery = '';
    if (!is_string($rawDelivery)
        || !in_array($rawDelivery, ['pickup', 'courier'], true)) {
        $errors['delivery'] = 'Выберите способ получения.';
    } else {
        $delivery = $rawDelivery;
    }

    $agree = is_string($rawAgree) && $rawAgree === 'yes';
    if (!$agree) {
        $errors['agree'] = 'Подтвердите согласие с условиями расчёта.';
    }

    if ($errors === []) {
        $total = $quantity * $price;
    }
}
?>
<!doctype html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>MiniShop: расчёт</title>
</head>
<body>
<h1>Предварительный расчёт</h1>
<p>Цена товара: 120 леев. Доставка в расчёт не включена.</p>

<form action="minishop.php" method="post">
    <p>
        <label for="email">Почта:</label>
        <input id="email" name="email" type="email"
               value="<?= h($email) ?>" required>
        <span><?= h($errors['email'] ?? '') ?></span>
    </p>
    <p>
        <label for="quantity">Количество:</label>
        <input id="quantity" name="quantity" type="text" inputmode="numeric"
               value="<?= h($quantityInput) ?>" required>
        <span><?= h($errors['quantity'] ?? '') ?></span>
    </p>
    <p>
        <label for="delivery">Получение:</label>
        <select id="delivery" name="delivery" required>
            <option value="">Выберите вариант</option>
            <option value="pickup" <?= $delivery === 'pickup' ? 'selected' : '' ?>>
                Самовывоз
            </option>
            <option value="courier" <?= $delivery === 'courier' ? 'selected' : '' ?>>
                Курьер
            </option>
        </select>
        <span><?= h($errors['delivery'] ?? '') ?></span>
    </p>
    <p>
        <label>
            <input type="checkbox" name="agree" value="yes"
                   <?= $agree ? 'checked' : '' ?> required>
            Согласен с условиями расчёта
        </label>
        <span><?= h($errors['agree'] ?? '') ?></span>
    </p>
    <button type="submit">Рассчитать</button>
</form>

<?php if ($total !== null) { ?>
    <p>Стоимость товара: <?= $total ?> леев.</p>
<?php } ?>
</body>
</html>
