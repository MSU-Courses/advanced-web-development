<?php

declare(strict_types=1);

require_once __DIR__ . '/src/template.php';

$errors = ['Введите имя', 'Введите корректный email'];

$errorList = '<ul>';
foreach ($errors as $error) {
    $errorList .= '<li>' . htmlspecialchars($error, ENT_QUOTES, 'UTF-8') . '</li>';
}
$errorList .= '</ul>';

echo fillTemplate('bug/errors.html', ['errorList' => $errorList]), PHP_EOL;
