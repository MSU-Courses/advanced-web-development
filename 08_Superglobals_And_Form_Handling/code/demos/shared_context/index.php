<?php
$message = 'Введите количество.';

if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST') {
    $quantity = $_POST['quantity'] ?? null;
    $message = is_string($quantity)
        ? 'Строковое значение получено.'
        : 'Поле quantity отсутствует или передано неверно.';
}

require __DIR__ . '/form.php';
