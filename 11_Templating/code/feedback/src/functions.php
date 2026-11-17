<?php

declare(strict_types=1);

use Twig\Environment;
use Twig\Loader\FilesystemLoader;

function createTwig(): Environment
{
    $loader = new FilesystemLoader(__DIR__ . '/../templates');

    return new Environment($loader, [
        'cache' => __DIR__ . '/../var/cache/twig',
        'auto_reload' => true,
        'strict_variables' => true,
    ]);
}
