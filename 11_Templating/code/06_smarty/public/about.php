<?php

declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../src/smarty.php';
$config = require __DIR__ . '/../config/app.php';

$smarty = createSmarty();
$smarty->assign($config);
$smarty->display('about.tpl');
