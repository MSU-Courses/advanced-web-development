<?php

$siteName = 'Учебный сайт';

function showHeader(): void
{
    require __DIR__ . '/site_name.php';
}

showHeader();
echo PHP_EOL;
