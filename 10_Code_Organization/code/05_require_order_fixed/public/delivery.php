<?php

declare(strict_types=1);

require_once __DIR__ . '/../src/DeliveryMethod.php';
require_once __DIR__ . '/../src/RegularDelivery.php';
require_once __DIR__ . '/../src/CourierDelivery.php';

$delivery = new CourierDelivery();

echo $delivery->title() . ': ' . $delivery->cost(736) . " лей\n";
