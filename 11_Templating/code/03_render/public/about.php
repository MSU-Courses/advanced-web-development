<?php

declare(strict_types=1);

require_once __DIR__ . '/../src/view.php';
$config = require __DIR__ . '/../config/app.php';

echo render('about.php', [
    'title' => 'О сайте',
    'siteName' => $config['siteName'],
    'adminEmail' => $config['adminEmail'],
]);
