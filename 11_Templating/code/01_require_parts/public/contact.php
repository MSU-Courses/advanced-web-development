<?php

declare(strict_types=1);

require_once __DIR__ . '/../src/functions.php';
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

// 2. Представление: подключаем части страницы
$title = 'Обратная связь';
require __DIR__ . '/../templates/header.php';
require __DIR__ . '/../templates/contact_form.php';
require __DIR__ . '/../templates/footer.php';
