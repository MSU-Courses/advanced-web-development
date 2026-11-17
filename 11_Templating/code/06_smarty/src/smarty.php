<?php

declare(strict_types=1);

use Smarty\Smarty;

function createSmarty(): Smarty
{
    $smarty = new Smarty();
    $smarty->setTemplateDir(__DIR__ . '/../templates');
    $smarty->setCompileDir(__DIR__ . '/../var/smarty/compile');
    $smarty->setEscapeHtml(true);

    return $smarty;
}
