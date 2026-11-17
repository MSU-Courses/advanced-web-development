<?php

declare(strict_types=1);

require_once __DIR__ . '/../src/view.php';
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

// 2. Представление: рендерим страницу внутри layout
echo renderPage('contact.php', [
    'title' => 'Обратная связь',
    'siteName' => $config['siteName'],
    'adminEmail' => $config['adminEmail'],
    'form' => $form,
    'errors' => $errors,
    'sent' => $sent,
]);
