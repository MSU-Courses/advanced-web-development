<?php

declare(strict_types=1);

const MESSAGE_MIN_LENGTH = 10;
const MESSAGE_MAX_LENGTH = 1000;

function readContactForm(array $input): array
{
    $form = ['name' => '', 'email' => '', 'message' => ''];

    foreach ($form as $key => $value) {
        $value = $input[$key] ?? '';
        $form[$key] = is_string($value) ? trim($value) : '';
    }

    return $form;
}

function validateContact(array $form): array
{
    $errors = [];

    if ($form['name'] === '') {
        $errors['name'] = 'Введите имя';
    }

    if (filter_var($form['email'], FILTER_VALIDATE_EMAIL) === false) {
        $errors['email'] = 'Введите корректный email';
    }

    $length = mb_strlen($form['message']);

    if ($length < MESSAGE_MIN_LENGTH || $length > MESSAGE_MAX_LENGTH) {
        $errors['message'] = 'Сообщение должно содержать от 10 до 1000 символов';
    }

    return $errors;
}
