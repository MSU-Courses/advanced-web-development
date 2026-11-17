<?php

declare(strict_types=1);

$name = 'Анна';

ob_start();
require __DIR__ . '/templates/greeting.php';
$html = ob_get_clean();

echo 'Длина HTML: ', mb_strlen($html), PHP_EOL;
echo $html;
