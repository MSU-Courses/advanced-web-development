<?php
$message = 'Введите количество и отправьте форму.';

if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST') {
    $quantity = $_POST['quantity'] ?? null;

    if (is_string($quantity)) {
        $message = 'Строковое значение получено.';
    } else {
        $message = 'Поле quantity отсутствует или передано неверно.';
    }
}
?>
<p><?= $message ?></p>
<form action="index.php" method="post">
    <input name="quantity" type="number" value="2">
    <button type="submit">Отправить</button>
</form>
