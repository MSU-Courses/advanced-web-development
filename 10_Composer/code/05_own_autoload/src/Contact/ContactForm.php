<?php

declare(strict_types=1);

namespace App\Contact;

class ContactForm
{
    public function __construct(
        public readonly string $name,
        public readonly string $email,
        public readonly string $message,
    ) {}

    public static function fromArray(array $input): self
    {
        return new self(
            self::field($input, 'name'),
            self::field($input, 'email'),
            self::field($input, 'message'),
        );
    }

    private static function field(array $input, string $key): string
    {
        $value = $input[$key] ?? '';

        return is_string($value) ? trim($value) : '';
    }
}
