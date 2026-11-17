<?php

function showHeader(string $siteName): void
{
    require __DIR__ . '/site_name.php';
}

showHeader('Учебный сайт');
echo PHP_EOL;
