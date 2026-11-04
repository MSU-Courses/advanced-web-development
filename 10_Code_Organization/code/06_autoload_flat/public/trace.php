<?php

declare(strict_types=1);

spl_autoload_register(function (string $class): void {
    if (file_exists(__DIR__ . '/../src/' . $class . '.php')) {
        echo "Автозагрузчик ищет класс $class\n";
        require __DIR__ . '/../src/' . $class . '.php';
    }
});

$delivery = new CourierDelivery();

echo $delivery->title() . ': ' . $delivery->cost(736) . " лей\n";
