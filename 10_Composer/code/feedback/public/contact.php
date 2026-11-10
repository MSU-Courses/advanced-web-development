<?php

declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

use App\Contact\ContactForm;
use App\Contact\Validator;
use App\Http\ContactHandler;
use Egulias\EmailValidator\EmailValidator;
use Monolog\Handler\StreamHandler;
use Monolog\Level;
use Monolog\Logger;

$config = require __DIR__ . '/../config/app.php';

$result = ['form' => ContactForm::empty(), 'errors' => [], 'sent' => false];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $logger = new Logger('feedback');
    $logger->pushHandler(new StreamHandler($config['logFile'], Level::Info));

    $handler = new ContactHandler(new Validator(new EmailValidator()), $logger);
    $result = $handler->handle($_POST);
}

$form = $result['form'];
$errors = $result['errors'];
$sent = $result['sent'];

$title = 'Обратная связь';
require __DIR__ . '/../templates/header.php';
require __DIR__ . '/../templates/contact_form.php';
require __DIR__ . '/../templates/footer.php';
