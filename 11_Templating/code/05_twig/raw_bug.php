<?php

declare(strict_types=1);

require __DIR__ . '/vendor/autoload.php';

use Twig\Environment;
use Twig\Loader\ArrayLoader;

$twig = new Environment(new ArrayLoader([
    'greeting' => '<p>Спасибо, {{ name }}!</p> <p>Спасибо, {{ name|raw }}!</p>',
]));

echo $twig->render('greeting', ['name' => '<script>alert(1)</script>']), PHP_EOL;
