<!doctype html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <title><?= h($title) ?> - <?= h($config['shopName']) ?></title>
</head>
<body>
    <h1><?= h($config['shopName']) ?></h1>
    <nav><a href="catalog.php">Каталог</a> | <a href="delivery.php">Доставка</a></nav>
    <h2><?= h($title) ?></h2>
