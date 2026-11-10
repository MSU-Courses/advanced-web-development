<?php

declare(strict_types=1);

require_once __DIR__ . '/../src/Validator.php';
require_once __DIR__ . '/../lib/SimpleMail/Validator.php';

var_dump(Validator::isQuantity(2));
