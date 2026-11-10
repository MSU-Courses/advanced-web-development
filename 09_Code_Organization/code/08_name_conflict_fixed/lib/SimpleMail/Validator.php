<?php

declare(strict_types=1);

namespace SimpleMail;

class Validator
{
    public static function isEmail(string $email): bool
    {
        return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
    }
}
