<?php

// index.php

require_once 'handler.php';

?>

<form action="index.php" method="post">
    <label for="name">Имя:</label>
    <input type="text" name="name" id="name" required value="<?php echo getOldValue('name'); ?>">
    <?php echo getErrors($errors, 'name'); ?>

    <label for="email">Email:</label>
    <input type="email" name="email" id="email" required value="<?php echo getOldValue('email'); ?>">
    <?php echo getErrors($errors, 'email'); ?>

    <label for="message">Сообщение:</label>
    <textarea name="message" id="message" required><?php echo getOldValue('message'); ?></textarea>
    <?php echo getErrors($errors, 'message'); ?>

    <label for="category">Категория:</label>
    <select name="category" id="category" required>
        <option value="">Выберите категорию</option>
        <option value="1" <?php echo (getOldValue('category') === '1') ? 'selected' : ''; ?>>Категория 1</option>
        <option value="2" <?php echo (getOldValue('category') === '2') ? 'selected' : ''; ?>>Категория 2</option>
        <option value="3" <?php echo (getOldValue('category') === '3') ? 'selected' : ''; ?>>Категория 3</option>
    </select>
    <?php echo getErrors($errors, 'category'); ?>

    <button type="submit">Отправить</button>
</form>
