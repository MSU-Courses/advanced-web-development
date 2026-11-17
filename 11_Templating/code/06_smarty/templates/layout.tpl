<!doctype html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <title>{block name=title}{/block} - {$siteName}</title>
</head>
<body>
    <header>{$siteName}</header>
    <main>
        {block name=content}{/block}
    </main>
    <footer>Вопросы: {$adminEmail}</footer>
</body>
</html>
