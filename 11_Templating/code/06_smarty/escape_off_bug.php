<?php

declare(strict_types=1);

require __DIR__ . '/vendor/autoload.php';

use Smarty\Smarty;

function greet(bool $escape): string
{
    $smarty = new Smarty();
    $smarty->setCompileDir(__DIR__ . '/var/smarty/compile_' . ($escape ? 'on' : 'off'));
    $smarty->setEscapeHtml($escape);
    $smarty->assign('name', '<script>alert(1)</script>');

    return $smarty->fetch('string:<p>Спасибо, {$name}!</p>');
}

echo greet(false), PHP_EOL;
echo greet(true), PHP_EOL;
