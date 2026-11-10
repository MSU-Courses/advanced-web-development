<?php

declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

use App\Contact\ContactForm;
use App\Contact\Validator;

$form = ContactForm::fromArray(['name' => '<b>Анна</b>', 'email' => 'anna@']);
$errors = (new Validator())->validate($form);

echo h($form->name), "\n";
print_r($errors);
