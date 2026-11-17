<!doctype html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <title><?= h($title) ?> - <?= h($siteName) ?></title>
</head>
<body>
    <header><?= h($siteName) ?></header>
    <main>
        <h1><?= h($title) ?></h1>
        <?= $content ?>
    </main>
    <footer>Вопросы: <?= h($adminEmail) ?></footer>
</body>
</html>
