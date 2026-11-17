<?php

declare(strict_types=1);

ob_start();
echo 'A';

ob_start();
echo 'B';
$inner = ob_get_clean();

echo '[' . $inner . ']';
$outer = ob_get_clean();

echo $outer, PHP_EOL;
