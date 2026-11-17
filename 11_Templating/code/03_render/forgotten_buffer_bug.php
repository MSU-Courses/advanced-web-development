<?php

declare(strict_types=1);

// ОШИБКА НАМЕРЕННО: функция включает буфер, но не забирает его содержимое.
function renderBroken(string $text): string
{
    ob_start();
    echo '<p>' . $text . '</p>';

    return ''; // забыли ob_get_clean()
}

$html = renderBroken('Содержимое страницы');

echo '<!doctype html>', PHP_EOL;
echo '<main>', $html, '</main>', PHP_EOL;
