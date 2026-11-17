<?php

declare(strict_types=1);

function h(string $text): string
{
    return htmlspecialchars($text, ENT_QUOTES, 'UTF-8');
}

function render(string $template, array $data = []): string
{
    extract($data, EXTR_SKIP);

    ob_start();
    require __DIR__ . '/../templates/' . $template;

    return ob_get_clean();
}
