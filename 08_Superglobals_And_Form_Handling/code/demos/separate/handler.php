<?php
if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') {
    echo 'Откройте form.php и отправьте форму.';
    exit;
}

$quantity = $_POST['quantity'] ?? null;

if (!is_string($quantity)) {
    echo 'Поле quantity отсутствует или передано неверно.';
    exit;
}

echo 'Строковое значение получено.';
