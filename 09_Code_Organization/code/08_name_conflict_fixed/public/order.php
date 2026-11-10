<?php

declare(strict_types=1);

require_once __DIR__ . '/../src/Form/Validator.php';
require_once __DIR__ . '/../lib/SimpleMail/Validator.php';

// use MiniShop\Form\Validator;
// use SimpleMail\Validator as MailValidator;

var_dump(MiniShop\Form\Validator::isQuantity(2));
var_dump(SimpleMail\Validator::isEmail('buyer@example.com'));
var_dump(\SimpleMail\Validator::isEmail('buyer@'));
