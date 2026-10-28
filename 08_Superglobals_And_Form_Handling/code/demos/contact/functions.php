<?php

function validateRequired(string $field, string $value): bool
{
    if (empty($value)) {
        return false;
    }
    return true;
}

function validateEmail(string $email): bool
{
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

function validateLength(string $field, string $value, int $min, int $max): bool
{
    $length = mb_strlen($value, 'UTF-8');
    return $length >= $min && $length <= $max;
}

function validateEnum(string $field, string $value, array $allowedValues): bool
{
    return in_array($value, $allowedValues, true);
}

function getErrors(array $errors, string $field): string {
    $html = '';

    if (!empty($errors[$field])) {
        $html .= '<ul>';
        foreach ($errors[$field] as $error) {
            $html .= '<li>' . htmlspecialchars($error, ENT_QUOTES, 'UTF-8') . '</li>';
        }
        $html .= '</ul>';
    }

    return $html;
}

function getOldValue(string $field): string
{
    $value = $_POST[$field] ?? '';
    return is_string($value) ? htmlspecialchars($value, ENT_QUOTES, 'UTF-8') : '';
}
