<?php

declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

use Monolog\Handler\StreamHandler;
use Monolog\Level;
use Monolog\Logger;

$logger = new Logger('app');
$logger->pushHandler(new StreamHandler(__DIR__ . '/../var/log/app.log', Level::Info));

$logger->info('Открыта главная страница', ['uri' => $_SERVER['REQUEST_URI'] ?? '/']);
$logger->debug('Это сообщение не попадёт в журнал');

echo 'Запись добавлена в var/log/app.log';
