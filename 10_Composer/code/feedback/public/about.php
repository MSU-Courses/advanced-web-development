<?php

declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

$config = require __DIR__ . '/../config/app.php';

$title = 'О сайте';
require __DIR__ . '/../templates/header.php';
?>
    <p>Учебный сайт с формой обратной связи.</p>
<?php require __DIR__ . '/../templates/footer.php'; ?>
