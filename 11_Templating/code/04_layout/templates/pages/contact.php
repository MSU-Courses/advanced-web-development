<?php if ($sent): ?>
    <p>Спасибо, <?= h($form['name']) ?>! Сообщение получено.</p>
<?php else: ?>
    <form method="post">
        <?= render('partials/field.php', [
            'label' => 'Имя',
            'name' => 'name',
            'value' => $form['name'],
            'error' => $errors['name'] ?? '',
        ]) ?>
        <?= render('partials/field.php', [
            'label' => 'Email',
            'name' => 'email',
            'value' => $form['email'],
            'error' => $errors['email'] ?? '',
        ]) ?>
        <?= render('partials/field.php', [
            'label' => 'Сообщение',
            'name' => 'message',
            'value' => $form['message'],
            'error' => $errors['message'] ?? '',
            'multiline' => true,
        ]) ?>
        <button type="submit">Отправить</button>
    </form>
<?php endif; ?>
