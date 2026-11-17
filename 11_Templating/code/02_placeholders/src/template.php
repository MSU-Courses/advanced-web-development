<?php

declare(strict_types=1);

function fillTemplate(string $file, array $data): string
{
    $replacements = [];

    foreach ($data as $key => $value) {
        $replacements['{{' . $key . '}}'] = htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
    }

    $template = file_get_contents(__DIR__ . '/../templates/' . $file);

    return strtr($template, $replacements);
}
