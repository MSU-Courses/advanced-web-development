<?php

declare(strict_types=1);

require_once __DIR__ . '/../src/template.php';
$config = require __DIR__ . '/../config/app.php';

echo fillTemplate('about.html', [
    'title' => 'О сайте',
    'siteName' => $config['siteName'],
    'adminEmail' => $config['adminEmail'],
]);
