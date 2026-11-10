<?php

declare(strict_types=1);

namespace App\Contact;

use Egulias\EmailValidator\EmailValidator;
use Egulias\EmailValidator\Validation\RFCValidation;

class Validator
{
    public const MESSAGE_MIN_LENGTH = 10;
    public const MESSAGE_MAX_LENGTH = 1000;

    public function __construct(private EmailValidator $emailValidator) {}

    public function validate(ContactForm $form): array
    {
        $errors = [];

        if ($form->name === '') {
            $errors['name'] = 'Введите имя';
        }

        if (!$this->emailValidator->isValid($form->email, new RFCValidation())) {
            $errors['email'] = 'Введите корректный email';
        }

        $length = mb_strlen($form->message);

        if ($length < self::MESSAGE_MIN_LENGTH || $length > self::MESSAGE_MAX_LENGTH) {
            $errors['message'] = 'Сообщение должно содержать от 10 до 1000 символов';
        }

        return $errors;
    }
}
