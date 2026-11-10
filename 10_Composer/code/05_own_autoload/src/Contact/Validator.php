<?php

declare(strict_types=1);

namespace App\Contact;

class Validator
{
    public function validate(ContactForm $form): array
    {
        $errors = [];

        if ($form->name === '') {
            $errors['name'] = 'Введите имя';
        }

        if (filter_var($form->email, FILTER_VALIDATE_EMAIL) === false) {
            $errors['email'] = 'Введите корректный email';
        }

        return $errors;
    }
}
