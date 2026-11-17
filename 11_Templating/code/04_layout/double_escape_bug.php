<?php

declare(strict_types=1);

require_once __DIR__ . '/src/view.php';

$content = render('pages/about.php');

echo h($content), PHP_EOL;
echo $content;
