<?php

declare(strict_types=1);

require_once __DIR__ . '/../src/functions.php';
$config = require __DIR__ . '/../config/shop.php';

$title = 'Доставка';
require __DIR__ . '/../templates/header.php';
?>
    <p>Бесплатная доставка от <?= formatPrice($config['freeDeliveryFrom'], $config['currency']) ?>.</p>
<?php require __DIR__ . '/../templates/footer.php'; ?>
