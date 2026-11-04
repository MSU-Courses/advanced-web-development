<?php

declare(strict_types=1);

require_once __DIR__ . '/../src/functions.php';
$config = require __DIR__ . '/../config/shop.php';
$products = require __DIR__ . '/../data/products.php';

$title = 'Каталог';
require __DIR__ . '/../templates/header.php';
?>
    <ul>
        <?php foreach ($products as $product): ?>
            <li><?= h($product['title']) ?> - <?= formatPrice($product['price'], $config['currency']) ?></li>
        <?php endforeach; ?>
    </ul>
<?php require __DIR__ . '/../templates/footer.php'; ?>
