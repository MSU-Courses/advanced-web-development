<?php

declare(strict_types=1);

require_once __DIR__ . '/../src/template.php';
require_once __DIR__ . '/../src/contact.php';
$config = require __DIR__ . '/../config/app.php';

// 1. Логика: обрабатываем форму
$form = ['name' => '', 'email' => '', 'message' => '']; // поля пока пустые
$errors = [];
$sent = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $form = readContactForm($_POST);  // читаем поля формы
    $errors = validateContact($form); // проверяем их
    $sent = ($errors === []);         // ошибок нет - сообщение принято
}

// 2. Представление: подставляем данные в шаблон с метками
if ($sent) {
    echo fillTemplate('thanks.html', [
        'title' => 'Обратная связь',
        'siteName' => $config['siteName'],
        'adminEmail' => $config['adminEmail'],
        'name' => $form['name'],
    ]);
    exit;
}

echo fillTemplate('contact.html', [
    'title' => 'Обратная связь',
    'siteName' => $config['siteName'],
    'adminEmail' => $config['adminEmail'],
    'name' => $form['name'],
    'email' => $form['email'],
    'message' => $form['message'],
    'nameError' => $errors['name'] ?? '',
    'emailError' => $errors['email'] ?? '',
    'messageError' => $errors['message'] ?? '',
]);
