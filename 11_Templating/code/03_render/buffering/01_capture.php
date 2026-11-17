<?php

declare(strict_types=1);

ob_start();

echo 'Привет, ';
echo 'мир!';

$text = ob_get_clean();

echo mb_strtoupper($text), PHP_EOL;
echo mb_strlen($text), PHP_EOL;
