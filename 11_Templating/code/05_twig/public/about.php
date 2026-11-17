<?php

declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../src/twig.php';
$config = require __DIR__ . '/../config/app.php';

$twig = createTwig();

echo $twig->render('about.html.twig', $config);
