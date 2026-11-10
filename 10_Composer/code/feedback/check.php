<?php

declare(strict_types=1);

require __DIR__ . '/vendor/autoload.php';

use App\Contact\Validator;
use App\Http\ContactHandler;
use Egulias\EmailValidator\EmailValidator;
use Monolog\Handler\StreamHandler;
use Monolog\Level;
use Monolog\Logger;

$config = require __DIR__ . '/config/app.php';

$logger = new Logger('feedback');
$logger->pushHandler(new StreamHandler($config['logFile'], Level::Info));

$handler = new ContactHandler(new Validator(new EmailValidator()), $logger);

$bad = $handler->handle(['name' => '', 'email' => 'anna@', 'message' => 'Привет']);
print_r($bad['errors']);

$good = $handler->handle([
    'name' => 'Анна',
    'email' => 'anna@example.com',
    'message' => 'Здравствуйте! Хочу задать вопрос.',
]);
var_dump($good['sent']);
